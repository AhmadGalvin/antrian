<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Cabang - Kiosk Antrian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-background-dark min-h-screen font-sans">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-primary/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center mb-8">
            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-4xl font-bold text-white mb-2">Sistem Antrian BPR</h1>
            <p class="text-gray-400 text-lg">Pilih lokasi cabang Anda</p>
        </div>

        <div class="relative z-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-5xl">
            @foreach($branches as $branch)
            <a href="{{ route('kiosk.index', ['branch' => $branch->id]) }}" 
                class="bg-card-dark border border-card-border hover:border-primary/50 text-white p-6 rounded-2xl text-center transition-all transform hover:scale-105 hover:shadow-xl hover:shadow-primary/10 group">
                <svg class="w-10 h-10 mx-auto mb-3 text-primary/60 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <div class="font-bold text-lg">{{ $branch->name }}</div>
                <div class="text-gray-400 text-sm">{{ $branch->code }}</div>
            </a>
            @endforeach
        </div>
    </div>
</body>
</html>
