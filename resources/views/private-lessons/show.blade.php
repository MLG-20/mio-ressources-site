<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $lesson->titre }} - Cours Particuliers</title>
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

    <main class="max-w-6xl mx-auto py-8 md:py-12 px-4 md:px-6">

        <!-- BOUTON RETOUR -->
        <a href="{{ route('private-lessons.browse') }}" class="inline-flex items-center gap-2 text-blue-600 font-bold text-xs md:text-sm mb-6 hover:text-blue-700 transition">
            <i class="fas fa-arrow-left"></i> Retour aux cours
        </a>

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl" role="alert">
                <p class="font-bold text-sm md:text-base">⚠ Erreur</p>
                <ul class="list-disc list-inside text-xs md:text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

            <!-- COLONNE PRINCIPALE -->
            <div class="lg:col-span-2 space-y-6">

                <!-- EN-TÊTE DU COURS -->
                <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white rounded-2xl md:rounded-[2rem] p-6 md:p-8 shadow-xl">
                    <div class="flex flex-col sm:flex-row items-start gap-4 mb-4">
                        <img src="{{ $lesson->teacher->avatar ? asset('storage/'.$lesson->teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($lesson->teacher->name) }}"
                            class="w-14 h-14 md:w-16 md:h-16 rounded-xl md:rounded-2xl object-cover border-4 border-white/30 flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl md:text-3xl font-black mb-2 break-words">{{ $lesson->titre }}</h1>
                            <p class="opacity-90 font-bold text-sm md:text-base">Par {{ $lesson->teacher->name }}</p>
                            @if($lesson->matiere)
                                <span class="inline-block bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase mt-2">
                                    📘 {{ $lesson->matiere->nom }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- DESCRIPTION -->
                <div class="bg-white rounded-2xl md:rounded-[2rem] p-6 md:p-8 shadow-xl border border-slate-100">
                    <h2 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-4">📄 Description</h2>
                    <p class="text-slate-700 leading-relaxed whitespace-pre-line text-sm md:text-base">{{ $lesson->description }}</p>
                </div>

                <!-- DISPONIBILITÉS -->
                @if(is_array($lesson->disponibilites) && count($lesson->disponibilites) > 0)
                    <div class="bg-white rounded-2xl md:rounded-[2rem] p-6 md:p-8 shadow-xl border border-slate-100">
                        <h2 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-4">📅 Disponibilités</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                            @foreach($lesson->disponibilites as $dispo)
                                <div class="bg-blue-50 rounded-xl p-4 border-l-4 border-blue-600">
                                    <p class="font-black text-blue-900 text-sm md:text-base">{{ $dispo['jour'] ?? '' }}</p>
                                    <p class="text-blue-700 text-xs md:text-sm">{{ $dispo['horaires'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- SIDEBAR -->
            <div class="space-y-6">

                <!-- CARTE DE RÉSERVATION -->
                <div class="bg-white rounded-2xl md:rounded-[2rem] p-6 md:p-8 shadow-xl border border-slate-100 sticky top-6">

                    <!-- PRIX -->
                    <div class="text-center mb-6 pb-6 border-b-2 border-slate-100">
                        @if($lesson->type === 'tutoriel')
                            <p class="text-xs md:text-sm text-slate-500 font-bold uppercase mb-2">💚 Type</p>
                            <p class="text-2xl md:text-3xl font-black text-emerald-600 mb-2">GRATUIT</p>
                            <p class="text-xs md:text-sm text-slate-600">Tutoriel sans engagement</p>
                        @else
                            <p class="text-xs md:text-sm text-slate-500 font-bold uppercase mb-2">Prix</p>
                            <p class="text-3xl md:text-4xl font-black text-slate-900">{{ number_format($lesson->prix, 0, ',', ' ') }}</p>
                            <p class="text-xs md:text-sm text-slate-500 font-bold">FCFA</p>
                        @endif
                    </div>

                    <!-- INFOS -->
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-600 font-bold text-xs md:text-sm">⏱ Durée</span>
                            <span class="font-black text-slate-900 text-sm md:text-base">{{ $lesson->duree_minutes }} min</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100">
                            <span class="text-slate-600 font-bold text-xs md:text-sm">👥 Places</span>
                            <span class="font-black text-slate-900 text-sm md:text-base">{{ $placesRestantes }} / {{ $lesson->places_max }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-slate-600 font-bold text-xs md:text-sm">✓ Statut</span>
                            @if($lesson->statut === 'actif' && $placesRestantes > 0)
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Disponible</span>
                            @elseif($placesRestantes <= 0)
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase">Complet</span>
                            @else
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold uppercase">Inactif</span>
                            @endif
                        </div>
                    </div>

                    <!-- BOUTON ACCÈS DIRECT -->
                    @auth
                        @if($lesson->statut === 'actif' && $placesRestantes > 0)
                            <form action="{{ route('private-lessons.access', $lesson->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full {{ $lesson->type === 'tutoriel' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white py-3 md:py-4 rounded-lg md:rounded-2xl font-black uppercase text-xs md:text-sm shadow-lg transition flex items-center justify-center gap-2">
                                    @if($lesson->type === 'tutoriel')
                                        <i class="fas fa-play-circle"></i>
                                        <span>💚 Accéder au tutoriel</span>
                                    @else
                                        <i class="fas fa-credit-card"></i>
                                        <span>💳 Payer et accéder</span>
                                    @endif
                                </button>
                            </form>
                        @else
                            <button disabled class="w-full bg-slate-300 text-slate-600 py-3 md:py-4 rounded-lg md:rounded-2xl font-black uppercase text-xs md:text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                <i class="fas fa-{{ $placesRestantes <= 0 ? 'lock' : 'pause' }}"></i>
                                <span>{{ $placesRestantes <= 0 ? '🔒 Complet' : '⏸ Indisponible' }}</span>
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-blue-600 text-white py-3 md:py-4 rounded-lg md:rounded-2xl font-black uppercase text-xs md:text-sm shadow-lg hover:bg-blue-700 transition text-center flex items-center justify-center gap-2">
                            <i class="fas fa-lock"></i>
                            🔐 Connexion requise
                        </a>
                    @endauth

                    @if($lesson->type === 'tutoriel')
                        <p class="text-xs text-slate-500 text-center mt-4">
                            💚 Accès gratuit et immédiat au tutoriel
                        </p>
                    @else
                        <p class="text-xs text-slate-500 text-center mt-4">
                            💳 Paiement sécurisé via Paytech
                        </p>
                    @endif
                </div>

            </div>

        </div>

    </main>

    @include('layouts.footer')

</body>
</html>
