<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIO Ressources - Excellence Académique</title>

    <!-- Scripts & Polices -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @include('components.analytics')

    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar Personnalisée */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #3b82f6; }
    </style>
</head>
<body class="bg-[#f8fafc] font-sans antialiased text-slate-900"
      x-data="{ mobileMenuOpen: false, scrolled: false }"
      @scroll.window="scrolled = (window.pageYOffset > 50)"
      :class="{ 'overflow-hidden': mobileMenuOpen }">

    <!-- NAVBAR PREMIUM -->
    <nav class="fixed w-full z-[100] transition-all duration-500 px-3 md:px-4 lg:px-8 py-3 md:py-4"
         :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-lg py-2' : 'bg-transparent py-4 md:py-5'">

        <div class="max-w-7xl mx-auto flex justify-between items-center h-12 md:h-14">

            <!-- LOGO -->
            <a href="/" class="flex items-center gap-2 md:gap-3 z-[110] group">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="text-base md:text-xl font-bold tracking-tighter transition-colors duration-300"
                      :class="(scrolled || mobileMenuOpen) ? 'text-slate-900' : 'text-white'">
                    MIO <span class="font-light text-xs md:text-base" :class="(scrolled || mobileMenuOpen) ? 'text-slate-500' : 'text-white/70'">RESSOURCES</span>
                </span>
            </a>

            <!-- MENU DESKTOP -->
            <div class="hidden lg:flex gap-4 xl:gap-8 font-semibold text-xs xl:text-sm uppercase tracking-widest transition-colors duration-300"
                 :class="scrolled ? 'text-slate-600' : 'text-white/90'">
                <a href="/" class="relative group py-2 hover:text-blue-600 transition">
                    <span>Accueil</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="#cours" class="relative group py-2 hover:text-blue-600 transition">
                    <span>Cours</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('private-lessons.browse') }}" class="relative group py-2 hover:text-purple-600 transition">
                    <span>💼 Cours Particuliers</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-purple-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('forum.index') }}" class="relative group py-2 hover:text-blue-600 transition">
                    <span>Forum</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('library.index') }}" class="relative group py-2 hover:text-blue-600 transition">
                    <span>Biblio.</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('page.show', 'a-propos') }}" class="relative group py-2 hover:text-blue-600 transition">
                    <span>À Propos</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
                <a href="{{ route('page.show', 'club-mio') }}" class="relative group py-2 hover:text-blue-600 transition">
                    <span>Club</span>
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full"></span>
                </a>
            </div>

            <!-- AUTH & BURGER -->
            <div class="flex items-center gap-2 md:gap-4 z-[110]">
                <div class="hidden md:flex items-center">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <!-- BOUTON ADMIN -->
                            <a href="/admin" class="flex items-center gap-2 bg-slate-900 text-white px-4 md:px-5 py-2 rounded-full font-bold shadow-lg hover:bg-blue-600 transition-all transform hover:scale-105 text-xs md:text-sm">
                                <i class="fas fa-shield-check text-blue-400"></i>
                                <span class="text-[10px] uppercase tracking-widest font-bold">Panel Admin</span>
                            </a>
                        @elseif(Auth::user()->user_type === 'teacher')
                            <!-- BOUTON ENSEIGNANT -->
                            <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-2 bg-slate-900 text-white px-5 py-2 rounded-full font-bold shadow-lg hover:bg-blue-600 transition-all transform hover:scale-105">
                                <i class="fas fa-chalkboard-teacher text-blue-400"></i>
                                <span class="text-[10px] uppercase tracking-widest font-bold">Espace Enseignant</span>
                            </a>
                        @else
                            <!-- BOUTON ÉTUDIANT -->
                            <a href="{{ route('user.dashboard') }}" class="flex items-center gap-2 bg-blue-600 text-white pl-1 pr-4 py-1.5 rounded-full font-bold shadow-lg hover:bg-blue-700 transition-all transform hover:scale-105">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=fff&color=2563eb&bold=true' }}"
                                     class="w-7 h-7 rounded-full border border-white/30 object-cover">
                                <span class="text-[10px] uppercase tracking-wider font-bold">Mon Espace</span>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest mr-6 transition-colors" :class="scrolled ? 'text-slate-900' : 'text-white'">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold text-[10px] uppercase tracking-widest shadow-lg">S'inscrire</a>
                    @endauth
                </div>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 transition-colors" :class="(scrolled || mobileMenuOpen) ? 'text-slate-900' : 'text-white'">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'" style="font-size: 1.5rem;"></i>
                </button>
            </div>
        </div>

        <!-- MENU MOBILE OVERLAY -->
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 bg-white z-[105] lg:hidden flex flex-col h-screen overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-[-100%]"
             x-transition:enter-end="translate-y-0"
             @click.away="mobileMenuOpen = false">

            <!-- Navigation Items -->
            <div class="flex-1 px-3 py-20 space-y-0.5 overflow-hidden">
                <a href="/" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-home mr-2 text-blue-600"></i>Accueil
                </a>
                <a href="#cours" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-graduation-cap mr-2 text-purple-600"></i>Cours
                </a>
                <a href="{{ route('private-lessons.browse') }}" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-purple-600 transition-colors">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>Particuliers
                </a>
                <a href="{{ route('forum.index') }}" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-comments mr-2 text-green-600"></i>Forum
                </a>
                <a href="{{ route('library.index') }}" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-book mr-2 text-orange-600"></i>Bibliothèque
                </a>
                <a href="{{ route('page.show', 'a-propos') }}" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-info-circle mr-2 text-red-600"></i>À Propos
                </a>
                <a href="{{ route('page.show', 'club-mio') }}" @click="mobileMenuOpen = false" class="block text-sm font-bold py-3 px-3 rounded-lg hover:bg-slate-100 uppercase tracking-tight text-slate-900 transition-colors">
                    <i class="fas fa-users mr-2 text-indigo-600"></i>Club MIO
                </a>
            </div>

            <!-- Auth Section -->
            <div class="bg-slate-50 border-t-4 border-blue-600 px-3 py-4 space-y-3 shadow-lg">
                @auth
                    @php $isAdmin = (Auth::user()->role === 'admin' || Auth::user()->user_type === 'teacher'); @endphp
                    <a href="{{ $isAdmin ? '/admin' : route('user.dashboard') }}"
                       @click="mobileMenuOpen = false"
                       class="w-full {{ $isAdmin ? 'bg-slate-900 hover:bg-slate-800' : 'bg-blue-600 hover:bg-blue-700' }} text-white py-3.5 px-4 rounded-xl font-black block uppercase tracking-widest text-sm transition-all shadow-lg">
                       <i class="fas fa-dashboard mr-2"></i>{{ $isAdmin ? 'Panel Admin' : 'Mon Espace' }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full bg-red-600 text-white hover:bg-red-700 py-3.5 px-4 rounded-xl font-black block uppercase tracking-widest text-sm transition-all shadow-lg">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="block w-full bg-slate-900 hover:bg-slate-800 text-white py-4 px-4 rounded-xl font-black uppercase text-sm text-center transition-all shadow-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                    <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-4 rounded-xl font-black shadow-2xl uppercase text-sm text-center transition-all">
                        <i class="fas fa-user-plus mr-2"></i>S'inscrire Gratuitement
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- POINTEUR BOUTON RETOUR EN HAUT -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            x-show="scrolled" x-cloak
            x-transition:enter="transition scale-0 rotate-180"
            x-transition:enter-end="scale-100 rotate-0"
            class="fixed bottom-10 right-10 z-[90] bg-blue-600 text-white w-14 h-14 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-2 group border-4 border-white/20">
        <i class="fas fa-arrow-up text-xl group-hover:animate-bounce"></i>
    </button>

    <!-- SLIDER -->
    <section class="relative h-[85vh] md:h-screen bg-black overflow-hidden"
             x-data="{ activeSlide: 0, slidesCount: {{ $sliders->count() }}, init() { setInterval(() => { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }, 6000) } }">
        @foreach($sliders as $index => $slide)
            <div class="absolute inset-0 transition-all duration-[1500ms]" x-show="activeSlide === {{ $index }}" x-cloak
                 x-transition:enter="opacity-0 scale-110" x-transition:enter-end="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <img src="{{ asset('storage/' . $slide->image_path) }}" class="w-full h-full object-cover opacity-50">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/40"></div>
                <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white px-6">
                    <h1 class="text-4xl md:text-7xl font-black mb-4 uppercase tracking-tighter">{{ $slide->titre }}</h1>
                    <p class="text-sm md:text-xl max-w-2xl font-light opacity-80 leading-relaxed">{{ $slide->description }}</p>
                    <a href="#cours" class="mt-10 bg-blue-600 text-white px-10 py-4 rounded-full font-bold text-sm md:text-lg hover:bg-blue-500 shadow-2xl transition-all active:scale-95 uppercase tracking-widest">Découvrir les cours</a>
                </div>
            </div>
        @endforeach
    </section>

    <!-- SECTION CURSUS -->
    <section id="cours" class="py-32 px-6 max-w-7xl mx-auto">
        <div class="text-center mb-20">
            <h2 class="text-4xl font-bold text-slate-900 tracking-tighter uppercase italic">Votre Cursus</h2>
            <div class="h-1.5 w-20 bg-blue-600 mx-auto mt-4 rounded-full shadow-lg shadow-blue-200"></div>
            <p class="mt-6 text-slate-500 text-lg">Accédez aux ressources par niveau d'étude</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($semestres as $semestre)
                <a href="{{ route('semestre.show', $semestre->id) }}"
                   class="group relative h-[480px] rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-500 hover:-translate-y-4 hover:shadow-blue-200/50 block">

                    @if($semestre->image_path)
                        <img src="{{ asset('storage/' . $semestre->image_path) }}"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-900"></div>
                    @endif

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                    <div class="absolute bottom-0 p-10 w-full">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg">{{ $semestre->niveau }}</span>
                            <span class="text-white/60 text-[10px] font-bold uppercase tracking-widest italic">{{ $semestre->matieres_count }} matières</span>
                        </div>
                        <h3 class="text-3xl font-bold text-white leading-tight mb-2 tracking-tight group-hover:text-blue-400 transition-colors">{{ $semestre->nom }}</h3>

                        <p class="text-white/70 text-sm mb-6 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                            Explorez les cours, TD et ressources pour ce semestre.
                        </p>

                        <div class="flex items-center gap-2 text-blue-400 font-bold text-xs uppercase tracking-widest transition-all group-hover:gap-4">
                            Explorer le contenu <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- SECTION COURS PARTICULIERS PREMIUM -->
    <section class="py-32 px-6 w-full bg-gradient-to-b from-white to-slate-50 relative overflow-hidden">
        <!-- Fond décoratif -->
        <div class="absolute -top-40 -left-40 w-80 h-80 bg-purple-600/5 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -right-40 w-80 h-80 bg-blue-600/5 rounded-full blur-[120px]"></div>

        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20 relative z-10">
                <div class="inline-block mb-6">
                    <span class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-purple-600/40">✨ NOUVEAUTÉ</span>
                </div>
                <h2 class="text-4xl font-bold text-slate-900 tracking-tighter uppercase italic mb-4">Cours Particuliers Premium</h2>
                <div class="h-1.5 w-24 bg-gradient-to-r from-purple-600 to-blue-600 mx-auto rounded-full shadow-lg shadow-blue-200"></div>
                <p class="mt-6 text-slate-600 text-lg max-w-2xl mx-auto">Découvrez les 5 derniers cours particuliers disponibles. Des sessions de révision personnalisées avec les meilleurs enseignants</p>
            </div>

            @php
                $privateLessons = \App\Models\PrivateLesson::with('teacher', 'matiere')
                    ->active()
                    ->latest()
                    ->take(6)
                    ->get();
            @endphp

            @if($privateLessons->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-12 relative z-10 w-full">
                    @foreach($privateLessons as $lesson)
                        <div class="group relative h-[350px] md:h-[420px] lg:h-[480px] rounded-2xl md:rounded-3xl lg:rounded-[3rem] overflow-hidden shadow-xl md:shadow-2xl transition-all duration-500 hover:-translate-y-2 md:hover:-translate-y-4 hover:shadow-purple-200/50 block cursor-pointer">

                            <!-- FOND DÉGRADÉ -->
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-600 via-blue-500 to-indigo-900"></div>

                            <!-- OVERLAY -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                            <!-- BADGES EN HAUT -->
                            <div class="absolute top-4 md:top-6 lg:top-10 left-4 md:left-6 lg:left-10 right-4 md:right-6 lg:right-10 flex items-center gap-2 md:gap-3 flex-wrap">
                                <div class="min-w-[80px] md:min-w-[100px]">
                                    @if($lesson->type === 'payant' && $lesson->prix > 0)
                                        <span class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-3 md:px-4 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-lg inline-block">{{ number_format((float)$lesson->prix, 0, ',', ' ') }} F</span>
                                    @else
                                        <span class="bg-gradient-to-r from-green-500 to-emerald-500 text-white px-3 md:px-4 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-lg inline-block">GRATUIT</span>
                                    @endif
                                </div>
                                <span class="text-white/60 text-[9px] md:text-xs font-bold uppercase tracking-widest italic truncate">{{ $lesson->matiere?->nom ?? 'Cours' }}</span>
                            </div>

                            <!-- TITRE AU MILIEU -->
                            <div class="absolute top-1/2 left-4 md:left-6 lg:left-10 right-4 md:right-6 lg:right-10 -translate-y-1/2">
                                <h3 class="text-lg md:text-2xl lg:text-3xl font-bold text-white leading-tight tracking-tight group-hover:text-purple-400 transition-colors line-clamp-2 md:line-clamp-3">{{ $lesson->titre }}</h3>
                            </div>

                            <!-- CONTENU EN BAS -->
                            <div class="absolute bottom-0 p-4 md:p-6 lg:p-10 w-full">
                                <!-- INFOS AU SURVOL -->
                                <div class="mb-3 md:mb-6 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-2 md:translate-y-4 group-hover:translate-y-0">
                                    <!-- Bouton Description -->
                                    <div class="mb-2 md:mb-3" x-data="{ showDesc: false }">
                                        <button type="button" @click.prevent="showDesc = true"
                                                class="w-full flex items-center justify-between gap-2 md:gap-3 bg-white/5 hover:bg-white/10 backdrop-blur-sm rounded-lg md:rounded-xl p-2 md:p-3 border border-white/10 transition-all">
                                            <div class="flex items-center gap-1.5 md:gap-2 min-w-0">
                                                <i class="fas fa-info-circle text-blue-300 text-xs md:text-sm flex-shrink-0"></i>
                                                <span class="text-white text-[10px] md:text-xs font-bold uppercase tracking-wide truncate">Description</span>
                                            </div>
                                            <i class="fas fa-chevron-right text-white/50 text-xs flex-shrink-0"></i>
                                        </button>

                                        <!-- Popup Description -->
                                        <div x-show="showDesc" x-cloak
                                             class="fixed inset-0 z-[9999] flex items-center justify-center p-2 md:p-4 bg-black/50"
                                             @click.prevent.stop="showDesc = false">
                                            <div @click.prevent.stop class="bg-white rounded-xl md:rounded-2xl shadow-2xl max-w-2xl md:max-w-4xl w-full max-h-[90vh] md:max-h-[80vh] p-4 md:p-6 lg:p-8 flex flex-col overflow-hidden" x-transition>
                                                <div class="flex items-center justify-between mb-4 md:mb-6 pb-4 md:pb-6 border-b border-slate-200 flex-shrink-0">
                                                    <h3 class="text-lg md:text-2xl font-bold text-slate-900">Description</h3>
                                                    <button @click.prevent.stop="showDesc = false" class="text-slate-400 hover:text-slate-600 text-2xl flex-shrink-0">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="flex-1 overflow-y-auto">
                                                    <div class="text-slate-700 text-sm md:text-base leading-relaxed font-medium pr-2">
                                                        {{ $lesson->description ?? 'Aucune description disponible pour ce cours.' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Info Professeur -->
                                    <div class="flex items-center gap-2 md:gap-3 bg-white/5 backdrop-blur-sm rounded-lg md:rounded-xl p-2 md:p-3 border border-white/10 mb-2 md:mb-3">
                                        <img src="{{ $lesson->teacher?->avatar ? asset('storage/' . $lesson->teacher->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($lesson->teacher?->name ?? 'Prof') . '&background=7c3aed&color=fff' }}"
                                             class="w-8 md:w-10 h-8 md:h-10 rounded-lg object-cover border-2 border-white/30 shadow-md flex-shrink-0">
                                        <div class="min-w-0">
                                            <p class="text-white/50 text-[9px] md:text-xs uppercase tracking-wide font-semibold">Enseignant</p>
                                            <p class="text-white font-bold text-xs md:text-sm truncate">{{ $lesson->teacher?->name ?? 'Professeur' }}</p>
                                        </div>
                                    </div>

                                    <!-- Badges d'info -->
                                    <div class="flex items-center gap-1 md:gap-2 flex-wrap">
                                        <div class="flex items-center gap-1 md:gap-1.5 bg-white/10 backdrop-blur-sm px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/10">
                                            <i class="fas fa-clock text-blue-300 text-[9px] md:text-xs"></i>
                                            <span class="text-white text-[9px] md:text-xs font-bold">{{ $lesson->duree_minutes ?? 60 }}m</span>
                                        </div>
                                        <div class="flex items-center gap-1 md:gap-1.5 bg-white/10 backdrop-blur-sm px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/10">
                                            <i class="fas fa-users text-purple-300 text-[9px] md:text-xs"></i>
                                            <span class="text-white text-[9px] md:text-xs font-bold">{{ $lesson->places_max ?? '∞' }}</span>
                                        </div>
                                        <div class="flex items-center gap-1 md:gap-1.5 bg-white/10 backdrop-blur-sm px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/10">
                                            <i class="fas fa-calendar text-green-300 text-[9px] md:text-xs"></i>
                                            <span class="text-white text-[9px] md:text-xs font-bold">{{ \Carbon\Carbon::parse($lesson->start_date)->format('d M') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- CTA BOUTON RÉSERVER -->
                                <a href="{{ route('private-lessons.show', $lesson->id) }}"
                                   class="inline-flex items-center gap-1 md:gap-2 text-purple-400 font-bold text-[10px] md:text-xs uppercase tracking-widest transition-all hover:gap-3 hover:text-purple-300">
                                    Réserver <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- BOUTON VOIR TOUS -->
                <div class="text-center mt-16 relative z-10">
                    <a href="{{ route('private-lessons.browse') }}"
                       class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white px-10 py-4 rounded-full font-bold text-sm md:text-base hover:shadow-2xl hover:shadow-purple-600/50 transition-all transform hover:-translate-y-1 uppercase tracking-widest shadow-xl">
                        Voir tous les cours particuliers
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-16 relative z-10">
                    <p class="text-slate-600 text-lg mb-6">Aucun cours particulier disponible pour le moment.</p>
                    <a href="{{ route('private-lessons.browse') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition">
                        Consulter les autres cours
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- SECTION AVIS -->
    <section class="py-32 bg-[#0f172a] relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px] -mr-48 -mt-48"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <h2 class="text-3xl font-bold text-center text-white mb-16 uppercase tracking-widest">L'avis de nos étudiants</h2>

            <div class="max-w-xl mx-auto bg-white/5 backdrop-blur-xl p-10 rounded-[3rem] border border-white/10 shadow-2xl mb-20">
                @if(session('success_review'))
                    <div class="mb-6 p-4 bg-blue-600 text-white rounded-2xl font-bold text-center animate-bounce">{{ session('success_review') }}</div>
                @endif
                <form action="{{ route('avis.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @auth
                        <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}" class="w-14 h-14 rounded-2xl object-cover">
                            <div><p class="text-[10px] font-bold text-blue-400 uppercase">Connecté en tant que</p><p class="text-xl font-bold text-white tracking-tight">{{ Auth::user()->name }}</p></div>
                        </div>
                    @else
                        <input type="text" name="nom" placeholder="Votre Prénom" class="w-full rounded-2xl bg-white/5 border-white/10 p-5 text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    @endauth
                    <textarea name="message" rows="3" placeholder="Votre message pour la communauté..." class="w-full rounded-2xl bg-white/5 border-white/10 p-5 text-white placeholder-slate-500 focus:ring-2 focus:ring-blue-500 outline-none" required></textarea>

                    <div class="flex flex-col sm:flex-row gap-4 items-center">
                        <select name="note" class="w-full sm:w-auto bg-white/5 border-white/10 text-white p-4 rounded-2xl font-bold focus:ring-0">
                            <option value="5" class="bg-slate-900">⭐⭐⭐⭐⭐ Excellent</option>
                            <option value="4" class="bg-slate-900">⭐⭐⭐⭐ Très bien</option>
                            <option value="3" class="bg-slate-900">⭐⭐⭐ Bien</option>
                        </select>
                        <button type="submit" class="w-full flex-1 bg-blue-600 text-white font-bold py-5 rounded-[2rem] hover:bg-blue-500 transition shadow-xl shadow-blue-900/40">PUBLIER MON AVIS</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
                @foreach(\App\Models\Review::latest()->take(3)->get() as $review)
                    <div class="bg-white/5 border border-white/10 p-8 rounded-[2.5rem] hover:bg-white/10 transition">
                        <div class="text-blue-500 mb-4 flex justify-center md:justify-start">
                            @for($i=0; $i<$review->note; $i++) <i class="fas fa-star text-xs"></i> @endfor
                        </div>
                        <p class="text-slate-300 italic mb-6 leading-relaxed">"{{ $review->message }}"</p>
                        <p class="text-white font-bold text-xs uppercase tracking-widest flex items-center justify-center md:justify-start gap-3">
                            <span class="w-6 h-px bg-blue-500"></span> {{ $review->nom }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-950 text-white py-20 px-8 border-t border-white/5">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-16 border-b border-white/5 pb-16">
            <div class="space-y-6 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <x-application-logo class="w-10 h-10" />
                    <span class="font-bold text-xl tracking-tighter uppercase tracking-widest">MIO <span class="font-light opacity-50">RESSOURCES</span></span>
                </div>
                <p class="text-slate-500 text-sm leading-relaxed max-w-sm mx-auto md:mx-0">La plateforme collaborative de référence pour les étudiants de l'Université Iba Der Thiam.</p>
            </div>
            <div>
                <h4 class="font-bold text-[10px] uppercase tracking-[0.4em] text-blue-500 mb-8 text-center md:text-left">Contact Officiel</h4>
                <div class="space-y-4 text-slate-400 text-sm text-center md:text-left">
                    <p class="flex items-center justify-center md:justify-start gap-3"><i class="fas fa-envelope text-blue-500"></i> {{ $globalSettings['contact_email'] ?? 'contact@mio.sn' }}</p>
                    <p class="flex items-center justify-center md:justify-start gap-3"><i class="fas fa-phone text-blue-500"></i> {{ $globalSettings['contact_phone'] ?? '' }}</p>
                </div>
            </div>
            <div class="text-center md:text-left">
                <h4 class="font-bold text-[10px] uppercase tracking-[0.4em] text-blue-500 mb-8">Réseaux Sociaux</h4>
                <div class="flex justify-center md:justify-start gap-5">
                    @if(isset($globalSettings['social_linkedin']))
                        <a href="{{ $globalSettings['social_linkedin'] }}" target="_blank" class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fab fa-linkedin-in text-xl"></i></a>
                    @endif
                    @if(isset($globalSettings['social_github']))
                        <a href="{{ $globalSettings['social_github'] }}" target="_blank" class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center text-white hover:bg-blue-600 transition-all shadow-lg"><i class="fab fa-github text-xl"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="text-center mt-12 text-slate-700 text-[10px] font-bold uppercase tracking-[0.5em]">
            &copy; {{ date('Y') }} MIO Ressources &bull; Excellence & Partage
        </div>
    </footer>

</body>
</html>
