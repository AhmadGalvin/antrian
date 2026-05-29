<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Antrian - {{ $branch->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-background-dark min-h-screen font-sans overflow-hidden">
    <div class="h-screen flex flex-col">
        <!-- Header -->
        <header class="glass-effect px-6 py-3 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-primary/15 border border-primary/20 rounded-xl">
                    <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-9 h-9">
                </div>
                <div>
                    <div class="font-bold text-white text-xl leading-tight">BPR {{ $branch->name }}</div>
                    <div class="text-xs text-gray-400 leading-tight">Layar Antrian Digital</div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-black text-white tabular-nums" id="clock">--:--:--</div>
                <div class="text-sm text-gray-400">{{ now()->format('d M Y') }}</div>
            </div>
        </header>

        <!-- Main Grid -->
        <div class="flex-grow flex overflow-hidden">
            <!-- Left: Media / Promo Slideshow (3/5) -->
            <div class="w-3/5 relative bg-background-dark overflow-hidden">
                <div id="media-container" class="w-full h-full">
                    @if($mediaItems->isEmpty())
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-card-dark to-background-dark">
                            <div class="text-center">
                                <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-28 h-28 mx-auto mb-6 opacity-20">
                                <div class="text-3xl font-bold text-white/20">BPR BKK</div>
                                <div class="text-gray-600 mt-2">{{ $branch->name }}</div>
                            </div>
                        </div>
                    @else
                        @foreach($mediaItems as $index => $item)
                        <div class="media-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}"
                            data-index="{{ $index }}" data-duration="{{ $item->duration_seconds ?? 10 }}">
                            @if($item->type === 'image')
                                <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover" alt="{{ $item->title }}">
                            @else
                                <video src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover" autoplay muted loop></video>
                            @endif
                            @if($item->title)
                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                                    <div class="text-white font-semibold text-xl">{{ $item->title }}</div>
                                </div>
                            @endif
                        </div>
                        @endforeach
                        <!-- Slide indicators -->
                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                            @foreach($mediaItems as $index => $item)
                                <div class="slide-indicator w-2 h-2 rounded-full transition-colors {{ $index === 0 ? 'bg-primary' : 'bg-white/30' }}" data-index="{{ $index }}"></div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right: Queue Display (2/5) -->
            <div class="w-2/5 flex flex-col border-l border-card-border overflow-hidden">
                <!-- Currently Served / In Process -->
                <div class="bg-gradient-to-br from-primary to-blue-700 p-6 shrink-0">
                    <div class="text-xs font-semibold text-blue-200 uppercase tracking-widest mb-3 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                        Sedang Dilayani
                    </div>
                    @php $inProcessQueues = $displayQueues->filter(fn($item) => $item['status'] === 'in_process') @endphp
                    @if($inProcessQueues->isEmpty())
                        <div class="text-center py-4">
                            <div class="text-6xl font-black text-white/30 mb-2">---</div>
                            <div class="text-blue-200 text-sm">Belum ada antrian dipanggil</div>
                        </div>
                    @else
                        <div class="space-y-3" id="current-queues-display">
                            @foreach($inProcessQueues as $item)
                            @php $queue = $item['queue'] @endphp
                            <div class="flex justify-between items-center bg-white/10 rounded-xl px-4 py-3" data-queue-id="{{ $queue->id }}">
                                <div>
                                    <div class="text-4xl font-black text-white tabular-nums">{{ $queue->queue_number }}</div>
                                    <div class="text-blue-200 text-sm mt-0.5">{{ $queue->service_label }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-white font-bold">Loket {{ $queue->counter_number ?? '?' }}</div>
                                    <div class="text-blue-200 text-xs">{{ $queue->servedBy?->name ?? 'Operator' }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Pending Queue Counts -->
                <div class="flex-grow bg-card-dark flex flex-col overflow-hidden">
                    <div class="px-5 py-3 border-b border-card-border flex justify-between items-center shrink-0">
                        <div class="text-sm font-semibold text-gray-300">Antrian Menunggu</div>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                            Live
                        </div>
                    </div>
                    <div class="flex-grow flex items-center justify-center px-4 py-6" id="pending-counts-display">
                        <div class="grid grid-cols-3 gap-4 w-full">
                            <!-- Teller -->
                            <div class="text-center">
                                <div class="bg-background-dark border border-card-border rounded-xl p-4">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Teller</div>
                                    <div class="text-5xl font-black text-primary tabular-nums" id="pending-teller">{{ $pendingCounts['teller'] }}</div>
                                    <div class="text-xs text-gray-500 mt-1">antrian</div>
                                </div>
                            </div>
                            <!-- CS -->
                            <div class="text-center">
                                <div class="bg-background-dark border border-card-border rounded-xl p-4">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">CS</div>
                                    <div class="text-5xl font-black text-primary tabular-nums" id="pending-cs">{{ $pendingCounts['cs'] }}</div>
                                    <div class="text-xs text-gray-500 mt-1">antrian</div>
                                </div>
                            </div>
                            <!-- Admin -->
                            <div class="text-center">
                                <div class="bg-background-dark border border-card-border rounded-xl p-4">
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Admin</div>
                                    <div class="text-5xl font-black text-primary tabular-nums" id="pending-admin">{{ $pendingCounts['admin'] }}</div>
                                    <div class="text-xs text-gray-500 mt-1">antrian</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 py-3 border-t border-card-border bg-background-dark shrink-0">
                        <div class="text-center text-sm text-gray-400">
                            Total <span class="font-bold text-primary" id="pending-count">{{ $pendingCounts['teller'] + $pendingCounts['cs'] + $pendingCounts['admin'] }}</span> antrian menunggu
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue Call Overlay -->
    <div id="queue-call-overlay" class="hidden fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-card-dark border border-primary/30 rounded-3xl p-12 text-center shadow-2xl max-w-md w-full animate-scale-in">
            <div class="text-primary text-sm font-semibold uppercase tracking-widest mb-3">Memanggil Antrian</div>
            <div class="text-8xl font-black text-white mb-3 tabular-nums" id="announced-queue-number">---</div>
            <div class="text-2xl text-gray-300 mb-2" id="announced-service"></div>
            <div class="text-lg text-primary font-semibold" id="announced-loket"></div>
        </div>
    </div>

    <script>
        // Clock
        function updateClock() {
            document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID');
        }
        updateClock();
        setInterval(updateClock, 1000);

        // Dynamic Slideshow Setup
        let activeMedia = @json($formattedMedia);
        
        let currentSlide = 0;
        let slideTimeout = null;

        function showSlide(index) {
            const slides = document.querySelectorAll('.media-slide');
            const indicators = document.querySelectorAll('.slide-indicator');
            slides.forEach((s, i) => {
                s.style.opacity = i === index ? '1' : '0';
                if (indicators[i]) {
                    indicators[i].classList.toggle('bg-primary', i === index);
                    indicators[i].classList.toggle('bg-white/30', i !== index);
                }
            });
        }

        function nextSlide() {
            const slides = document.querySelectorAll('.media-slide');
            if (slides.length === 0) return;
            
            if (slideTimeout) clearTimeout(slideTimeout);
            
            const currentElement = slides[currentSlide];
            const duration = parseInt(currentElement.dataset.duration || '10') * 1000;
            
            slideTimeout = setTimeout(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
                nextSlide();
            }, duration);
        }

        function updateSlideshow(newMedia) {
            const oldIds = activeMedia.map(m => m.id).join(',');
            const newIds = newMedia.map(m => m.id).join(',');
            
            if (oldIds === newIds) return; // No changes in media playlist
            
            activeMedia = newMedia;
            currentSlide = 0;
            if (slideTimeout) clearTimeout(slideTimeout);
            
            const container = document.getElementById('media-container');
            if (activeMedia.length === 0) {
                container.innerHTML = `
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-card-dark to-background-dark">
                        <div class="text-center">
                            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-28 h-28 mx-auto mb-6 opacity-20">
                            <div class="text-3xl font-bold text-white/20">BPR BKK</div>
                            <div class="text-gray-600 mt-2">{{ $branch->name }}</div>
                        </div>
                    </div>
                `;
                return;
            }
            
            // Build slides HTML
            let html = activeMedia.map((item, index) => `
                <div class="media-slide absolute inset-0 transition-opacity duration-1000 ${index === 0 ? 'opacity-100' : 'opacity-0'}"
                    data-index="${index}" data-duration="${item.duration_seconds ?? 10}">
                    ${item.type === 'image' 
                        ? `<img src="${item.url}" class="w-full h-full object-cover" alt="${item.title || ''}">`
                        : `<video src="${item.url}" class="w-full h-full object-cover" autoplay muted loop></video>`
                    }
                    ${item.title ? `
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                            <div class="text-white font-semibold text-xl">${item.title}</div>
                        </div>
                    ` : ''}
                </div>
            `).join('');
            
            // Add indicators
            html += `
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                    ${activeMedia.map((item, index) => `
                        <div class="slide-indicator w-2 h-2 rounded-full transition-colors ${index === 0 ? 'bg-primary' : 'bg-white/30'}" data-index="${index}"></div>
                    `).join('')}
                </div>
            `;
            
            container.innerHTML = html;
            nextSlide();
        }

        // Initialize Slideshow if media exists
        if (activeMedia.length > 0) {
            nextSlide();
        }

        // Speech synthesis (using global reference to prevent Chromium Garbage Collection bug)
        let activeUtterance = null;
        function speak(text) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                activeUtterance = new SpeechSynthesisUtterance(text);
                activeUtterance.lang = 'id-ID';
                activeUtterance.rate = 0.9;
                activeUtterance.volume = 1.0;
                window.speechSynthesis.speak(activeUtterance);
            }
        }

        // =============================================
        // Queue call overlay
        // =============================================
        let overlayTimeout = null;

        function showCallOverlay(queue) {
            document.getElementById('announced-queue-number').textContent = queue.queue_number;
            document.getElementById('announced-service').textContent = queue.service_type;
            document.getElementById('announced-loket').textContent = 'Loket ' + (queue.counter_number ?? '?');
            const overlay = document.getElementById('queue-call-overlay');
            overlay.classList.remove('hidden');
            if (overlayTimeout) clearTimeout(overlayTimeout);
            overlayTimeout = setTimeout(() => overlay.classList.add('hidden'), 5000);
            speak('Nomor antrian ' + queue.queue_number.split('').join(' ') + ', silakan menuju Loket ' + (queue.counter_number ?? '') + '.');
        }

        // =============================================
        // Polling with reliable recall detection (ID + recall_count)
        // =============================================
        @php
            $latestInProcess = \App\Models\Queue::forBranch($branch->id)->today()->inProcess()->orderBy('called_at', 'desc')->first();
        @endphp
        let lastCalledKey = '{{ $latestInProcess ? $latestInProcess->id . "-" . ($latestInProcess->recall_count ?? 0) : "" }}';

        async function pollDisplay() {
            try {
                const response = await fetch('{{ route("display.data", $branch->id) }}');
                if (!response.ok) return;
                const data = await response.json();

                // Detect new call or recall using unique key (ID + recall_count)
                if (data.latest_called) {
                    const newKey = data.latest_called.id + '-' + (data.latest_called.recall_count ?? 0);
                    if (newKey !== lastCalledKey) {
                        showCallOverlay(data.latest_called);
                        lastCalledKey = newKey;
                    }
                }

                // Update current display list (in-process queues)
                const currentDisplay = document.getElementById('current-queues-display');
                const inProcess = data.display_queues.filter(q => q.status === 'in_process');
                if (currentDisplay) {
                    if (inProcess.length === 0) {
                        currentDisplay.innerHTML = '<div class="text-center py-4"><div class="text-6xl font-black text-white/30 mb-2">---</div><div class="text-blue-200 text-sm">Belum ada antrian dipanggil</div></div>';
                    } else {
                        currentDisplay.innerHTML = inProcess.map(q => `
                            <div class="flex justify-between items-center bg-white/10 rounded-xl px-4 py-3">
                                <div>
                                    <div class="text-4xl font-black text-white tabular-nums">${q.queue_number}</div>
                                    <div class="text-blue-200 text-sm mt-0.5">${q.service_type}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-white font-bold">Loket ${q.counter_number ?? '?'}</div>
                                </div>
                            </div>
                        `).join('');
                    }
                }

                // Update pending counts
                const pc = data.pending_counts;
                document.getElementById('pending-teller').textContent = pc.teller;
                document.getElementById('pending-cs').textContent = pc.cs;
                document.getElementById('pending-admin').textContent = pc.admin;
                document.getElementById('pending-count').textContent = pc.teller + pc.cs + pc.admin;

                // Live dynamic slideshow updates (no page refresh required)
                if (data.media) {
                    updateSlideshow(data.media);
                }
            } catch(e) {
                console.error('Display poll error:', e);
            }
        }

        setInterval(pollDisplay, 3000);
    </script>
</body>
</html>
