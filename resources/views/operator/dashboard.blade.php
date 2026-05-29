<x-app-layout>
    {{-- Flash Messages --}}
    <div id="flash-messages" class="fixed top-20 right-4 z-50 space-y-2 w-80">
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2 flash-message shadow-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl flex items-center gap-2 flash-message shadow-lg">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-xl flex items-center gap-2 flash-message shadow-lg">{{ session('warning') }}</div>
        @endif
        @if(session('info'))
            <div class="bg-blue-500/10 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-xl flex items-center gap-2 flash-message shadow-lg">{{ session('info') }}</div>
        @endif
    </div>

    {{-- Main Content Grid --}}
    <main class="flex-1 flex flex-col lg:flex-row p-4 lg:p-6 gap-4 lg:gap-6 overflow-hidden" style="height: calc(100vh - 4rem);">

        {{-- LEFT PANEL: Current Ticket (2/3 width) --}}
        <section class="flex-[2] flex flex-col gap-6 bg-card-dark rounded-xl border border-card-border shadow-sm p-8 relative overflow-hidden">
            {{-- Background Decoration Blob --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            {{-- Top Status Bar --}}
            <div class="flex justify-between items-start z-10">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-400 uppercase tracking-wider">Sedang Dilayani</span>
                    <h3 class="text-xl font-semibold mt-1 text-white" id="current-service-label">
                        {{ $currentQueue?->service_label ?? '—' }}
                    </h3>
                </div>
                <div class="flex items-center gap-2 bg-background-dark px-3 py-1.5 rounded-lg border border-card-border">
                    <svg class="w-4 h-4 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="font-mono font-medium text-lg text-white tabular-nums" id="digital-clock">--:--:--</span>
                </div>
            </div>

            {{-- Central Ticket Display --}}
            <div class="flex-1 flex flex-col items-center justify-center text-center z-10 py-8 lg:py-0">
                @if($currentQueue)
                    <h2 class="text-8xl lg:text-9xl font-bold tracking-tighter text-white tabular-nums" id="current-queue-number">{{ $currentQueue->queue_number }}</h2>
                @else
                    <div class="text-center">
                        <h2 class="text-8xl lg:text-9xl font-bold tracking-tighter text-white/10 tabular-nums">—</h2>
                        <p class="text-gray-500 text-sm mt-4">Belum ada antrian yang sedang dilayani</p>
                    </div>
                @endif
            </div>

            {{-- Action Controls --}}
            <div class="mt-auto z-10 pt-6 border-t border-card-border">
                @if($currentQueue)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Lewati --}}
                        <form action="{{ route('operator.skip') }}" method="POST" onsubmit="return confirm('Lewati antrian ini?')">
                            @csrf
                            <button type="submit" class="group w-full flex flex-col items-center justify-center p-4 rounded-xl border-2 border-red-500/20 hover:border-red-500 hover:bg-red-500/10 text-red-400 transition-all duration-200 h-24">
                                <svg class="w-7 h-7 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <span class="font-semibold">Lewati</span>
                            </button>
                        </form>
                        {{-- Selesai --}}
                        <form action="{{ route('operator.finish') }}" method="POST">
                            @csrf
                            <button type="submit" class="group w-full flex flex-col items-center justify-center p-4 rounded-xl border-2 border-green-500/20 hover:border-green-500 hover:bg-green-500/10 text-green-400 transition-all duration-200 h-24">
                                <svg class="w-7 h-7 mb-1 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="font-semibold">Selesai</span>
                            </button>
                        </form>
                        {{-- Panggil Ulang --}}
                        <form action="{{ route('operator.recall') }}" method="POST">
                            @csrf
                            <button type="submit" class="group w-full flex flex-col items-center justify-center p-4 rounded-xl bg-primary hover:bg-primary/80 text-white shadow-lg shadow-primary/25 transition-all duration-200 h-24 active:scale-95">
                                <div class="flex items-center gap-2">
                                    <svg class="w-8 h-8 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <span class="font-bold text-lg mt-1">Panggil Ulang</span>
                            </button>
                        </form>
                    </div>
                @else
                    <form action="{{ route('operator.call-next') }}" method="POST">
                        @csrf
                        <button type="submit" class="group w-full flex flex-col items-center justify-center p-4 rounded-xl bg-primary hover:bg-primary/80 text-white shadow-lg shadow-primary/25 transition-all duration-200 h-24 active:scale-95">
                            <div class="flex items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </div>
                            <span class="font-bold text-lg mt-1">Panggil Antrian Berikutnya</span>
                        </button>
                    </form>
                @endif
            </div>
        </section>

        {{-- RIGHT PANEL: Sidebar (1/3 width) --}}
        <aside class="flex-1 flex flex-col gap-4 lg:gap-6 min-w-0">

            {{-- Daily Stats Widget --}}
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-card-dark p-4 rounded-xl border border-card-border text-center">
                    <div class="text-xs text-gray-400 uppercase font-semibold mb-1">Dilayani</div>
                    <div class="text-2xl font-bold text-white tabular-nums" id="served-by-me-count">{{ $stats['served_by_me'] }}</div>
                </div>
                <div class="bg-card-dark p-4 rounded-xl border border-card-border text-center">
                    <div class="text-xs text-gray-400 uppercase font-semibold mb-1">Menunggu</div>
                    <div class="text-2xl font-bold text-primary tabular-nums" id="pending-count">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-card-dark p-4 rounded-xl border border-card-border text-center">
                    <div class="text-xs text-gray-400 uppercase font-semibold mb-1">Dilewati</div>
                    <div class="text-2xl font-bold text-red-400 tabular-nums" id="skipped-by-me-count">{{ $stats['skipped_by_me'] }}</div>
                </div>
            </div>

            {{-- Waiting Queue List --}}
            <div class="flex-1 bg-card-dark rounded-xl border border-card-border flex flex-col overflow-hidden shadow-sm min-h-0">
                <div class="p-4 border-b border-card-border flex justify-between items-center bg-white/5">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        Antrian Menunggu
                    </h3>
                    <span class="text-xs text-primary font-medium tabular-nums" id="pending-badge">{{ $stats['pending'] }}</span>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-2" id="pending-queue-list">
                    @forelse($pendingQueues as $index => $queue)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $index === 0 ? 'bg-primary/5 border border-primary/20' : 'hover:bg-white/5 border border-transparent hover:border-card-border' }} cursor-default transition-colors group queue-item" data-queue-id="{{ $queue->id }}">
                            <div class="flex items-center gap-3">
                                <div class="h-10 rounded-lg {{ $index === 0 ? 'bg-background-dark border border-primary/10 text-primary' : 'bg-background-dark text-gray-300' }} flex items-center justify-center font-bold whitespace-nowrap px-2 min-w-[3rem] tabular-nums text-sm">
                                    {{ $queue->queue_number }}
                                </div>
                                <div>
                                    <p class="text-sm {{ $index === 0 ? 'font-semibold text-white' : 'font-medium text-white' }}">{{ $queue->service_label }}</p>
                                    <p class="text-xs text-gray-500 tabular-nums">{{ $queue->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                            @if($index === 0)
                            <svg class="w-4 h-4 text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500" id="no-pending-queues">
                            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm">Tidak ada antrian menunggu</p>
                        </div>
                    @endforelse
                </div>

                {{-- Queue Footer --}}
                <div class="p-3 border-t border-card-border bg-white/5 text-center" id="queue-footer">
                    <p class="text-xs text-gray-500">
                        @if($pendingQueues->count() > 0)
                            Menampilkan {{ $pendingQueues->count() }} dari {{ $stats['pending'] }} antrian menunggu
                        @else
                            Tidak ada antrian menunggu
                        @endif
                    </p>
                </div>
            </div>

            {{-- History Button --}}
            <button onclick="document.getElementById('history-modal').classList.toggle('hidden')"
                class="flex items-center justify-between p-4 bg-card-dark border border-card-border rounded-xl hover:bg-card-border/50 transition-colors group">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-500/20 rounded-lg text-indigo-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l3-3m0 0l3 3M6 14v7"/></svg>
                    </div>
                    <div class="text-left">
                        <h4 class="text-sm font-semibold text-white">Riwayat</h4>
                        <p class="text-xs text-gray-400">Lihat Riwayat Antrian Terakhir Dilayani</p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </aside>
    </main>

    {{-- Footer Status Bar --}}
    <footer class="h-8 flex-none bg-card-dark border-t border-card-border px-6 flex items-center justify-between text-xs text-gray-500">
        <div class="flex items-center gap-4">
            <span>{{ $user->branch?->name ?? '—' }}</span>
            @if($user->counter_number)
                <span class="text-primary font-medium">Loket {{ $user->counter_number }}</span>
            @endif
        </div>
        <div>
            
        </div>
    </footer>

    {{-- History Modal --}}
    <div id="history-modal" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" onclick="if(event.target===this) this.classList.add('hidden')">
        <div class="bg-card-dark border border-card-border rounded-xl shadow-2xl w-full max-w-2xl flex flex-col" style="max-height: 80vh;">
            <div class="px-5 py-4 border-b border-card-border flex items-center justify-between shrink-0">
                <div>
                    <h3 class="text-base font-semibold text-white">Riwayat Antrian Hari Ini</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $recentQueues->count() }} antrian dilayani oleh Anda hari ini</p>
                </div>
                <button onclick="document.getElementById('history-modal').classList.add('hidden')" class="text-gray-500 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="overflow-y-auto custom-scrollbar flex-1">
                <table class="min-w-full text-sm">
                    <thead class="bg-background-dark text-xs text-gray-500 uppercase sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-2.5 text-left font-medium">No. Antrian</th>
                            <th class="px-4 py-2.5 text-left font-medium">Layanan</th>
                            <th class="px-4 py-2.5 text-center font-medium">Status</th>
                            <th class="px-4 py-2.5 text-left font-medium">Selesai Pada</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border" id="recent-queues-body">
                        @forelse($recentQueues as $queue)
                        <tr class="hover:bg-background-dark/30 transition-colors">
                            <td class="px-4 py-3 font-bold text-gray-100 tabular-nums">{{ $queue->queue_number }}</td>
                            <td class="px-4 py-3 text-gray-400">{{ $queue->service_label }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $queue->status === 'finished' ? 'bg-green-500/15 text-green-400' : 'bg-red-500/15 text-red-400' }}">
                                    {{ $queue->status === 'finished' ? 'Selesai' : 'Dilewati' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs tabular-nums">{{ $queue->finished_at?->format('H:i:s') ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-gray-500">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Belum ada antrian yang dilayani hari ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- New Queue Notification --}}
    <div id="new-queue-notification" class="hidden fixed bottom-12 right-4 z-50 bg-green-500/15 border border-green-500/30 text-green-400 p-4 rounded-xl shadow-xl max-w-xs">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <div>
                <div class="font-bold text-sm">Antrian Baru!</div>
                <div class="text-xs" id="new-queue-info"></div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #111621; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #2b3548; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #3b475f; }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .animate-pulse-soft { animation: pulse-soft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>

    <script>
        // Clock
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('digital-clock').textContent = `${h}:${m}:${s}`;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Auto-dismiss flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 4000);

        let lastPendingCount = {{ $stats['pending'] }};

        async function pollUpdates() {
            try {
                const response = await fetch('{{ route("operator.status") }}');
                if (!response.ok) return;
                const data = await response.json();

                // Update stats
                document.getElementById('pending-count').textContent = data.pending_count;
                document.getElementById('pending-badge').textContent = data.pending_count;
                document.getElementById('served-by-me-count').textContent = data.served_by_me;
                document.getElementById('skipped-by-me-count').textContent = data.skipped_by_me ?? 0;
                

                // Notification on new queue
                if (data.pending_count > lastPendingCount) {
                    showNewQueueNotification(data.pending_queues[0]);
                }
                lastPendingCount = data.pending_count;

                // Update pending list
                updatePendingList(data.pending_queues);

                // Update recent queues only if modal is open
                const modal = document.getElementById('history-modal');
                if (modal && !modal.classList.contains('hidden')) {
                    updateRecentQueues(data.recent_queues);
                }

            } catch (error) {
                console.error('Poll error:', error);
            }
        }

        function updatePendingList(queues) {
            const container = document.getElementById('pending-queue-list');
            const footer = document.getElementById('queue-footer');
            if (queues.length === 0) {
                container.innerHTML = '<div class="flex flex-col items-center justify-center py-12 text-gray-500"><svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><p class="text-sm">Tidak ada antrian menunggu</p></div>';
                footer.innerHTML = '<p class="text-xs text-gray-500">Tidak ada antrian menunggu</p>';
                return;
            }
            container.innerHTML = queues.map((q, i) => `
                <div class="flex items-center justify-between p-3 rounded-lg ${i === 0 ? 'bg-primary/5 border border-primary/20' : 'hover:bg-white/5 border border-transparent hover:border-card-border'} cursor-default transition-colors group queue-item" data-queue-id="${q.id}">
                    <div class="flex items-center gap-3">
                        <div class="h-10 rounded-lg bg-background-dark ${i === 0 ? 'border border-primary/10 text-primary' : 'text-gray-300'} flex items-center justify-center font-bold whitespace-nowrap px-2 min-w-[3rem] tabular-nums text-sm">
                            ${q.queue_number}
                        </div>
                        <div>
                            <p class="text-sm ${i === 0 ? 'font-semibold text-white' : 'font-medium text-white'}">${q.service_label}</p>
                            <p class="text-xs text-gray-500 tabular-nums">${q.created_at_formatted}</p>
                        </div>
                    </div>
                    ${i === 0 ? '<svg class="w-4 h-4 text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>' : ''}
                </div>
            `).join('');
            footer.innerHTML = `<p class="text-xs text-gray-500">Menampilkan ${queues.length} dari ${queues.length} antrian menunggu</p>`;
        }

        function updateRecentQueues(queues) {
            const tbody = document.getElementById('recent-queues-body');
            if (!tbody) return;
            if (queues.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada antrian yang dilayani hari ini</td></tr>';
                return;
            }
            tbody.innerHTML = queues.map(q => `
                <tr class="hover:bg-background-dark/30 transition-colors">
                    <td class="px-4 py-3 font-bold text-gray-100 tabular-nums">${q.queue_number}</td>
                    <td class="px-4 py-3 text-gray-400">${q.service_label}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${q.status === 'finished' ? 'bg-green-500/15 text-green-400' : 'bg-red-500/15 text-red-400'}">
                            ${q.status === 'finished' ? 'Selesai' : 'Dilewati'}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs tabular-nums">${q.finished_at_formatted ?? '-'}</td>
                </tr>
            `).join('');
        }

        function showNewQueueNotification(queue) {
            const notification = document.getElementById('new-queue-notification');
            document.getElementById('new-queue-info').textContent = queue ? `${queue.queue_number} - ${queue.service_label}` : 'Ada antrian baru!';
            notification.classList.remove('hidden');
            try {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleQoAMI7T8nZaEggriML+dE8aCRWFveNyYCcMEHu49WpTNg4Ka7L8b1pADgVfp/5wW0wPAVWg/nFaVg8AU5//cFpYDwBSmP9vWlkPAFCS/29aWQ8AT5L/b1paEABPlv9vWl0QAE+Y/29aXxAAT5v/b1phEABOnf9vWmMQAE6g/29aZRAATqL/b1pnEABNpP9uWmgRAE2m/25aahEATaj/blpsEQBNqv9uWm0SAE2s/21abxIATa7/bVpwEgBNsP9tWnISAE2y/21acxMATbT/bFp1EwBNtv9sWnYTAE24/2xaeBMATbr/bFp5EwBNu/9sWnsUAE29/2tacxQATb7/a1p8FABNwP9rWn0UAE3B/2tafhQATcP/alp/FABNxP9qWoAVAE3G/2pahRUATcf/alqGFQBNyP9qWocVAE3J/2lahxUATcr/aVqIFgBNy/9pWokWAE3M/2laiRYATc3/aVqKFgBNzv9pWosXAE3P/2hajBcATdD/aFqMFwBN0f9oWo0XAE3S/2hajRcATdP/aFqOGABN1P9oWo8YAE3V/2haj/8=');
                audio.volume = 0.5;
                audio.play();
            } catch(e) {}
            setTimeout(() => notification.classList.add('hidden'), 5000);
        }

        setInterval(pollUpdates, 3000);
    </script>
</x-app-layout>
