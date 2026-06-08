<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Display Antrian - {{ $branch->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        /* ===== Premium Display-specific styles ===== */
        :root {
            --bg-obsidian: #111621;
            --bg-card: #1a2130;
            --border-card: #2d3548;
            --primary-blue: #1754cf;
            --primary-hover: #1342a3;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-obsidian);
            color: #f8fafc;
        }
        
        .font-display {
            font-family: 'Outfit', sans-serif;
        }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Pop animation on new call */
        @keyframes numberPop {
            0% { transform: scale(0.9); opacity: 0; }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-number-pop {
            animation: numberPop 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Subtle clean translation on bottom counter flash */
        @keyframes counterFlash {
            0% { 
                background-color: rgba(23, 84, 207, 0.15); 
                border-color: var(--primary-blue); 
                transform: translateY(-4px); 
            }
            100% { 
                background-color: var(--bg-card); 
                border-color: var(--border-card); 
                transform: translateY(0); 
            }
        }
        .animate-counter-flash {
            animation: counterFlash 3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Simple border change for new call */
        @keyframes cardFlash {
            0% { 
                border-color: var(--primary-blue); 
                background-color: rgba(23, 84, 207, 0.05);
            }
            100% { 
                border-color: var(--border-card);
                background-color: var(--bg-card);
            }
        }
        .animate-card-flash {
            animation: cardFlash 3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Running text CSS animation */
        @keyframes scrollText {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        .running-text-scroll {
            display: inline-block;
            white-space: nowrap;
            animation: scrollText 35s linear infinite;
        }
        .running-text-scroll:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col">

    <!-- Audio Unlock Overlay -->
    <div id="audio-unlock-overlay" class="fixed inset-0 z-[999] bg-[#111621]/50 backdrop-blur-sm flex flex-col items-center justify-center cursor-pointer transition-opacity duration-500" onclick="unlockAudio()">
        <div class="text-center flex flex-col items-center max-w-2xl px-6">
            <div class="w-24 h-24 bg-[#1754cf] text-white rounded-full flex items-center justify-center mb-8 shadow-[0_0_40px_rgba(23,84,207,0.6)] border border-white/20">
                <svg class="w-12 h-12 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
            </div>
            <h2 class="text-4xl font-black text-white font-display mb-4 tracking-wide drop-shadow-lg">Mulai Layar Antrean</h2>
            <p class="text-gray-300 text-xl mb-10 drop-shadow-md leading-relaxed">Sistem browser modern mewajibkan 1x klik pada layar untuk mengaktifkan fitur suara dan putar otomatis (Autoplay).</p>
            <button class="px-10 py-4 bg-[#1754cf] hover:bg-[#1342a3] text-white font-bold rounded-2xl text-xl transition-colors shadow-[0_0_30px_rgba(23,84,207,0.5)] tracking-wide">
                Klik Untuk Memulai
            </button>
        </div>
    </div>

    <!-- ==================== HEADER ==================== -->
    <header class="h-[8vh] px-[2vw] flex items-center justify-between shrink-0 border-b border-white/[0.04] bg-[#1a2130]/50 backdrop-blur-xl">
        <div class="flex items-center gap-[1vw]">
            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="h-[4.8vh] w-[4.8vh] object-contain">
            <div>
                <div class="font-extrabold text-white text-[1.3vw] tracking-tight leading-none font-display">PT. BPR BKK WONOGIRI (Perseroda)</div>
                <div class="text-[0.75vw] text-[#1754cf] font-bold tracking-widest uppercase mt-[0.4vh]">CABANG {{ $branch->name }} &bull; SISTEM ANTRIAN DIGITAL</div>
            </div>
        </div>
        <div class="flex items-center gap-[1.5vw]">
            <a href="{{ route('login') }}" class="px-[0.8vw] py-[0.6vh] text-[0.8vw] bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-md transition-all opacity-0 hover:opacity-100 flex items-center gap-[0.3vw]" title="Keluar dari Display">
                <svg class="w-[0.9vw] h-[0.9vw]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </a>
            <div class="text-right flex flex-col justify-center border-l border-white/[0.06] pl-[1.5vw] h-[5vh]">
                <div class="text-[0.7vw] text-slate-400 font-semibold uppercase tracking-widest leading-none">{{ now()->translatedFormat('l, d F Y') }}</div>
                <div class="text-[1.6vw] font-black text-white tabular-nums tracking-tight mt-[0.3vh] leading-none font-display" id="clock">--:--:--</div>
            </div>
        </div>
    </header>

    <!-- ==================== MAIN CONTENT ==================== -->
    @php
        $latestInProcess = \App\Models\Queue::forBranch($branch->id)->today()->whereNotNull('called_at')->orderBy('called_at', 'desc')->first();
    @endphp
    <div class="h-[68vh] flex flex-grow overflow-hidden gap-0 min-h-0">

        <!-- LEFT: Latest Called Number -->
        <div class="w-[32%] h-full flex flex-col items-center justify-center p-[1.5vw] relative overflow-hidden border-r border-[#2d3548] bg-[#111621]" id="left-panel">
            <div id="main-call-card" class="relative z-10 flex flex-col items-center justify-between w-full h-full bg-[#1a2130] border border-[#2d3548] rounded-2xl p-[3vw] shadow-2xl transition-all duration-500">
                <!-- Top section: Queue number -->
                <div class="flex flex-col items-center justify-center flex-1 w-full">
                    <div class="text-slate-400 text-[1.1vw] font-bold uppercase tracking-[0.25em] mb-[2.5vh] relative z-10">Nomor Antrian</div>
                    <div class="text-[5.5vw] font-black text-white leading-none tracking-tight font-display whitespace-nowrap transition-all duration-300 relative z-10" id="announced-queue-number">
                        {{ $latestInProcess ? $latestInProcess->queue_number : '---' }}
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-[85%] h-px bg-[#2d3548] relative z-10 shrink-0"></div>

                <!-- Bottom section: Loket number -->
                <div class="flex flex-col items-center justify-center flex-1 w-full">
                    <div class="text-slate-400 text-[1.1vw] font-bold uppercase tracking-[0.25em] mb-[2vh] relative z-10" id="announced-service">LOKET</div>
                    <div class="text-[5.5vw] font-black text-[#1754cf] leading-none tracking-tight font-display relative z-10" id="announced-loket">
                        {{ $latestInProcess ? $latestInProcess->counter_number : '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT: Media Area (16:9 aspect) -->
        <div class="w-[68%] h-full bg-black relative overflow-hidden">
            <!-- Inner frame highlight border -->
            <div class="absolute inset-0 border-l border-[#2d3548] z-20 pointer-events-none"></div>
            <div id="media-container" class="absolute inset-0">
                @if($mediaItems->isEmpty())
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a2130] via-[#111621] to-[#1a2130]">
                        <div class="text-center">
                            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-24 h-24 mx-auto mb-6 opacity-5">
                            <div class="text-2xl font-black text-white/5 tracking-wider font-display">PT. BPR BKK WONOGIRI</div>
                            <div class="text-slate-600 mt-2 text-xs tracking-widest uppercase font-medium">{{ $branch->name }}</div>
                        </div>
                    </div>
                @else
                    @foreach($mediaItems as $index => $item)
                    <div class="media-slide absolute inset-0 bg-black transition-opacity duration-1000 {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                        data-index="{{ $index }}" data-duration="{{ $item->duration_seconds ?? 10 }}">
                        @if($item->type === 'image')
                            <img src="{{ asset('storage/' . $item->file_path) }}" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 max-w-full max-h-full object-contain" alt="">
                        @else
                            <video src="{{ asset('storage/' . $item->file_path) }}" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 max-w-full max-h-full object-contain" autoplay {{ $mediaItems->count() <= 1 ? 'loop' : '' }} playsinline></video>
                        @endif
                    </div>
                    @endforeach
                    <!-- Slide indicators -->
                    @if($mediaItems->count() > 1)
                    <div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2 z-20">
                         @foreach($mediaItems as $index => $item)
                             <div class="slide-indicator rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-white w-6 h-1.5' : 'w-1.5 h-1.5 bg-white/30' }}" data-index="{{ $index }}"></div>
                         @endforeach
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- ==================== BOTTOM COUNTERS ==================== -->
    <div class="h-[18vh] shrink-0 border-t border-[#2d3548] bg-[#111621] flex items-center justify-center px-[2vw]">
        @if($activeCounters->isEmpty())
            <div class="text-center">
                <span class="text-slate-500 text-[1.1vw] font-medium">Belum ada loket yang aktif</span>
            </div>
        @else
            <div class="flex items-center justify-center gap-[1.5vw] w-full max-w-[96vw]">
                @php
                    // Group active counters by role to count them
                    $roleGroups = $activeCounters->groupBy(function($c) {
                        return strtolower($c->role);
                    });
                    
                    $counterCountsByRole = $roleGroups->map->count();
                    
                    // Assign sequence numbers to counters per role
                    $counterIndices = [];
                    foreach ($roleGroups as $role => $counters) {
                        $index = 1;
                        foreach ($counters as $c) {
                            $counterIndices[$c->id] = $index++;
                        }
                    }
                @endphp
                @foreach($activeCounters as $counter)
                    @php
                        $counterQueue = collect($displayQueues)->first(function($item) use ($counter) {
                            return ($item['queue']->counter_number ?? null) == $counter->counter_number;
                         });
                        $queueNum = $counterQueue ? ($counterQueue['queue']->queue_number ?? '---') : '---';
                        
                        $roleLower = strtolower($counter->role);
                        $roleCount = $counterCountsByRole[$roleLower] ?? 1;
                        $displayName = strtoupper($counter->role);
                        if ($roleCount > 1) {
                            $sequenceNum = $counterIndices[$counter->id] ?? 1;
                            $displayName .= ' ' . str_pad($sequenceNum, 2, '0', STR_PAD_LEFT);
                        }
                    @endphp
                    <div class="flex-1 max-w-[22vw] min-w-[14vw] h-[14vh] bg-[#1a2130] border border-[#2d3548] rounded-xl p-[1.5vh] flex flex-col justify-center items-center shadow-lg transition-all duration-500 relative overflow-hidden" id="counter-card-{{ $counter->counter_number }}">
                        <!-- Colored top border accent -->
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-[#1754cf] opacity-70"></div>
                        
                        <!-- Pulsing online status indicator -->
                        <div class="absolute top-[1.2vh] right-[0.8vw] flex items-center">
                            <span class="w-[0.4vw] h-[0.4vw] rounded-full bg-[#1754cf]"></span>
                        </div>

                        <div class="text-slate-400 text-[1.1vw] font-bold uppercase tracking-[0.15em] mb-[0.8vh]">
                            {{ $displayName }}
                        </div>
                        <div class="text-[3vw] font-black text-white tabular-nums leading-none font-display transition-all duration-300" id="counter-queue-{{ $counter->counter_number }}">
                            {{ $queueNum }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- ==================== RUNNING TEXT FOOTER ==================== -->
    <div class="h-[6vh] bg-[#1754cf] text-white shrink-0 flex items-center overflow-hidden relative border-t border-[#1342a3]/30">
        <div class="font-black shrink-0 h-full flex items-center px-[2vw] bg-[#111621] text-white text-[1vw] uppercase tracking-[0.2em] relative z-10 font-display shadow-[4px_0_15px_rgba(0,0,0,0.15)]">
            Info
        </div>
        <div class="flex-grow overflow-hidden relative z-10 pl-[1.5vw]">
            <div class="running-text-scroll text-[1.25vw] font-bold tracking-wide text-white" id="running-text-content">
                {{ $branch->running_text ?? 'PT. BPR BKK WONOGIRI (Perseroda) senantiasa menghimpun dan menyalurkan dana dari dan untuk masyarakat.' }}
            </div>
        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        // ===== Clock =====
        function updateClock() {
            const now = new Date();
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = h + ':' + m + ':' + s;
        }
        updateClock();
        setInterval(updateClock, 1000);

        // ===== Audio Unlock Interaction =====
        let hasInteracted = false;
        function unlockAudio() {
            hasInteracted = true;
            const overlay = document.getElementById('audio-unlock-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 500);
            }
            
            // Unmute and play any currently active video
            const videos = document.querySelectorAll('video');
            videos.forEach(v => {
                v.muted = false;
                v.play().catch(e => console.log('Autoplay blocked:', e));
            });
        }

        // ===== Dynamic Slideshow =====
        let activeMedia = @json($formattedMedia);
        let currentSlide = 0;
        let slideTimeout = null;

        function showSlide(index) {
            const slides = document.querySelectorAll('.media-slide');
            const indicators = document.querySelectorAll('.slide-indicator');
            
            // 1. Matikan semua video agar suara tidak bocor
            const allVideos = document.querySelectorAll('.media-slide video');
            allVideos.forEach(v => {
                v.pause();
                v.currentTime = 0;
            });

            // 2. Ganti tampilan visual
            slides.forEach((s, i) => {
                if (i === index) {
                    s.classList.remove('opacity-0', 'z-0');
                    s.classList.add('opacity-100', 'z-10');
                } else {
                    s.classList.remove('opacity-100', 'z-10');
                    s.classList.add('opacity-0', 'z-0');
                }
                if (indicators[i]) {
                    indicators[i].className = i === index 
                        ? 'slide-indicator rounded-full transition-all duration-300 bg-white w-6 h-1.5'
                        : 'slide-indicator rounded-full transition-all duration-300 w-1.5 h-1.5 bg-white/30';
                }
            });

            // 3. Putar video yang aktif
            const activeSlide = slides[index];
            if (!activeSlide) return;
            const activeVideo = activeSlide.querySelector('video');
            if (activeVideo) {
                if (hasInteracted) activeVideo.muted = false;
                activeVideo.play().catch(e => console.log('Autoplay blocked:', e));
            }
        }

        function nextSlide() {
            const slides = document.querySelectorAll('.media-slide');
            if (slides.length <= 1) return;
            
            if (slideTimeout) clearTimeout(slideTimeout);
            
            const currentElement = slides[currentSlide];
            if (!currentElement) return;
            
            const activeVideo = currentElement.querySelector('video');

            if (activeVideo) {
                // Tunggu video sampai selesai (otomatis menyesuaikan durasi asli video)
                activeVideo.removeAttribute('loop');
                activeVideo.onended = () => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                    nextSlide();
                };
            } else {
                // Gambar menggunakan durasi pengaturan (detik)
                const duration = parseInt(currentElement.dataset.duration || '10') * 1000;
                slideTimeout = setTimeout(() => {
                    currentSlide = (currentSlide + 1) % slides.length;
                    showSlide(currentSlide);
                    nextSlide();
                }, duration);
            }
        }

        function updateSlideshow(newMedia) {
            const oldIds = activeMedia.map(m => m.id).join(',');
            const newIds = newMedia.map(m => m.id).join(',');
            if (oldIds === newIds) return;

            activeMedia = newMedia;
            currentSlide = 0;
            if (slideTimeout) clearTimeout(slideTimeout);

            const container = document.getElementById('media-container');
            if (activeMedia.length === 0) {
                container.innerHTML = `
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#1a2130] via-[#111621] to-[#1a2130]">
                        <div class="text-center">
                            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-24 h-24 mx-auto mb-6 opacity-5">
                            <div class="text-2xl font-black text-white/5 tracking-wider font-display">PT. BPR BKK WONOGIRI</div>
                            <div class="text-slate-600 mt-2 text-xs tracking-widest uppercase font-medium">{{ $branch->name }}</div>
                        </div>
                    </div>`;
                return;
            }

            let html = activeMedia.map((item, index) => `
                <div class="media-slide absolute inset-0 bg-black transition-opacity duration-1000 ${index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0'}"
                    data-index="${index}" data-duration="${item.duration_seconds ?? 10}">
                    ${item.type === 'image'
                        ? `<img src="${item.url}" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 max-w-full max-h-full object-contain" alt="">`
                        : `<video src="${item.url}" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 max-w-full max-h-full object-contain" autoplay ${activeMedia.length <= 1 ? 'loop' : ''} playsinline ${!hasInteracted ? 'muted' : ''}></video>`
                    }
                </div>`).join('');

            if (activeMedia.length > 1) {
                html += `<div class="absolute bottom-5 left-0 right-0 flex justify-center gap-2 z-20">
                    ${activeMedia.map((_, index) => `
                        <div class="slide-indicator rounded-full transition-all duration-300 ${index === 0 ? 'bg-white w-6 h-1.5' : 'w-1.5 h-1.5 bg-white/30'}" data-index="${index}"></div>
                    `).join('')}</div>`;
            }

            container.innerHTML = html;
            if (activeMedia.length > 1) nextSlide();
        }

        if (activeMedia.length > 1) nextSlide();

        // ===== Call Effect Queue System =====
        let callQueue = [];
        let isCalling = false;
        let currentUtterance = null; // Global reference to prevent GC bugs in Chrome TTS

        function processCallQueue() {
            if (callQueue.length === 0) {
                isCalling = false;
                // Kembalikan volume video ke normal (100%) setelah semua antrean selesai
                const allVideos = document.querySelectorAll('.media-slide video');
                allVideos.forEach(v => v.volume = 1.0);
                return;
            }
            if (isCalling) return;

            isCalling = true;
            
            // Turunkan volume video (Audio Ducking) ke 15% agar suara panggilan jelas
            const allVideos = document.querySelectorAll('.media-slide video');
            allVideos.forEach(v => v.volume = 0.15);

            const queue = callQueue.shift();

            // 1. VISUAL UPDATES
            const qNumberEl = document.getElementById('announced-queue-number');
            const loketEl = document.getElementById('announced-loket');

            // Update main card text
            qNumberEl.textContent = queue.queue_number;
            loketEl.textContent = queue.counter_number;

            // Pop animation on the number
            qNumberEl.classList.remove('animate-number-pop');
            void qNumberEl.offsetWidth;
            qNumberEl.classList.add('animate-number-pop');
            setTimeout(() => qNumberEl.classList.remove('animate-number-pop'), 800);

            // Flash the main call card
            const mainCard = document.getElementById('main-call-card');
            if (mainCard) {
                mainCard.classList.remove('animate-card-flash');
                void mainCard.offsetWidth; // Trigger reflow
                mainCard.classList.add('animate-card-flash');
                setTimeout(() => mainCard.classList.remove('animate-card-flash'), 3000);
            }

            // Flash the specific counter card in the bottom grid
            const card = document.getElementById('counter-card-' + queue.counter_number);
            const counterNum = document.getElementById('counter-queue-' + queue.counter_number);
            if (counterNum) counterNum.textContent = queue.queue_number;
            if (card) {
                card.classList.remove('animate-counter-flash');
                void card.offsetWidth;
                card.classList.add('animate-counter-flash');
                setTimeout(() => card.classList.remove('animate-counter-flash'), 3000);
            }

            // 2. AUDIO ANNOUNCEMENT (TTS)
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel(); // Clear any stuck speech
                const text = 'Nomor antrian, ' + queue.queue_number.split('').join(' ') + ', silakan menuju Loket, ' + queue.counter_number + '.';
                currentUtterance = new SpeechSynthesisUtterance(text);
                currentUtterance.lang = 'id-ID';
                currentUtterance.rate = 0.85;
                currentUtterance.volume = 1.0;
                
                let isFinished = false;

                const finish = () => {
                    if (isFinished) return;
                    isFinished = true;
                    // Jeda 1 detik setelah selesai bicara sebelum lanjut antrean berikutnya
                    setTimeout(() => {
                        isCalling = false;
                        processCallQueue();
                    }, 1000);
                };

                currentUtterance.onend = finish;
                currentUtterance.onerror = finish;

                window.speechSynthesis.speak(currentUtterance);

                // Failsafe: Jika browser nge-bug dan onend tidak terpanggil, paksa lanjut setelah 8 detik
                setTimeout(() => {
                    if (!isFinished) {
                        window.speechSynthesis.cancel();
                        finish();
                    }
                }, 8000);

            } else {
                // Jika browser tidak support TTS, tunggu saja 4 detik visual
                setTimeout(() => {
                    isCalling = false;
                    processCallQueue();
                }, 4000);
            }
        }

        function triggerCallEffect(queue) {
            callQueue.push(queue);
            processCallQueue();
        }

        // ===== Polling =====
        let lastCalledKey = '{{ $latestInProcess ? $latestInProcess->id . "-" . ($latestInProcess->recall_count ?? 0) : "" }}';

        async function pollDisplay() {
            try {
                const response = await fetch('{{ route("display.data", $branch->id) }}');
                if (!response.ok) return;
                const data = await response.json();

                // 1. Detect new call or recall
                if (data.latest_called) {
                    const newKey = data.latest_called.id + '-' + (data.latest_called.recall_count ?? 0);
                    if (newKey !== lastCalledKey) {
                        triggerCallEffect(data.latest_called);
                        lastCalledKey = newKey;
                    }
                }

                // 2. Update bottom counter grid
                if (data.display_queues) {
                    data.display_queues.forEach(q => {
                        const el = document.getElementById('counter-queue-' + q.counter_number);
                        if (el) el.textContent = q.queue_number;
                    });
                }

                // 3. Update media slideshow
                if (data.media) updateSlideshow(data.media);

                // 4. Update running text
                if (data.running_text !== undefined) {
                    const rtEl = document.getElementById('running-text-content');
                    if (rtEl && rtEl.textContent.trim() !== data.running_text.trim()) {
                        rtEl.textContent = data.running_text || 'PT. BPR BKK WONOGIRI (Perseroda)';
                    }
                }
            } catch(e) {
                console.error('Display poll error:', e);
            }
        }

        setInterval(pollDisplay, 3000);
    </script>
</body>
</html>
