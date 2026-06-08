<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kiosk Antrian - {{ $branch->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        .step-container { transition: opacity 0.3s ease, transform 0.3s ease; }
        .step-hidden { opacity: 0; transform: translateX(30px); pointer-events: none; position: absolute; }
        .step-visible { opacity: 1; transform: translateX(0); }
        .service-btn:active { transform: scale(0.97); }
    </style>
</head>
<body class="bg-background-dark min-h-screen font-sans select-none">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="glass-effect px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-10 h-10">
                <div>
                    <div class="font-bold text-white text-lg leading-tight">PT. BPR BKK WONOGIRI (Perseroda)</div>
                    <div class="text-xs text-gray-400 leading-tight">Cabang {{ $branch->name }} &bull; Sistem Antrian Digital</div>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-lg transition-colors flex items-center gap-1.5 opacity-40 hover:opacity-100" title="Keluar dari Kiosk">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </a>
                <div class="text-right">
                    <div class="text-2xl font-bold text-white tabular-nums" id="clock">--:--</div>
                    <div class="text-xs text-gray-400">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col items-center justify-center p-6 relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/8 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full {{ $hasAdmin ? 'max-w-4xl' : 'max-w-2xl' }}">
                <!-- STEP 1: Pilih Kategori Layanan -->
                <div id="step-category" class="step-container step-visible">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white">Silakan Pilih Layanan</h1>
                        <p class="text-gray-400 mt-2">Sentuh tombol layanan yang Anda butuhkan</p>
                    </div>

                    <div class="grid grid-cols-1 {{ $hasAdmin ? 'sm:grid-cols-3' : 'sm:grid-cols-2' }} gap-5">
                        <!-- Teller -->
                        <button type="button" onclick="showServices('teller')"
                            class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/15 focus:outline-none focus:ring-2 focus:ring-primary">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                            <div class="relative z-10 w-16 h-16 mx-auto mb-4 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-2xl flex items-center justify-center transition-all">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="relative z-10">
                                <div class="text-lg font-bold text-white group-hover:text-primary transition-colors">Teller</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="teller-count">{{ $pendingTeller }}</span> antrian menunggu
                                </div>
                            </div>
                        </button>

                        <!-- Customer Service -->
                        <button type="button" onclick="showServices('cs')"
                            class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/15 focus:outline-none focus:ring-2 focus:ring-primary">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                            <div class="relative z-10 w-16 h-16 mx-auto mb-4 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-2xl flex items-center justify-center transition-all">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                            </div>
                            <div class="relative z-10">
                                <div class="text-lg font-bold text-white group-hover:text-primary transition-colors">Customer Service</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="cs-count">{{ $pendingCs }}</span> antrian menunggu
                                </div>
                            </div>
                        </button>

                        @if($hasAdmin)
                        <!-- Administrasi -->
                        <button type="button" onclick="showServices('admin')"
                            class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-6 text-center transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-primary/15 focus:outline-none focus:ring-2 focus:ring-primary">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                            <div class="relative z-10 w-16 h-16 mx-auto mb-4 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-2xl flex items-center justify-center transition-all">
                                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="relative z-10">
                                <div class="text-lg font-bold text-white group-hover:text-primary transition-colors">Administrasi</div>
                                <div class="text-sm text-gray-500 mt-1">
                                    <span class="admin-count">{{ $pendingAdmin }}</span> antrian menunggu
                                </div>
                            </div>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- STEP 2: Pilih Jenis Layanan (Sub-service) -->
                <div id="step-services" class="step-container step-hidden">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-white">Pilih Jenis Layanan</h1>
                        <p class="text-gray-400 mt-2">Layanan <span id="selected-category-label" class="text-primary font-semibold"></span></p>
                    </div>

                    <!-- Teller Services (dynamically includes redistributed admin services) -->
                    <div id="services-teller" class="hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($branchServices['teller'] as $key => $label)
                            <form action="{{ route('kiosk.store') }}" method="POST" class="ticket-form">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <input type="hidden" name="customer_note" value="{{ $key }}">
                                <button type="submit"
                                    class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-5 text-left transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-xl flex items-center justify-center transition-all shrink-0">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold text-white group-hover:text-primary transition-colors">{{ $label }}</div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            @endforeach
                        </div>
                    </div>

                    <!-- CS Services (dynamically includes redistributed admin services) -->
                    <div id="services-cs" class="hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($branchServices['cs'] as $key => $label)
                            <form action="{{ route('kiosk.store') }}" method="POST" class="ticket-form">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <input type="hidden" name="customer_note" value="{{ $key }}">
                                <button type="submit"
                                    class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-5 text-left transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-xl flex items-center justify-center transition-all shrink-0">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold text-white group-hover:text-primary transition-colors">{{ $label }}</div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            @endforeach
                        </div>
                    </div>

                    @if($hasAdmin)
                    <!-- Admin Services (only shown when branch has admin) -->
                    <div id="services-admin" class="hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($branchServices['admin'] as $key => $label)
                            <form action="{{ route('kiosk.store') }}" method="POST" class="ticket-form">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <input type="hidden" name="customer_note" value="{{ $key }}">
                                <button type="submit"
                                    class="service-btn w-full group relative overflow-hidden bg-card-dark border border-card-border hover:border-primary/60 rounded-2xl p-5 text-left transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/10 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>
                                    <div class="relative z-10 flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary/10 group-hover:bg-primary/20 border border-primary/20 group-hover:border-primary/50 rounded-xl flex items-center justify-center transition-all shrink-0">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold text-white group-hover:text-primary transition-colors">{{ $label }}</div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Back Button -->
                    <div class="mt-6 text-center">
                        <button type="button" onclick="showCategory()"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-card-dark border border-card-border text-gray-300 hover:text-white hover:border-primary/40 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="px-6 py-3 flex items-center justify-center">
            <p class="text-xs text-gray-600">&copy; {{ date('Y') }} BPR BKK &mdash; Sistem Antrian Digital</p>
        </footer>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="hidden fixed inset-0 bg-background-dark/90 backdrop-blur-sm z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-white font-medium">Mencetak Tiket...</p>
        </div>
    </div>

    <script>
        // Clock
        function updateClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'});
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Category labels
        const categoryLabels = {
            teller: 'Teller',
            cs: 'Customer Service',
            admin: 'Administrasi'
        };

        // Show sub-services for a category
        function showServices(category) {
            const stepCategory = document.getElementById('step-category');
            const stepServices = document.getElementById('step-services');

            // Hide all service groups
            document.getElementById('services-teller').classList.add('hidden');
            document.getElementById('services-cs').classList.add('hidden');
            const adminServices = document.getElementById('services-admin');
            if (adminServices) adminServices.classList.add('hidden');

            // Show selected service group
            const targetGroup = document.getElementById('services-' + category);
            if (targetGroup) targetGroup.classList.remove('hidden');

            // Update label
            document.getElementById('selected-category-label').textContent = categoryLabels[category];

            // Animate transition
            stepCategory.classList.remove('step-visible');
            stepCategory.classList.add('step-hidden');

            // Small delay for smooth animation
            setTimeout(() => {
                stepServices.classList.remove('step-hidden');
                stepServices.classList.add('step-visible');
            }, 50);
        }

        // Go back to category selection
        function showCategory() {
            const stepCategory = document.getElementById('step-category');
            const stepServices = document.getElementById('step-services');

            stepServices.classList.remove('step-visible');
            stepServices.classList.add('step-hidden');

            setTimeout(() => {
                stepCategory.classList.remove('step-hidden');
                stepCategory.classList.add('step-visible');
            }, 50);
        }

        // Show loading on form submit
        document.querySelectorAll('.ticket-form').forEach(form => {
            form.addEventListener('submit', () => {
                document.getElementById('loading-overlay').classList.remove('hidden');
            });
        });

        // Poll pending counts using existing status route
        async function updatePendingCounts() {
            try {
                const branchId = {{ $branch->id }};
                const response = await fetch(`/kiosk/status/${branchId}`);
                if (!response.ok) return;
                const data = await response.json();
                if (data.pending_teller !== undefined) {
                    document.querySelectorAll('.teller-count').forEach(el => el.textContent = data.pending_teller);
                    document.querySelectorAll('.cs-count').forEach(el => el.textContent = data.pending_cs);
                    document.querySelectorAll('.admin-count').forEach(el => el.textContent = data.pending_admin);
                }
            } catch(e) {}
        }
        setInterval(updatePendingCounts, 10000);
    </script>
</body>
</html>
