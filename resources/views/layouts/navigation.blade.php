<nav x-data="{ open: false }" class="glass-effect sticky top-0 z-50 w-full">
    <div class="px-6 py-3 flex items-center justify-between">
        <!-- Logo + Brand -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('svg/bkk.svg') }}" alt="BPR BKK" class="h-9 w-9">
                <div class="hidden sm:block">
                    <div class="text-base font-bold text-white leading-tight">PT. BPR BKK WONOGIRI (Perseroda)</div>
                    <div class="text-xs text-gray-400 leading-tight">Queue Management</div>
                </div>
            </a>
        </div>

        <!-- Desktop Navigation Links (center) -->
        <div class="hidden sm:flex items-center space-x-1">
            @auth
                @if(Auth::user()->isSuperadmin())
                    <a href="{{ route('superadmin.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('superadmin.dashboard') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('superadmin.branches') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('superadmin.branches') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Cabang
                    </a>
                    <a href="{{ route('superadmin.users') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('superadmin.users') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        User
                    </a>
                    <a href="{{ route('superadmin.reports') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('superadmin.reports') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Laporan
                    </a>
                    <a href="{{ route('superadmin.media') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('superadmin.media') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Media
                    </a>
                @elseif(Auth::user()->canServeQueue())
                    <a href="{{ route('operator.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg transition-all
                            {{ request()->routeIs('operator.dashboard') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                @endif
            @endauth
        </div>

        <!-- Right: role badge + user dropdown -->
        <div class="hidden sm:flex items-center gap-3">
            @auth
                <!-- Role Badge -->
                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                    @if(Auth::user()->role === 'superadmin') bg-red-500/20 text-red-400 ring-1 ring-red-500/30
                    @elseif(Auth::user()->role === 'teller') bg-blue-500/20 text-blue-400 ring-1 ring-blue-500/30
                    @elseif(Auth::user()->role === 'cs') bg-green-500/20 text-green-400 ring-1 ring-green-500/30
                    @else bg-gray-500/20 text-gray-400 ring-1 ring-gray-500/30 @endif">
                    {{ Auth::user()->role_label }}
                </span>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-1.5 bg-card-dark border border-card-border hover:border-gray-500 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-primary to-blue-400 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2.5 border-b border-card-border">
                            <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 truncate">{{ Auth::user()->branch?->name ?? 'BPR BKK Pusat' }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ __('Profil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @endauth
        </div>

        <!-- Mobile Hamburger -->
        <div class="flex items-center sm:hidden">
            <button @click="open = !open" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-card-dark transition-colors">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden border-t border-card-border">
        <div class="px-4 pt-2 pb-3 space-y-1">
            @auth
                @if(Auth::user()->isSuperadmin())
                    <a href="{{ route('superadmin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('superadmin.dashboard') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Dashboard</a>
                    <a href="{{ route('superadmin.branches') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('superadmin.branches') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Cabang</a>
                    <a href="{{ route('superadmin.users') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('superadmin.users') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">User</a>
                    <a href="{{ route('superadmin.reports') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('superadmin.reports') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Laporan</a>
                    <a href="{{ route('superadmin.media') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('superadmin.media') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Media</a>
                @elseif(Auth::user()->canServeQueue())
                    <a href="{{ route('operator.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('operator.dashboard') ? 'bg-primary/15 text-primary' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Dashboard</a>
                @endif
            @endauth
        </div>
        <div class="border-t border-card-border px-4 py-3 space-y-1">
            @auth
                <div class="text-sm font-medium text-white">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                <a href="{{ route('profile.edit') }}" class="block mt-2 px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-white/5">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-white/5">Keluar</button>
                </form>
            @endauth
        </div>
    </div>
</nav>
