<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
        <p class="text-sm text-gray-400 mt-1">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Email</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="w-full pl-10 pr-4 py-2.5 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors placeholder-gray-600 text-sm"
                    placeholder="email@bprbkk.co.id">
            </div>
            @error('email')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Password</label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full pl-10 pr-4 py-2.5 bg-background-dark border border-card-border text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-colors text-sm"
                    placeholder="••••••••">
            </div>
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded bg-background-dark border-card-border text-primary focus:ring-primary focus:ring-offset-background-dark">
                <span class="text-sm text-gray-400">Ingat saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:text-blue-400 transition-colors">
                    Lupa password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <button type="submit"
            class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-primary hover:bg-primary-hover text-white font-semibold rounded-lg shadow-lg shadow-primary/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-card-dark">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Masuk
        </button>
    </form>
</x-guest-layout>
