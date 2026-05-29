<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Antrian - {{ $queue->queue_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            @page { size: 80mm auto; margin: 0; }
            body { margin: 0; padding: 0; background: white !important; }
            .no-print { display: none !important; }
            .print-area {
                width: 80mm;
                padding: 5mm;
                font-family: monospace;
                background: white !important;
                color: black !important;
            }
            .print-area * { color: black !important; }
        }
        .print-area {
            width: 80mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body class="bg-background-dark min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="max-w-md w-full">
        <!-- Success Header -->
        <div class="text-center mb-8 no-print">
            <div class="w-20 h-20 bg-green-500/15 border border-green-500/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">Antrian Berhasil!</h1>
            <p class="text-gray-400">Silakan tunggu nomor Anda dipanggil</p>
        </div>

        <!-- Ticket Print Area -->
        <div class="print-area rounded-2xl shadow-2xl" id="print-area">
            <div class="text-center border-b-2 border-dashed border-gray-300 pb-4 mb-4">
                <h2 class="text-lg font-bold">BPR {{ $queue->branch->name }}</h2>
                <p class="text-sm text-gray-600">{{ $queue->branch->address }}</p>
            </div>
            <div class="text-center py-6">
                <div class="text-sm text-gray-500 mb-2 uppercase tracking-widest">Nomor Antrian</div>
                <div class="text-7xl font-black text-gray-900 mb-2">{{ $queue->queue_number }}</div>
                <div class="text-lg text-gray-700 font-medium">{{ $queue->service_label }}</div>
            </div>
            <div class="border-t-2 border-dashed border-gray-300 pt-4 mt-4">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Tanggal:</span>
                    <span>{{ $queue->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Waktu:</span>
                    <span>{{ $queue->created_at->format('H:i:s') }}</span>
                </div>
            </div>
            <div class="text-center mt-6 pt-4 border-t-2 border-dashed border-gray-300">
                <p class="text-xs text-gray-500">Harap simpan tiket ini</p>
                <p class="text-xs text-gray-500">Terima kasih atas kunjungan Anda</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-4 no-print">
            <button onclick="window.print()" class="flex-1 flex items-center justify-center gap-2 py-4 px-6 bg-card-dark border border-card-border text-white font-semibold rounded-xl text-base hover:bg-card-border/50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Tiket
            </button>
            <a href="{{ route('kiosk.index', ['branch' => $queue->branch_id]) }}" class="flex-1 flex items-center justify-center gap-2 py-4 px-6 bg-primary hover:bg-primary-hover text-white font-semibold rounded-xl text-base transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        <!-- Countdown -->
        <div class="text-center mt-4 no-print">
            <p class="text-gray-500 text-sm">Halaman akan kembali otomatis dalam <span id="countdown" class="text-white font-bold">15</span> detik</p>
        </div>
    </div>

    <script>
        window.onload = () => setTimeout(() => window.print(), 500);
        let seconds = 15;
        const countdown = setInterval(() => {
            seconds--;
            document.getElementById('countdown').textContent = seconds;
            if (seconds <= 0) {
                clearInterval(countdown);
                window.location.href = '{{ route("kiosk.index", ["branch" => $queue->branch_id]) }}';
            }
        }, 1000);
    </script>
</body>
</html>
