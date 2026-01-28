<nav x-data="{ open: false }" class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 shadow-lg shadow-indigo-500/20">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 group">
                        <div class="px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl group-hover:bg-white/30 transition">
                            <span class="text-white font-extrabold text-xl tracking-tight">Mio Ressources</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-8 space-x-1">
                    <a href="{{ route('user.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teacher.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:text-white hover:bg-white/10' }} transition">
                        <i class="fas fa-chalkboard-user mr-2"></i>Espace Enseignant
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-3 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-xl text-white hover:bg-white/20 focus:outline-none transition">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-white/70">Mon compte</div>
                            </div>
                            <svg class="w-4 h-4 text-white/70 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('user.dashboard')" class="flex items-center gap-2">
                            <i class="fas fa-user-circle text-gray-400"></i>
                            {{ __('Mon Profil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('teacher.dashboard')" class="flex items-center gap-2">
                            <i class="fas fa-chalkboard-user text-gray-400"></i>
                            {{ __('Espace Enseignant') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/10 backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }} transition">
                <i class="fas fa-home w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('teacher.*') ? 'bg-white/20 text-white' : 'text-white/80 hover:bg-white/10' }} transition">
                <i class="fas fa-chalkboard-user w-5"></i>
                <span class="font-medium">Espace Enseignant</span>
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-white/10 px-4">
            <div class="flex items-center gap-3 px-4 py-3 mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <div class="font-semibold text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-white/70">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 transition">
                    <i class="fas fa-user-circle w-5"></i>
                    <span class="font-medium">Mon Profil</span>
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-red-300 hover:bg-red-500/20 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
