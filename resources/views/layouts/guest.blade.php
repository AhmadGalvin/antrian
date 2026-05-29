<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BPR BKK') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-background-dark text-gray-200 antialiased" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen flex flex-col items-center justify-center p-6 relative overflow-hidden">
            <!-- Animated background orbs -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-48 -right-48 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-48 -left-48 w-96 h-96 bg-primary/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-800/5 rounded-full blur-3xl"></div>
            </div>

            <!-- Logo -->
            <div class="relative z-10 mb-6">
                <a href="/" class="flex flex-col items-center gap-2">
                    <div class="p-3 bg-primary/10 border border-primary/20 rounded-2xl">
                        <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK Logo" class="w-14 h-14">
                    </div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">BPR BKK</div>
                        <div class="text-xs text-gray-500">Queue Management System</div>
                    </div>
                </a>
            </div>

            <!-- Card -->
            <div class="relative z-10 w-full max-w-md">
                <div class="bg-card-dark border border-card-border rounded-2xl shadow-2xl shadow-black/50 p-8">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 mt-6 text-xs text-gray-600">
                &copy; {{ date('Y') }} BPR BKK. All rights reserved.
            </div>
        </div>
    </body>
</html>
