<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchMedia;
use App\Models\Queue;
use Illuminate\Http\Request;

class DisplayController extends Controller
{
    /**
     * Show display for a branch
     */
    public function show($branchId)
    {
        $branch = Branch::findOrFail($branchId);

        // Get current in-process queues ordered by counter_number (smallest first)
        $currentQueues = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->with('servedBy')
            ->orderBy('counter_number', 'asc')
            ->get();

        // Get last finished queue per counter (for display persistence until next call)
        $finishedQueues = Queue::forBranch($branchId)
            ->today()
            ->where('status', 'finished')
            ->orderBy('finished_at', 'desc')
            ->get()
            ->groupBy('counter_number')
            ->map(fn($queues) => $queues->first());

        // Merge: show in_process, and if counter has no in_process, show last finished
        $displayQueues = collect();
        $processedCounters = $currentQueues->pluck('counter_number')->toArray();
        
        // Add all in-process queues
        foreach ($currentQueues as $queue) {
            $displayQueues->push([
                'queue' => $queue,
                'status' => 'in_process'
            ]);
        }
        
        // Add finished queues for counters that don't have in-process
        foreach ($finishedQueues as $counterNumber => $queue) {
            if (!in_array($counterNumber, $processedCounters)) {
                $displayQueues->push([
                    'queue' => $queue,
                    'status' => 'finished'
                ]);
            }
        }
        
        // Sort by counter number
        $displayQueues = $displayQueues->sortBy(fn($item) => $item['queue']->counter_number)->values();

        // Get pending counts per service type
        $pendingCounts = [
            'teller' => Queue::forBranch($branchId)->today()->pending()->forTeller()->count(),
            'cs' => Queue::forBranch($branchId)->today()->pending()->forCs()->count(),
        ];

        // Only include admin count if branch has admin operators
        if ($branch->has_admin) {
            $pendingCounts['admin'] = Queue::forBranch($branchId)->today()->pending()->forAdmin()->count();
        }

        // Get active media for this branch
        $mediaItems = BranchMedia::where('branch_id', $branchId)
            ->active()
            ->ordered()
            ->get();

        // Format media items for the Blade/JS display to avoid Blade syntax compiler bugs
        $formattedMedia = $mediaItems->map(fn($m) => [
            'id' => $m->id,
            'type' => $m->type,
            'url' => asset('storage/' . $m->file_path),
            'title' => $m->title,
            'duration_seconds' => $m->duration_seconds,
        ]);

        return view('display.show', compact('branch', 'displayQueues', 'pendingCounts', 'mediaItems', 'formattedMedia'));
    }

    /**
     * Get display data for AJAX polling
     */
    public function data($branchId)
    {
        // Get current in-process queues ordered by counter_number
        $currentQueues = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->with('servedBy')
            ->orderBy('counter_number', 'asc')
            ->get();

        // Get last finished queue per counter
        $finishedQueues = Queue::forBranch($branchId)
            ->today()
            ->where('status', 'finished')
            ->orderBy('finished_at', 'desc')
            ->get()
            ->groupBy('counter_number')
            ->map(fn($queues) => $queues->first());

        // Merge display queues
        $displayQueues = collect();
        $processedCounters = $currentQueues->pluck('counter_number')->toArray();
        
        foreach ($currentQueues as $queue) {
            $displayQueues->push([
                'id' => $queue->id,
                'queue_number' => $queue->queue_number,
                'counter_number' => $queue->counter_number,
                'service_type' => $queue->service_type,
                'status' => 'in_process',
                'called_at' => $queue->called_at->format('H:i:s'),
            ]);
        }
        
        foreach ($finishedQueues as $counterNumber => $queue) {
            if (!in_array($counterNumber, $processedCounters)) {
                $displayQueues->push([
                    'id' => $queue->id,
                    'queue_number' => $queue->queue_number,
                    'counter_number' => $counterNumber,
                    'service_type' => $queue->service_type,
                    'status' => 'finished',
                    'called_at' => $queue->called_at ? $queue->called_at->format('H:i:s') : null,
                ]);
            }
        }
        
        $displayQueues = $displayQueues->sortBy('counter_number')->values();

        $branch = Branch::find($branchId);

        $pendingCounts = [
            'teller' => Queue::forBranch($branchId)->today()->pending()->forTeller()->count(),
            'cs' => Queue::forBranch($branchId)->today()->pending()->forCs()->count(),
        ];

        // Only include admin count if branch has admin operators
        if ($branch && $branch->has_admin) {
            $pendingCounts['admin'] = Queue::forBranch($branchId)->today()->pending()->forAdmin()->count();
        }

        // Get the latest called queue for announcement (speech queue)
        $latestCalled = Queue::forBranch($branchId)
            ->today()
            ->inProcess()
            ->orderBy('called_at', 'desc')
            ->first();

        // Get active media for this branch
        $mediaItems = BranchMedia::where('branch_id', $branchId)
            ->active()
            ->ordered()
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'type' => $m->type,
                'url' => asset('storage/' . $m->file_path),
                'title' => $m->title,
                'duration_seconds' => $m->duration_seconds,
            ]);

        return response()->json([
            'display_queues' => $displayQueues,
            'pending_counts' => $pendingCounts,
            'latest_called' => $latestCalled ? [
                'id' => $latestCalled->id,
                'queue_number' => $latestCalled->queue_number,
                'counter_number' => $latestCalled->counter_number,
                'service_type' => $latestCalled->service_type,
                'called_at' => $latestCalled->called_at->timestamp,
                'recall_count' => $latestCalled->recall_count ?? 0,
            ] : null,
            'media' => $mediaItems,
            'timestamp' => now()->timestamp,
        ]);
    }
}
