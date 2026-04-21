<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Bibliothèque Numérique - MIO</title>

    <!-- Scripts & Polices -->
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
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }

        /* Personnalisation du scroll à droite */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }

        /* Style pour les images de l'éditeur riche */
        .prose img { border-radius: 1.5rem; margin: 2rem 0; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }

        /* Fallback global pour harmoniser les pages existantes */
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white { background-color: #0f172a !important; }
        html.dark .bg-slate-50, html.dark .bg-\[\#f8fafc\] { background-color: #020617 !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400 { color: #cbd5e1 !important; }
        html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900 overflow-x-hidden transition-colors duration-300"
      x-data="{ scrolled: false }"
      @scroll.window="scrolled = (window.pageYOffset > 200)">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <!-- BOUTON RETOUR EN HAUT (LE POINTEUR) -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            x-show="scrolled" x-cloak
            x-transition:enter="transition scale-0 rotate-180 duration-300"
            x-transition:enter-end="scale-100 rotate-0"
            class="fixed bottom-6 md:bottom-8 right-4 md:right-8 z-[90] bg-blue-600 text-white w-12 md:w-14 h-12 md:h-14 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-2 group border-4 border-white/20">
        <i class="fas fa-arrow-up text-lg md:text-xl group-hover:animate-bounce"></i>
    </button>

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 transition-colors duration-300">
        <a href="/" class="flex items-center gap-2 group">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="hidden sm:inline font-black text-slate-800 dark:text-slate-100 tracking-tight uppercase text-xs md:text-base">Bibliothèque</span>
        </a>
        <a href="/" class="text-slate-500 dark:text-slate-300 hover:text-blue-600 font-bold transition flex items-center gap-1 md:gap-2 text-xs md:text-sm uppercase tracking-widest">
            <i class="fas fa-home"></i> <span class="hidden md:inline">Accueil</span>
        </a>
    </nav>

    <header class="py-12 md:py-24 bg-slate-900 text-white text-center relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 md:w-96 h-32 md:h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-24 md:w-64 h-24 md:h-64 bg-indigo-600/10 rounded-full blur-3xl"></div>

        <h1 class="relative z-10 text-2xl md:text-7xl font-black tracking-tighter uppercase leading-none mb-2 md:mb-4 px-4">Savoir & Recherche</h1>
        <p class="relative z-10 text-blue-400 font-bold tracking-[0.3em] uppercase text-[10px] md:text-xs px-4">Accès aux mémoires et ouvrages de référence</p>
    </header>

    <main class="max-w-7xl mx-auto py-8 md:py-20 px-4 md:px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-10">
            @forelse($publications as $pub)
                <!-- Chaque document gère sa propre fenêtre de description -->
                <div x-data="{ showDesc: false }" class="h-full">

                    <div class="group bg-white rounded-xl md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 transition-all duration-500 hover:-translate-y-3 flex flex-col h-full">

                        <!-- Couverture -->
                        <div class="h-48 md:h-72 relative bg-slate-100 overflow-hidden">
                            <img src="{{ $pub->cover_image ? asset('storage/'.$pub->cover_image) : 'https://placehold.co/400x600/e2e8f0/1e293b?text=Livre' }}"
                                 class="w-full h-full object-cover transition transform duration-1000 group-hover:scale-110">

                            <div class="absolute top-3 md:top-4 left-3 md:left-4">
                                <span class="bg-slate-900/80 backdrop-blur text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[8px] md:text-[10px] font-black uppercase tracking-widest border border-white/10">{{ $pub->type }}</span>
                            </div>

                            @if($pub->is_premium)
                                <div class="absolute bottom-3 md:bottom-4 right-3 md:right-4 bg-amber-400 text-slate-900 px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[8px] md:text-[10px] font-black uppercase shadow-lg">{{ $pub->price }} F</div>
                            @else
                                <div class="absolute bottom-3 md:bottom-4 right-3 md:right-4 bg-green-500 text-white px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[8px] md:text-[10px] font-black uppercase shadow-lg">Gratuit</div>
                            @endif
                        </div>

                        <!-- Contenu de la Carte -->
                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="font-black text-slate-800 text-xl leading-tight mb-4 group-hover:text-blue-600 transition-colors line-clamp-2">
                                {{ $pub->titre }}
                            </h3>

                            <!-- BOUTON POUR OUVRIR LE POPUP -->
                            <button @click="showDesc = true" class="text-blue-600 text-xs font-black uppercase tracking-widest flex items-center gap-2 hover:gap-3 transition-all mb-6">
                                <i class="fas fa-align-left"></i> Lire le résumé
                            </button>

                            <div class="flex items-center gap-3 mb-6 mt-auto pt-6 border-t border-slate-50">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xs overflow-hidden border border-slate-200 shadow-inner">
                                    @if($pub->user->avatar)
                                        <img src="{{ asset('storage/'.$pub->user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user-tie"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ $pub->user->name }}</span>
                                    <span class="text-[8px] font-black text-blue-500 uppercase tracking-widest">{{ $pub->user->specialty ?? 'Enseignant' }}</span>
                                </div>
                            </div>

                           <!-- SYSTEME DE NOTATION HYBRIDE -->
