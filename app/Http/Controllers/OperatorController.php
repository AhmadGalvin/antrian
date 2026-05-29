<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OperatorController extends Controller
{
    /**
     * Dashboard for Teller/Admin/CS
     */
    public function dashboard()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;
        $serviceType = $user->getServiceType();

        // Get today's queues for this branch and service type
        $pendingQueues = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->when($serviceType, fn($q) => $q->where('service_type', $serviceType))
            ->orderBy('created_at', 'asc')
            ->get();

        // Current queue being served by this user
        $currentQueue = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        // Stats for today
        $stats = [
            'pending' => $pendingQueues->count(),
            'served_by_me' => Queue::forBranch($branchId)
                ->today()
                ->where('served_by', $user->id)
                ->where('status', 'finished')
                ->count(),
            'skipped_by_me' => Queue::forBranch($branchId)
                ->today()
                ->where('served_by', $user->id)
                ->where('status', 'skipped')
                ->count(),
            'total_today' => Queue::forBranch($branchId)
                ->today()
                ->when($serviceType, fn($q) => $q->where('service_type', $serviceType))
                ->count(),
        ];

        // Recent called queues (all today, filtered by service type, served by this user)
        $recentQueues = Queue::forBranch($branchId)
            ->today()
            ->whereIn('status', ['finished', 'skipped'])
            ->where('served_by', $user->id)
            ->orderBy('finished_at', 'desc')
            ->get();

        return view('operator.dashboard', compact(
            'user',
            'pendingQueues',
            'currentQueue',
            'stats',
            'recentQueues'
        ));
    }

    /**
     * Call next queue
     */
    public function callNext(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->branch_id;
        $serviceType = $user->getServiceType();

        // Check if user already has an in-process queue
        $currentQueue = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        if ($currentQueue) {
            return back()->with('error', 'Anda masih memiliki antrian yang sedang dilayani. Selesaikan terlebih dahulu.');
        }

        // Get next pending queue (FIFO)
        $nextQueue = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->when($serviceType, fn($q) => $q->where('service_type', $serviceType))
            ->orderBy('created_at', 'asc')
            ->first();

        if (!$nextQueue) {
            return back()->with('info', 'Tidak ada antrian yang menunggu.');
        }

        // Update queue status
        $nextQueue->update([
            'status' => 'in_process',
            'counter_number' => $user->counter_number,
            'served_by' => $user->id,
            'called_at' => Carbon::now(),
        ]);

        // TODO: Broadcast event for display
        // event(new \App\Events\NewQueueCalled($nextQueue));

        return back()->with('success', "Memanggil antrian {$nextQueue->queue_number}");
    }

    /**
     * Finish current queue
     */
    public function finish(Request $request)
    {
        $user = auth()->user();

        $currentQueue = Queue::forBranch($user->branch_id)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        if (!$currentQueue) {
            return back()->with('error', 'Tidak ada antrian yang sedang dilayani.');
        }

        $currentQueue->update([
            'status' => 'finished',
            'finished_at' => Carbon::now(),
        ]);

        return back()->with('success', "Antrian {$currentQueue->queue_number} selesai dilayani.");
    }

    /**
     * Skip current queue (customer not present)
     */
    public function skip(Request $request)
    {
        $user = auth()->user();

        $currentQueue = Queue::forBranch($user->branch_id)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        if (!$currentQueue) {
            return back()->with('error', 'Tidak ada antrian yang sedang dilayani.');
        }

        $currentQueue->update([
            'status' => 'skipped',
            'finished_at' => Carbon::now(),
        ]);

        return back()->with('warning', "Antrian {$currentQueue->queue_number} dilewati.");
    }

    /**
     * Recall current queue
     */
    public function recall(Request $request)
    {
        $user = auth()->user();

        $currentQueue = Queue::forBranch($user->branch_id)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        if (!$currentQueue) {
            return back()->with('error', 'Tidak ada antrian yang sedang dilayani.');
        }

        // Update called_at to trigger real-time recall on the display screen
        $currentQueue->update([
            'called_at' => Carbon::now(),
            'recall_count' => ($currentQueue->recall_count ?? 0) + 1,
        ]);

        // TODO: Broadcast event for display
        // event(new \App\Events\NewQueueCalled($currentQueue));

        return back()->with('success', "Memanggil ulang antrian {$currentQueue->queue_number}");
    }

    /**
     * Get current status (for AJAX polling)
     */
    public function status()
    {
        $user = auth()->user();
        $branchId = $user->branch_id;
        $serviceType = $user->getServiceType();

        // Pending queues with formatted data
        $pendingQueues = Queue::forBranch($branchId)
            ->today()
            ->pending()
            ->when($serviceType, fn($q) => $q->where('service_type', $serviceType))
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'queue_number' => $q->queue_number,
                'service_label' => $q->service_label,
                'created_at_formatted' => $q->created_at->format('H:i'),
            ]);

        // Recent queues (finished/skipped, served by this user today)
        $recentQueues = Queue::forBranch($branchId)
            ->today()
            ->whereIn('status', ['finished', 'skipped'])
            ->where('served_by', $user->id)
            ->orderBy('finished_at', 'desc')
            ->get()
            ->map(fn($q) => [
                'id' => $q->id,
                'queue_number' => $q->queue_number,
                'service_label' => $q->service_label,
                'status' => $q->status,
                'finished_at_formatted' => $q->finished_at?->format('H:i:s'),
            ]);

        // Stats
        $servedByMe = Queue::forBranch($branchId)
            ->today()
            ->where('served_by', $user->id)
            ->where('status', 'finished')
            ->count();

        $totalToday = Queue::forBranch($branchId)
            ->today()
            ->when($serviceType, fn($q) => $q->where('service_type', $serviceType))
            ->count();

        $currentQueue = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->where('served_by', $user->id)
            ->first();

        $skippedByMe = Queue::forBranch($branchId)
            ->today()
            ->where('served_by', $user->id)
            ->where('status', 'skipped')
            ->count();

        return response()->json([
            'pending_count' => $pendingQueues->count(),
            'pending_queues' => $pendingQueues,
            'recent_queues' => $recentQueues,
            'served_by_me' => $servedByMe,
            'skipped_by_me' => $skippedByMe,
            'total_today' => $totalToday,
            'current_queue' => $currentQueue ? [
                'id' => $currentQueue->id,
                'queue_number' => $currentQueue->queue_number,
                'service_label' => $currentQueue->service_label,
                'called_at' => $currentQueue->called_at->format('H:i:s'),
            ] : null,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }
}

