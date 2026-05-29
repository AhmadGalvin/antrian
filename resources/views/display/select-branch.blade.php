<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Cabang - Display Antrian</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-background-dark min-h-screen font-sans">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center mb-8">
            <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="w-20 h-20 mx-auto mb-4">
            <h1 class="text-4xl font-bold text-white mb-2">Display Antrian BPR</h1>
            <p class="text-gray-400 text-lg">Pilih lokasi cabang untuk menampilkan layar antrian</p>
        </div>

        <div class="relative z-10 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-w-5xl">
            @foreach($branches as $branch)
            <a href="{{ route('display.show', ['branchId' => $branch->id]) }}" 
                class="bg-card-dark border border-card-border hover:border-blue-500/50 text-white p-6 rounded-2xl text-center transition-all transform hover:scale-105 hover:shadow-xl hover:shadow-blue-500/10 group">
                <svg class="w-10 h-10 mx-auto mb-3 text-blue-500/60 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <div class="font-bold text-lg">{{ $branch->name }}</div>
                <div class="text-gray-400 text-sm">{{ $branch->code }}</div>
            </a>
            @endforeach
        </div>

        <div class="relative z-10 mt-12 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-card-dark border border-card-border hover:border-red-500/50 text-gray-400 hover:text-red-400 rounded-xl transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>
