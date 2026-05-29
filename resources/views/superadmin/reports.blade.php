<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-primary/15 border border-primary/20 rounded-xl">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Laporan &amp; Analitik</h1>
                    <p class="text-xs text-gray-400 mt-0.5">Superadmin Dashboard</p>
                </div>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Last updated: Just now</span>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-card-dark border border-card-border rounded-xl p-5">
            <form method="GET" class="flex flex-col xl:flex-row gap-4 items-end xl:items-center justify-between">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 w-full xl:w-auto flex-grow">
                    <!-- Start Date -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wide">Tanggal Mulai</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                                class="w-full pl-9 pr-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        </div>
                    </div>
                    <!-- End Date -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wide">Tanggal Akhir</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                                class="w-full pl-9 pr-3 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                        </div>
                    </div>
                    <!-- Branch -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wide">Cabang</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            <select name="branch_id" class="w-full pl-9 pr-8 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none appearance-none">
                                <option value="">Semua Cabang</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $branchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <!-- Service -->
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-400 uppercase tracking-wide">Layanan</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                            <select name="service_type" class="w-full pl-9 pr-8 py-2 bg-background-dark border border-card-border text-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-transparent outline-none appearance-none">
                                <option value="">Semua Layanan</option>
                                <option value="teller" {{ $serviceType == 'teller' ? 'selected' : '' }}>Teller</option>
                                <option value="cs" {{ $serviceType == 'cs' ? 'selected' : '' }}>Customer Service</option>
                                <option value="admin" {{ $serviceType == 'admin' ? 'selected' : '' }}>Administrasi</option>
                            </select>
                            <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
                <!-- Action Buttons -->
                <div class="flex items-center gap-3 w-full xl:w-auto shrink-0">
                    <button type="submit"
                        class="flex-1 xl:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-primary hover:bg-primary-hover text-white font-medium text-sm rounded-lg shadow-lg shadow-primary/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('superadmin.reports.export', request()->query()) }}"
                        class="flex-1 xl:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-background-dark border border-card-border hover:bg-card-border/50 text-gray-300 text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export CSV
                    </a>
                </div>
            </form>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-blue-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Total Antrian</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total'] }}</h3>
                </div>
            </div>
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-green-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Selesai</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-white">{{ $stats['finished'] }}</h3>
                </div>
            </div>
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-orange-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Dilewati</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-white">{{ $stats['skipped'] }}</h3>
                </div>
            </div>
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-blue-400/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Menunggu</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-white">{{ $stats['pending'] }}</h3>
                </div>
            </div>
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-purple-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Rata-rata (menit)</p>
                <div class="flex items-baseline gap-2 mt-2">
                    <h3 class="text-3xl font-bold text-white">{{ number_format($avgServiceTime, 1) }}</h3>
                    <span class="text-sm text-gray-400">mnt</span>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Line Chart - Daily Queues (2-col) -->
            <div class="lg:col-span-2 xl:col-span-2 bg-card-dark border border-card-border rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white">Tren Antrian Harian</h3>
                </div>
                <div class="relative h-64 w-full">
                    <canvas id="chartByDate"></canvas>
                </div>
            </div>

            <!-- Doughnut Chart - Status Distribution -->
            <div class="lg:col-span-1 xl:col-span-1 bg-card-dark border border-card-border rounded-xl p-6 flex flex-col">
                <h3 class="text-lg font-semibold text-white mb-4">Status Antrian</h3>
                <div class="relative flex-grow flex items-center justify-center">
                    <div class="h-52 w-52 relative">
                        <canvas id="chartByStatus"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-bold text-white">{{ $stats['total'] }}</span>
                            <span class="text-xs text-gray-400">Total</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-xs text-gray-400">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span> Menunggu</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span> Proses</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-green-400"></span> Selesai</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span> Dilewati</div>
                </div>
            </div>

            <!-- Horizontal Bar - Branch Performance -->
            <div class="lg:col-span-3 xl:col-span-1 bg-card-dark border border-card-border rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Antrian per Cabang</h3>
                <div class="relative h-64 w-full">
                    <canvas id="chartByBranch"></canvas>
                </div>
            </div>

            <!-- Bar Chart - Service Volume (full-width) -->
            <div class="lg:col-span-3 xl:col-span-4 bg-card-dark border border-card-border rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Volume per Layanan</h3>
                <div class="relative h-48 w-full">
                    <canvas id="chartByService"></canvas>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-card-dark border border-card-border rounded-xl overflow-hidden flex flex-col" style="max-height: 520px;">
            <div class="px-6 py-4 border-b border-card-border flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-lg font-semibold text-white">Detail Data Antrian</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $queues->count() }} entri ditemukan</p>
                </div>
            </div>
            <div class="overflow-auto flex-grow">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-400 uppercase bg-background-dark sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-4 font-medium">No. Antrian</th>
                            <th class="px-6 py-4 font-medium">Cabang</th>
                            <th class="px-6 py-4 font-medium">Layanan</th>
                            <th class="px-6 py-4 text-center font-medium">Loket</th>
                            <th class="px-6 py-4 text-center font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Dilayani Oleh</th>
                            <th class="px-6 py-4 font-medium">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border">
                        @forelse($queues as $queue)
                        <tr class="hover:bg-background-dark/50 transition-colors">
                            <td class="px-6 py-3 font-bold text-white">{{ $queue->queue_number }}</td>
                            <td class="px-6 py-3 text-gray-300">{{ $queue->branch->name }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $queue->service_label }}</td>
                            <td class="px-6 py-3 text-center text-gray-400">{{ $queue->counter_number ?? '-' }}</td>
                            <td class="px-6 py-3 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($queue->status === 'pending') bg-yellow-500/15 text-yellow-400
                                    @elseif($queue->status === 'in_process') bg-blue-500/15 text-blue-400
                                    @elseif($queue->status === 'finished') bg-green-500/15 text-green-400
                                    @else bg-red-500/15 text-red-400 @endif">
                                    @if($queue->status === 'pending') Menunggu
                                    @elseif($queue->status === 'in_process') Diproses
                                    @elseif($queue->status === 'finished') Selesai
                                    @else Dilewati @endif
                                </span>
                            </td>
                            <td class="px-6 py-3 text-gray-400">{{ $queue->servedBy?->name ?? '-' }}</td>
                            <td class="px-6 py-3 text-gray-500 text-xs">{{ $queue->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Tidak ada data antrian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Chart.js Defaults - Dark Theme
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = '#2d3548';
        Chart.defaults.font.family = 'Inter';

        const chartByDateLabels = @json($chartByDate->keys());
        const chartByDateValues = @json($chartByDate->values());
        const chartByStatusLabels = @json($chartByStatus->keys());
        const chartByStatusValues = @json($chartByStatus->values());
        const chartByServiceLabels = @json($chartByService->keys());
        const chartByServiceValues = @json($chartByService->values());
        const chartByBranchLabels = @json($chartByBranch->keys());
        const chartByBranchValues = @json($chartByBranch->values());

        function getServiceLabel(type) {
            const labels = { 'teller': 'Teller', 'cs': 'Customer Service', 'admin': 'Administrasi' };
            return labels[type] || type;
        }
        function getStatusLabel(status) {
            const labels = { 'pending': 'Menunggu', 'in_process': 'Diproses', 'finished': 'Selesai', 'skipped': 'Dilewati' };
            return labels[status] || status;
        }

        // Line Chart - Daily Queues
        const ctxDate = document.getElementById('chartByDate').getContext('2d');
        const gradientDate = ctxDate.createLinearGradient(0, 0, 0, 280);
        gradientDate.addColorStop(0, 'rgba(23, 84, 207, 0.45)');
        gradientDate.addColorStop(1, 'rgba(23, 84, 207, 0.0)');
        new Chart(ctxDate, {
            type: 'line',
            data: {
                labels: chartByDateLabels,
                datasets: [{
                    label: 'Antrian',
                    data: chartByDateValues,
                    borderColor: '#1754cf',
                    backgroundColor: gradientDate,
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointBackgroundColor: '#1754cf',
                    pointBorderColor: '#1a2130',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [3, 3], color: '#2d3548' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Doughnut Chart - Status
        new Chart(document.getElementById('chartByStatus'), {
            type: 'doughnut',
            data: {
                labels: chartByStatusLabels.map(getStatusLabel),
                datasets: [{
                    data: chartByStatusValues,
                    backgroundColor: ['#facc15', '#60a5fa', '#22c55e', '#ef4444'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });

        // Bar Chart - Service Type
        new Chart(document.getElementById('chartByService'), {
            type: 'bar',
            data: {
                labels: chartByServiceLabels.map(getServiceLabel),
                datasets: [{
                    label: 'Antrian',
                    data: chartByServiceValues,
                    backgroundColor: ['#1754cf', '#3b82f6', '#60a5fa', '#93c5fd'],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [3, 3], color: '#2d3548' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Horizontal Bar Chart - Branch
        new Chart(document.getElementById('chartByBranch'), {
            type: 'bar',
            data: {
                labels: chartByBranchLabels,
                datasets: [{
                    label: 'Antrian',
                    data: chartByBranchValues,
                    backgroundColor: '#1754cf',
                    borderRadius: 4,
                    barThickness: 18
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { beginAtZero: true, grid: { borderDash: [3, 3], color: '#2d3548' } },
                    y: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>
