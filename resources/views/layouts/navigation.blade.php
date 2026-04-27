<nav x-data="{ open: false }" class="bg-white dark:bg-slate-900 shadow-lg border-b border-slate-200 dark:border-slate-700 transition-colors duration-300" style="padding-top: env(safe-area-inset-top);">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 group">
                        <div class="px-4 py-2 bg-slate-100 dark:bg-white/10 backdrop-blur-sm rounded-xl group-hover:bg-slate-200 dark:group-hover:bg-white/20 transition">
                            <span class="text-slate-800 dark:text-white font-extrabold text-xl tracking-tight">Mio Ressources</span>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-8 space-x-1">
                    <a href="{{ route('user.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('user.dashboard') ? 'bg-slate-200 dark:bg-white/20 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-white/80 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10' }} transition">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('teacher.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('teacher.*') ? 'bg-slate-200 dark:bg-white/20 text-slate-900 dark:text-white' : 'text-slate-600 dark:text-white/80 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10' }} transition">
                        <i class="fas fa-chalkboard-user mr-2"></i>Espace Enseignant
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="group flex items-center gap-3 px-4 py-2 bg-slate-100 dark:bg-white/10 backdrop-blur-sm rounded-xl text-slate-800 dark:text-white hover:bg-slate-200 dark:hover:bg-white/20 focus:outline-none transition">
                            <div class="w-8 h-8 bg-slate-200 dark:bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500 dark:text-white/70">Mon compte</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-500 dark:text-white/70 group-hover:text-slate-900 dark:group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                <button id="theme-toggle-app-desktop" type="button"
                        class="ml-3 w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-yellow-300 hover:scale-105 transition-all flex items-center justify-center"
                        aria-label="Changer le theme">
                    <i class="fas fa-moon"></i>
                </button>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button id="theme-toggle-app-mobile" type="button"
                        class="mr-2 inline-flex items-center justify-center p-2 rounded-lg text-slate-700 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition">
                    <i class="fas fa-moon"></i>
                </button>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-slate-600 dark:text-white/80 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-slate-900 backdrop-blur-sm transition-colors duration-300">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.dashboard') ? 'bg-slate-200 dark:bg-white/20 text-slate-900 dark:text-white' : 'text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10' }} transition">
                <i class="fas fa-home w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('teacher.*') ? 'bg-slate-200 dark:bg-white/20 text-slate-900 dark:text-white' : 'text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10' }} transition">
                <i class="fas fa-chalkboard-user w-5"></i>
                <span class="font-medium">Espace Enseignant</span>
            </a>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-4 border-t border-slate-200 dark:border-white/10 px-4">
            <div class="flex items-center gap-3 px-4 py-3 mb-3">
                <div class="w-10 h-10 bg-slate-100 dark:bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-slate-700 dark:text-white"></i>
                </div>
                <div>
                    <div class="font-semibold text-slate-900 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-slate-500 dark:text-white/70">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-slate-700 dark:text-white/80 hover:bg-slate-100 dark:hover:bg-white/10 transition">
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

<script>
    (function () {
        const setInitialTheme = () => {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
        };
        const syncIcons = () => {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('#theme-toggle-app-desktop i, #theme-toggle-app-mobile i').forEach((icon) => {
                icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
            });
        };
        const bind = (id) => {
            const btn = document.getElementById(id);
            if (!btn || btn.dataset.themeBound === '1') return;
            btn.dataset.themeBound = '1';
            btn.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                syncIcons();
            });
        };
        setInitialTheme();
        bind('theme-toggle-app-desktop');
        bind('theme-toggle-app-mobile');
        syncIcons();
    })();
</script>
