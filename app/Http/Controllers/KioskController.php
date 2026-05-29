<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Queue;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    /**
     * Show kiosk interface
     */
    public function index(Request $request)
    {
        // Get branch from authenticated kiosk user or from route
        $user = auth()->user();
        $branch = null;

        if ($user && $user->branch_id) {
            $branch = $user->branch;
        } elseif ($request->has('branch')) {
            $branch = Branch::find($request->branch);
        }

        if (!$branch) {
            // Show branch selection if not determined
            $branches = Branch::active()->get();
            return view('kiosk.select-branch', compact('branches'));
        }

        // Get pending queues count for each service type
        $pendingTeller = Queue::forBranch($branch->id)
            ->today()
            ->pending()
            ->forTeller()
            ->count();

        $pendingCs = Queue::forBranch($branch->id)
            ->today()
            ->pending()
            ->forCs()
            ->count();

        $pendingAdmin = Queue::forBranch($branch->id)
            ->today()
            ->pending()
            ->forAdmin()
            ->count();

        return view('kiosk.index', compact('branch', 'pendingTeller', 'pendingCs', 'pendingAdmin'));
    }

    /**
     * Store new queue ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'customer_note' => 'required|string',
        ]);

        // Determine service type based on customer_note
        $serviceType = Queue::determineServiceType($validated['customer_note']);

        // Generate queue number
        $queueNumber = Queue::generateQueueNumber($validated['branch_id'], $serviceType);

        // Create queue
        $queue = Queue::create([
            'branch_id' => $validated['branch_id'],
            'queue_number' => $queueNumber,
            'service_type' => $serviceType,
            'customer_note' => $validated['customer_note'],
            'status' => 'pending',
        ]);

        // Load branch for ticket display
        $queue->load('branch');

        // Get waiting count
        $waitingCount = Queue::forBranch($validated['branch_id'])
            ->today()
            ->pending()
            ->where('service_type', $serviceType)
            ->where('id', '<', $queue->id)
            ->count();

        return view('kiosk.ticket', compact('queue', 'waitingCount'));
    }

    /**
     * Get services list for AJAX
     */
    public function services()
    {
        return response()->json([
            'teller' => Queue::TELLER_SERVICES,
            'cs' => Queue::CS_SERVICES,
            'admin' => Queue::ADMIN_SERVICES,
        ]);
    }

    /**
     * Get current queue status for a branch (AJAX)
     */
    public function branchStatus($branchId)
    {
        $pendingTeller = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->forTeller()
            ->count();

        $pendingCs = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->forCs()
            ->count();

        $pendingAdmin = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->forAdmin()
            ->count();

        return response()->json([
            'pending_teller' => $pendingTeller,
            'pending_cs' => $pendingCs,
            'pending_admin' => $pendingAdmin,
        ]);
    }

    /**
     * Get current queue status for kiosk real-time updates (via query param)
     */
    public function status(Request $request)
    {
        $branchId = $request->query('branch');
        
        if (!$branchId) {
            return response()->json(['error' => 'Branch ID required'], 400);
        }

        return $this->branchStatus($branchId);
    }
}

