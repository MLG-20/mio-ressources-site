<nav class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white sticky top-0 z-50 shadow-lg border-b border-slate-200 dark:border-white/10 transition-colors duration-300"
     x-data="{ mobileMenuOpen: false }">
    <div class="px-4 md:px-8 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="font-black uppercase tracking-tighter text-xs md:text-base">MIO Ressource</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center gap-4">
                <a href="/" class="text-sm font-bold uppercase hover:text-blue-500 transition">Accueil</a>
                <a href="{{ route('private-lessons.browse') }}" class="text-sm font-bold uppercase hover:text-blue-500 transition">Cours Particuliers</a>

                <button id="theme-toggle-shared-desktop" type="button"
                        class="w-10 h-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-yellow-300 hover:scale-105 transition-all flex items-center justify-center"
                        aria-label="Changer le theme">
                    <i class="fas fa-moon"></i>
                </button>

                @auth
                    @if(Auth::user()->role === 'professeur')
                        <a href="{{ route('teacher.dashboard') }}" class="bg-slate-100 dark:bg-white/10 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-slate-200 dark:hover:bg-white/20 transition">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="bg-slate-100 dark:bg-white/10 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-slate-200 dark:hover:bg-white/20 transition">
                            Mon Espace
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-bold uppercase hover:text-red-500 transition">
                            <i class="fas fa-sign-out-alt mr-1"></i>Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold uppercase hover:text-blue-500 transition">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg hover:bg-blue-700 transition">
                        S'inscrire
                    </a>
                @endauth
            </div>

            <!-- Bouton Hamburger Mobile -->
            <div class="md:hidden flex items-center gap-2">
                <button id="theme-toggle-shared-mobile" type="button"
                        class="w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-yellow-300 hover:scale-105 transition-all flex items-center justify-center"
                        aria-label="Changer le theme">
                    <i class="fas fa-moon"></i>
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 rounded-lg transition-colors">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Mobile -->
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         @click.away="mobileMenuOpen = false"
         class="absolute top-full left-0 w-full md:hidden bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-white/10 max-h-[85vh] overflow-y-auto shadow-2xl transition-colors duration-300">
        <div class="px-3 py-3 space-y-2">
            <a href="/" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase text-slate-800 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
            <a href="{{ route('private-lessons.browse') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase text-slate-800 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Cours Particuliers
            </a>

            @auth
                @if(Auth::user()->role === 'professeur')
                    <a href="{{ route('teacher.dashboard') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase text-slate-800 dark:text-white bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 transition">
                        <i class="fas fa-th-large mr-2"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase text-slate-800 dark:text-white bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 transition">
                        <i class="fas fa-user-circle mr-2"></i>Mon Espace
                    </a>
                @endif

                <div class="pt-2 border-t border-slate-200 dark:border-white/10">
                    <div class="px-3 py-1.5 mb-2">
                        <p class="text-[10px] text-slate-500 dark:text-white/50 uppercase font-bold">Connecté</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-center py-3 px-4 rounded-xl text-sm font-bold uppercase bg-red-600 hover:bg-red-700 text-white transition shadow-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <!-- Boutons Connexion/Inscription bien visibles -->
                <div class="pt-2 border-t-2 border-blue-600 space-y-2">
                    <a href="{{ route('login') }}" class="block py-3 px-4 rounded-xl text-sm font-black uppercase bg-slate-900 hover:bg-slate-700 text-white transition text-center shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                    <a href="{{ route('register') }}" class="block py-3 px-4 rounded-xl text-sm font-black uppercase bg-blue-600 hover:bg-blue-700 text-white transition text-center shadow-2xl">
                        <i class="fas fa-user-plus mr-2"></i>S'inscrire
                    </a>
                </div>
            @endauth
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
            const icons = document.querySelectorAll('#theme-toggle-shared-desktop i, #theme-toggle-shared-mobile i');
            icons.forEach((icon) => {
                icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
            });
        };

        const bindToggle = (id) => {
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
        bindToggle('theme-toggle-shared-desktop');
        bindToggle('theme-toggle-shared-mobile');
        syncIcons();
    })();
</script>
