<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours Particuliers - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function searchData() {
            return {
                showSuggestions: false,
                showAutocomplete: false,
                query: '{{ request('q') }}',
                matieres: @json($matieres->pluck('nom')),
                get filteredMatieres() {
                    if (this.query.length < 2) return [];
                    const q = this.query.toLowerCase();
                    return this.matieres.filter(function(m) {
                        return m.toLowerCase().includes(q);
                    }).slice(0, 8);
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        /* Fallback dark mode pour harmoniser les sections non migrées */
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white, html.dark .bg-\[\#f8fafc\], html.dark .bg-slate-50 { background-color: #0f172a !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400 { color: #cbd5e1 !important; }
        html.dark .border-slate-50, html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">

    <!-- NAVBAR -->
    @include('layouts.navbar')

    <!-- HERO SECTION -->
    <section class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-black mb-4 uppercase tracking-tight">💼 Cours Particuliers</h1>
            <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto">
                Réservez des cours personnalisés avec nos enseignants experts. Paiement sécurisé et sessions en ligne.
            </p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto py-8 md:py-12 px-4 md:px-6">

        <!-- BARRE DE RECHERCHE -->
        <div class="bg-white rounded-lg md:rounded-[2rem] p-3 md:p-6 lg:p-8 shadow-lg md:shadow-xl border border-slate-100 mb-6 md:mb-8"
             x-data="searchData()">
            <form method="GET" action="{{ route('private-lessons.browse') }}">
                <div class="relative">
                    <!-- Input de recherche -->
                    <div class="relative flex items-center gap-2 md:gap-3">
                        <i class="fas fa-search absolute left-3 md:left-6 text-slate-400 text-sm md:text-xl flex-shrink-0"></i>
                        <input
                            type="text"
                            name="q"
                            x-model="query"
                            @focus="showSuggestions = true; showAutocomplete = true"
                            @input="showAutocomplete = true"
                            @click.away="showSuggestions = false; showAutocomplete = false"
                            value="{{ request('q') }}"
                            placeholder="Rechercher un cours..."
                            autocomplete="off"
                            class="w-full pl-9 md:pl-16 pr-2 md:pr-32 py-2.5 md:py-5 text-xs md:text-base lg:text-lg bg-slate-50 border-2 border-slate-200 rounded-lg md:rounded-2xl focus:border-blue-500 focus:bg-white focus:outline-none transition-all">
                        <button type="submit" class="absolute right-2 md:right-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-3 md:px-6 lg:px-8 py-2 md:py-3 rounded-lg md:rounded-xl font-bold text-[10px] md:text-xs lg:text-sm uppercase shadow-lg hover:shadow-xl transition-all flex-shrink-0">
                            <i class="fas fa-search mr-0 md:mr-2 hidden md:inline"></i><span class="hidden md:inline">Rechercher</span><span class="md:hidden">OK</span>
                        </button>
                    </div>

                    <!-- Autocomplétion des matières -->
                    <div x-show="showAutocomplete && filteredMatieres.length > 0 && query.length >= 2"
                         x-transition
                         class="absolute top-full left-0 right-0 mt-2 md:mt-3 bg-white rounded-lg md:rounded-2xl shadow-xl md:shadow-2xl border border-slate-200 overflow-hidden z-50">
                        <div class="p-2 md:p-3 bg-gradient-to-r from-blue-50 to-purple-50 border-b border-slate-200">
                            <p class="text-[9px] md:text-xs font-bold text-slate-600 uppercase flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-blue-600"></i>
                                Matières
                            </p>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="matiere in filteredMatieres" :key="matiere">
                                <button
                                    type="button"
                                    @click="query = matiere; showAutocomplete = false; $el.closest('form').submit()"
                                    class="w-full text-left px-3 md:px-6 py-2 md:py-3 hover:bg-blue-50 transition-colors flex items-center gap-2 md:gap-3 border-b border-slate-100 last:border-0 group">
                                    <div class="w-8 md:w-10 h-8 md:h-10 rounded-lg md:rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-book text-white text-xs md:text-sm"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-xs md:text-sm truncate" x-text="matiere"></p>
                                        <p class="text-[9px] md:text-xs text-slate-500">Cliquez</p>
                                    </div>
                                    <i class="fas fa-arrow-right text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition-all flex-shrink-0 text-xs"></i>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Suggestions et aide -->
                    <div x-show="showSuggestions && query.length === 0"
                         x-transition
                         class="absolute top-full left-0 right-0 mt-2 md:mt-3 bg-white rounded-lg md:rounded-2xl shadow-xl md:shadow-2xl border border-slate-200 p-3 md:p-6 z-50">
                        <h4 class="text-xs md:text-sm font-bold text-slate-700 uppercase mb-3 md:mb-4 flex items-center gap-2">
                            <i class="fas fa-lightbulb text-yellow-500"></i>
                            Suggestions
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-3">
                            <!-- Suggestions par matière -->
                            <div>
                                <p class="text-[9px] md:text-xs font-bold text-slate-500 uppercase mb-2">Matières</p>
                                <div class="flex flex-wrap gap-1 md:gap-2">
                                    @foreach($matieres->take(4) as $matiere)
                                        <a href="{{ route('private-lessons.browse', ['q' => $matiere->nom]) }}"
                                           class="inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-2 md:px-3 py-1 md:py-2 rounded-lg text-[9px] md:text-xs font-bold transition-all">
                                            <i class="fas fa-book hidden md:inline"></i>
                                            {{ $matiere->nom }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Suggestions rapides -->
                            <div>
                                <p class="text-[9px] md:text-xs font-bold text-slate-500 uppercase mb-2">Populaires</p>
                                <div class="flex flex-wrap gap-1 md:gap-2">
                                    <a href="{{ route('private-lessons.browse', ['q' => 'gratuit']) }}"
                                       class="inline-flex items-center gap-1 bg-green-50 hover:bg-green-100 text-green-700 px-2 md:px-3 py-1 md:py-2 rounded-lg text-[9px] md:text-xs font-bold transition-all">
                                        <i class="fas fa-gift hidden md:inline"></i>
                                        Gratuit
                                    </a>
                                    <a href="{{ route('private-lessons.browse', ['q' => '1 heure']) }}"
                                       class="inline-flex items-center gap-1 bg-purple-50 hover:bg-purple-100 text-purple-700 px-2 md:px-3 py-1 md:py-2 rounded-lg text-[9px] md:text-xs font-bold transition-all">
                                        <i class="fas fa-clock hidden md:inline"></i>
                                        1h
                                    </a>
                                    <a href="{{ route('private-lessons.browse', ['q' => 'disponible']) }}"
                                       class="inline-flex items-center gap-1 bg-orange-50 hover:bg-orange-100 text-orange-700 px-2 md:px-3 py-1 md:py-2 rounded-lg text-[9px] md:text-xs font-bold transition-all">
                                        <i class="fas fa-calendar-check hidden md:inline"></i>
                                        Dispo
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Filtres actifs -->
            @if(request('q'))
                <div class="mt-4 flex items-center gap-3 flex-wrap">
                    <span class="text-sm font-bold text-slate-600">Recherche active :</span>
                    <div class="inline-flex items-center gap-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold">
                        <i class="fas fa-search"></i>
                        "{{ request('q') }}"
                        <a href="{{ route('private-lessons.browse') }}" class="ml-2 text-blue-900 hover:text-blue-600">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- RÉSULTATS -->
        @if($lessons->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 lg:gap-12 mb-8">
                @foreach($lessons as $lesson)
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
                                    <img src="{{ $lesson->teacher->avatar ? asset('storage/'.$lesson->teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($lesson->teacher->name).'&background=7c3aed&color=fff' }}"
                                         class="w-8 md:w-10 h-8 md:h-10 rounded-lg object-cover border-2 border-white/30 shadow-md flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="text-white/50 text-[9px] md:text-xs uppercase tracking-wide font-semibold">Enseignant</p>
                                        <p class="text-white font-bold text-xs md:text-sm truncate">{{ $lesson->teacher->name }}</p>
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
                                        <span class="text-white text-[9px] md:text-xs font-bold">
                                            {{ $lesson->enrollments->where('payment_status', 'paid')->count() }}/{{ $lesson->places_max }}
                                        </span>
                                    </div>
                                    @if(is_array($lesson->disponibilites) && count($lesson->disponibilites) > 0)
                                        <div class="flex items-center gap-1 md:gap-1.5 bg-white/10 backdrop-blur-sm px-2 md:px-3 py-1 md:py-1.5 rounded-lg border border-white/10">
                                            <i class="fas fa-calendar text-green-300 text-[9px] md:text-xs"></i>
                                            <span class="text-white text-[9px] md:text-xs font-bold">{{ count($lesson->disponibilites) }} dispos</span>
                                        </div>
                                    @endif
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

            <!-- PAGINATION -->
            <div class="mt-8">
                {{ $lessons->links() }}
            </div>

        @else
            <div class="bg-white rounded-[2rem] p-12 text-center shadow-xl">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-2xl font-black text-slate-800 mb-2">Aucun cours trouvé</h3>
                <p class="text-slate-600 mb-6">Essayez de modifier vos critères de recherche</p>
                <a href="{{ route('private-lessons.browse') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-sm uppercase shadow-lg hover:bg-blue-700 transition">
                    Voir tous les cours
                </a>
            </div>
        @endif

    </main>

    @include('layouts.footer')

</body>
</html>