<div class="mb-6">
    <!-- 1. Affichage Public (Pour tout le monde) -->
    <div class="flex items-center gap-2 mb-2">
        <div class="flex text-yellow-400 text-xs">
            @php $avg = round($pub->ratings_avg_stars ?? 0); @endphp
            @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= $avg ? '' : 'text-slate-200' }}"></i>
            @endfor
        </div>
        <span class="text-[10px] font-bold text-slate-400">({{ $pub->ratings_count }} avis)</span>
    </div>

    <!-- 2. Formulaire de Vote (Seulement pour les membres) -->
    @auth
        <x-rate-item :itemId="$pub->id" type="publication" />
    @else
        <p class="text-[9px] text-slate-400 italic">
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Connectez-vous</a> pour donner votre avis.
        </p>
    @endauth
</div>

                            <!-- Bouton Action -->
                            @if($pub->is_premium)
                                <a href="{{ route('book.checkout', $pub->id) }}" class="block w-full bg-slate-900 text-white text-center py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-blue-600 transition-all shadow-lg active:scale-95">
                                    Acheter l'ouvrage
                                </a>
                            @else
                                <a href="{{ asset('storage/'.$pub->file_path) }}" target="_blank" class="block w-full bg-blue-50 text-blue-600 text-center py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-blue-600 hover:text-white transition-all active:scale-95">
                                    Lire maintenant
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- LE POPUP (MODAL) DE DESCRIPTION -->
                    <div x-show="showDesc" x-cloak
                         class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-slate-900/80 backdrop-blur-md"
                         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                        <div @click.away="showDesc = false"
                             class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-300">

                            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">À propos de l'ouvrage</h2>
                                <button @click="showDesc = false" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 transition-all shadow-sm">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>

                            <div class="p-10 max-h-[60vh] overflow-y-auto">
                                <div class="flex flex-col md:flex-row items-center gap-8 mb-8 text-center md:text-left">
                                    <img src="{{ $pub->cover_image ? asset('storage/'.$pub->cover_image) : 'https://placehold.co/200x300' }}"
                                         class="w-32 h-44 object-cover rounded-2xl shadow-2xl border-4 border-slate-50">
                                    <div>
                                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2">{{ $pub->titre }}</h3>
                                        <p class="text-blue-600 font-bold text-sm uppercase">{{ $pub->user->name }} &bull; {{ $pub->type }}</p>
                                    </div>
                                </div>
                                <div class="h-px w-full bg-slate-100 mb-8"></div>
                                <div class="text-slate-600 text-lg leading-relaxed font-medium">
                                    {!! nl2br(e($pub->description)) !!}
                                </div>
                            </div>

                            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                                <button @click="showDesc = false" class="px-10 py-4 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-blue-600 transition-all">
                                    Fermer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-32 bg-white rounded-[3rem] border border-dashed border-slate-200">
                    <i class="fas fa-book-reader text-6xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">La bibliothèque est en cours de remplissage...</p>
                </div>
            @endforelse
        </div>
    </main>

    @include('layouts.footer')

</body>
@include('components.theme-toggle-script')
</html>
