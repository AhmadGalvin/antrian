<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchMedia;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuperadminController extends Controller
{
    /**
     * Dashboard overview
     */
    public function dashboard()
    {
        $stats = [
            'total_branches' => Branch::count(),
            'total_users' => User::count(),
            'today_queues' => Queue::today()->count(),
            'pending_queues' => Queue::today()->pending()->count(),
            'served_queues' => Queue::today()->where('status', 'finished')->count(),
        ];

        $branches = Branch::withCount([
            'queues as today_queues_count' => fn($q) => $q->whereDate('created_at', today()),
            'queues as pending_queues_count' => fn($q) => $q->whereDate('created_at', today())->where('status', 'pending'),
        ])->get();

        return view('superadmin.dashboard', compact('stats', 'branches'));
    }

    /**
     * List all branches
     */
    public function branches()
    {
        $branches = Branch::withCount('users')->get();
        return view('superadmin.branches', compact('branches'));
    }

    /**
     * Store new branch
     */
    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:branches',
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
        ]);

        $validated['has_admin'] = $request->boolean('has_admin', true);

        Branch::create($validated);

        return redirect()->route('superadmin.branches')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    /**
     * Update branch
     */
    public function updateBranch(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('branches')->ignore($branch->id)],
            'name' => 'required|string|max:100',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['has_admin'] = $request->boolean('has_admin');

        $branch->update($validated);

        return redirect()->route('superadmin.branches')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    /**
     * Delete branch
     */
    public function destroyBranch(Branch $branch)
    {
        $branch->delete();

        return redirect()->route('superadmin.branches')
            ->with('success', 'Cabang berhasil dihapus.');
    }

    /**
     * List all users
     */
    public function users()
    {
        $users = User::with('branch')->get();
        $branches = Branch::active()->get();
        return view('superadmin.users', compact('users', 'branches'));
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,admin,teller,cs,kiosk',
            'branch_id' => 'nullable|exists:branches,id',
            'counter_number' => 'nullable|integer|min:1|max:10',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('superadmin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,admin,teller,cs,kiosk',
            'branch_id' => 'nullable|exists:branches,id',
            'counter_number' => 'nullable|integer|min:1|max:10',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('superadmin.users')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Delete user
     */
    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('superadmin.users')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * View reports with date range filter
     */
    public function reports(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : today();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : today();
        $branchId = $request->branch_id;
        $serviceType = $request->service_type;

        $query = Queue::with('branch', 'servedBy')
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $queues = $query->orderBy('created_at', 'desc')->get();
        $branches = Branch::active()->get();

        // Statistics
        $stats = [
            'total' => $queues->count(),
            'finished' => $queues->where('status', 'finished')->count(),
            'skipped' => $queues->where('status', 'skipped')->count(),
            'pending' => $queues->where('status', 'pending')->count(),
            'in_process' => $queues->where('status', 'in_process')->count(),
        ];

        // Chart data - queues by date
        $chartByDate = $queues->groupBy(fn($q) => $q->created_at->format('Y-m-d'))
            ->map(fn($group) => $group->count())
            ->sortKeys();

        // Chart data - queues by service type
        $chartByService = $queues->groupBy('service_type')
            ->map(fn($group) => $group->count());

        // Chart data - queues by status
        $chartByStatus = $queues->groupBy('status')
            ->map(fn($group) => $group->count());

        // Chart data - queues by branch
        $chartByBranch = $queues->groupBy(fn($q) => $q->branch->name ?? 'Unknown')
            ->map(fn($group) => $group->count());

        // Average service time (for finished queues)
        $finishedQueues = $queues->where('status', 'finished')->filter(fn($q) => $q->called_at && $q->finished_at);
        $avgServiceTime = $finishedQueues->count() > 0 
            ? $finishedQueues->avg(fn($q) => \Carbon\Carbon::parse($q->finished_at)->diffInMinutes(\Carbon\Carbon::parse($q->called_at)))
            : 0;

        return view('superadmin.reports', compact(
            'queues', 'branches', 'startDate', 'endDate', 'branchId', 'serviceType',
            'stats', 'chartByDate', 'chartByService', 'chartByStatus', 'chartByBranch', 'avgServiceTime'
        ));
    }

    /**
     * Export reports to CSV
     */
    public function exportReports(Request $request)
    {
        $startDate = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : today();
        $endDate = $request->end_date ? \Carbon\Carbon::parse($request->end_date) : today();
        $branchId = $request->branch_id;
        $serviceType = $request->service_type;

        $query = Queue::with('branch', 'servedBy')
            ->whereBetween('created_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($serviceType) {
            $query->where('service_type', $serviceType);
        }

        $queues = $query->orderBy('created_at', 'asc')->get();

        // Generate CSV
        $filename = 'laporan_antrian_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($queues) {
            $file = fopen('php://output', 'w');
            
            // BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Use semicolon as delimiter for Excel compatibility (Indonesian regional settings)
            $delimiter = ';';
            
            // Header row
            fputcsv($file, [
                'No. Antrian',
                'Cabang',
                'Layanan',
                'Jenis Transaksi',
                'Loket',
                'Status',
                'Dilayani Oleh',
                'Waktu Dibuat',
                'Waktu Dipanggil',
                'Waktu Selesai',
                'Durasi (menit)',
            ], $delimiter);

            // Data rows
            foreach ($queues as $queue) {
                $duration = null;
                if ($queue->called_at && $queue->finished_at) {
                    $duration = \Carbon\Carbon::parse($queue->finished_at)
                        ->diffInMinutes(\Carbon\Carbon::parse($queue->called_at));
                }

                fputcsv($file, [
                    $queue->queue_number,
                    $queue->branch->name ?? '-',
                    $queue->service_label,
                    $queue->customer_note,
                    $queue->counter_number ?? '-',
                    ucfirst($queue->status),
                    $queue->servedBy->name ?? '-',
                    $queue->created_at->format('Y-m-d H:i:s'),
                    $queue->called_at ? \Carbon\Carbon::parse($queue->called_at)->format('Y-m-d H:i:s') : '-',
                    $queue->finished_at ? \Carbon\Carbon::parse($queue->finished_at)->format('Y-m-d H:i:s') : '-',
                    $duration ?? '-',
                ], $delimiter);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Media management page
     */
    public function media(Request $request)
    {
        $branchId = $request->branch_id;
        $branches = Branch::active()->get();
        
        $media = BranchMedia::with('branch')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('branch_id')
            ->orderBy('display_order')
            ->get();

        return view('superadmin.media', compact('media', 'branches', 'branchId'));
    }

    /**
     * Store new media
     */
    public function storeMedia(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'type' => 'required|in:image,video',
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:51200', // 50MB max
            'title' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:1|max:300',
        ]);

        // Store the file
        $path = $request->file('file')->store('media/branch-' . $validated['branch_id'], 'public');

        BranchMedia::create([
            'branch_id' => $validated['branch_id'],
            'type' => $validated['type'],
            'file_path' => $path,
            'title' => $validated['title'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'duration_seconds' => $validated['duration_seconds'] ?? 10,
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.media', ['branch_id' => $validated['branch_id']])
            ->with('success', 'Media berhasil ditambahkan.');
    }

    /**
     * Update media
     */
    public function updateMedia(Request $request, BranchMedia $media)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:100',
            'display_order' => 'nullable|integer|min:0',
            'duration_seconds' => 'nullable|integer|min:1|max:300',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $media->update($validated);

        return redirect()->route('superadmin.media', ['branch_id' => $media->branch_id])
            ->with('success', 'Media berhasil diperbarui.');
    }

    /**
     * Delete media
     */
    public function destroyMedia(BranchMedia $media)
    {
        $branchId = $media->branch_id;
        
        // Delete the file
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return redirect()->route('superadmin.media', ['branch_id' => $branchId])
            ->with('success', 'Media berhasil dihapus.');
    }
}
