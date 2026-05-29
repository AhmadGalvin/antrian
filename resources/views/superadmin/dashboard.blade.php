<x-app-layout>
    <div class="px-6 py-8 max-w-[1920px] mx-auto space-y-6">

        <!-- Page Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Dashboard Superadmin</h1>
                <p class="text-sm text-gray-400 mt-0.5">Ringkasan sistem antrian BPR BKK</p>
            </div>
            <div class="text-right text-sm text-gray-400 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ now()->format('l, d F Y') }}
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2 animate-fade-in">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Total Cabang -->
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-blue-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Total Cabang</p>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-white">{{ $stats['total_branches'] }}</span>
                </div>
                <a href="{{ route('superadmin.branches') }}" class="mt-3 text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                    Lihat semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Total User -->
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-green-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Total User</p>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-white">{{ $stats['total_users'] }}</span>
                </div>
                <a href="{{ route('superadmin.users') }}" class="mt-3 text-xs text-green-400 hover:text-green-300 flex items-center gap-1">
                    Kelola user
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Antrian Hari Ini -->
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-purple-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Antrian Hari Ini</p>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-white">{{ $stats['today_queues'] }}</span>
                </div>
                <a href="{{ route('superadmin.reports') }}" class="mt-3 text-xs text-purple-400 hover:text-purple-300 flex items-center gap-1">
                    Lihat laporan
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Menunggu -->
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-yellow-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Antrian Menunggu</p>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-white">{{ $stats['pending_queues'] }}</span>
                </div>
                <p class="mt-3 text-xs text-gray-500">Saat ini aktif</p>
            </div>

            <!-- Selesai -->
            <div class="bg-card-dark border border-card-border rounded-xl p-5 relative overflow-hidden group hover:border-teal-500/30 transition-all stat-card">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-20 h-20 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400">Selesai Dilayani</p>
                <div class="mt-2">
                    <span class="text-3xl font-bold text-white">{{ $stats['served_queues'] }}</span>
                </div>
                <p class="mt-3 text-xs text-gray-500">Hari ini</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('superadmin.branches') }}" class="bg-card-dark border border-card-border hover:border-blue-500/40 rounded-xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/10 group">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl group-hover:bg-blue-500/20 transition-colors">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-white group-hover:text-blue-300 transition-colors">Kelola Cabang</div>
                        <div class="text-sm text-gray-400 mt-1">Tambah, edit, atau hapus cabang BPR</div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="{{ route('superadmin.users') }}" class="bg-card-dark border border-card-border hover:border-green-500/40 rounded-xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-green-500/10 group">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-green-500/10 border border-green-500/20 rounded-xl group-hover:bg-green-500/20 transition-colors">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-white group-hover:text-green-300 transition-colors">Kelola User</div>
                        <div class="text-sm text-gray-400 mt-1">Manajemen akun Teller, CS, Admin</div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>

            <a href="{{ route('superadmin.reports') }}" class="bg-card-dark border border-card-border hover:border-purple-500/40 rounded-xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-purple-500/10 group">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-purple-500/10 border border-purple-500/20 rounded-xl group-hover:bg-purple-500/20 transition-colors">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-white group-hover:text-purple-300 transition-colors">Laporan</div>
                        <div class="text-sm text-gray-400 mt-1">Lihat statistik dan laporan antrian</div>
                    </div>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-purple-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>

        <!-- Branch Status Table -->
        <div class="bg-card-dark border border-card-border rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-card-border flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white">Status Antrian Per Cabang</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Data real-time hari ini</p>
                </div>
                <span class="flex items-center gap-1.5 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    Live
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-background-dark">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Cabang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Total Antrian</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Menunggu</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-card-border">
                        @foreach($branches as $branch)
                        <tr class="hover:bg-background-dark/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-100">{{ $branch->name }}</div>
                                <div class="text-sm text-gray-500">{{ $branch->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-mono bg-card-border/50 text-gray-300 rounded">{{ $branch->code }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-500/15 text-blue-400">
                                    {{ $branch->today_queues_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $branch->pending_queues_count > 0 ? 'bg-yellow-500/15 text-yellow-400' : 'bg-green-500/15 text-green-400' }}">
                                    {{ $branch->pending_queues_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('display.show', $branch->id) }}" target="_blank"
                                        class="flex items-center gap-1.5 text-xs text-blue-400 hover:text-blue-300 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Display
                                    </a>
                                    <a href="{{ route('kiosk.index', ['branch' => $branch->id]) }}" target="_blank"
                                        class="flex items-center gap-1.5 text-xs text-green-400 hover:text-green-300 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Kiosk
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
