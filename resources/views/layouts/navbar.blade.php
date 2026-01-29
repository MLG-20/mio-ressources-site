<nav class="bg-slate-900 text-white sticky top-0 z-50 shadow-lg" x-data="{ mobileMenuOpen: false }">
    <div class="px-4 md:px-8 py-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="font-black uppercase tracking-tighter text-xs md:text-base">MIO Ressource</span>
            </a>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center gap-4">
                <a href="/" class="text-sm font-bold uppercase hover:text-blue-400 transition">Accueil</a>
                <a href="{{ route('private-lessons.browse') }}" class="text-sm font-bold uppercase hover:text-blue-400 transition">Cours Particuliers</a>

                @auth
                    @if(Auth::user()->role === 'professeur')
                        <a href="{{ route('teacher.dashboard') }}" class="bg-white/10 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-white/20 transition">
                            Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="bg-white/10 px-4 py-2 rounded-xl text-xs font-bold uppercase hover:bg-white/20 transition">
                            Mon Espace
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-bold uppercase hover:text-red-400 transition">
                            <i class="fas fa-sign-out-alt mr-1"></i>Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold uppercase hover:text-blue-400 transition">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg hover:bg-blue-700 transition">
                        S'inscrire
                    </a>
                @endauth
            </div>

            <!-- Bouton Hamburger Mobile -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
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
         class="md:hidden bg-slate-800 border-t border-white/10">
        <div class="px-4 py-4 space-y-3">
            <a href="/" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase hover:bg-white/10 transition">
                <i class="fas fa-home mr-2"></i>Accueil
            </a>
            <a href="{{ route('private-lessons.browse') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase hover:bg-white/10 transition">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Cours Particuliers
            </a>

            @auth
                @if(Auth::user()->role === 'professeur')
                    <a href="{{ route('teacher.dashboard') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase bg-white/10 hover:bg-white/20 transition">
                        <i class="fas fa-th-large mr-2"></i>Tableau de bord
                    </a>
                @else
                    <a href="{{ route('user.dashboard') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase bg-white/10 hover:bg-white/20 transition">
                        <i class="fas fa-user-circle mr-2"></i>Mon Espace
                    </a>
                @endif

                <div class="pt-3 border-t border-white/10">
                    <div class="px-4 py-2 mb-2">
                        <p class="text-xs text-white/50 uppercase font-bold">Connecté en tant que</p>
                        <p class="text-sm font-bold text-white">{{ Auth::user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left py-3 px-4 rounded-xl text-sm font-bold uppercase hover:bg-red-600/20 text-red-400 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase hover:bg-white/10 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                </a>
                <a href="{{ route('register') }}" class="block py-3 px-4 rounded-xl text-sm font-bold uppercase bg-blue-600 hover:bg-blue-700 transition text-center">
                    <i class="fas fa-user-plus mr-2"></i>S'inscrire
                </a>
            @endauth
        </div>
    </div>
</nav>
