<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours Particuliers - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900">

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

        <!-- FILTRES -->
        <div class="bg-white rounded-[2rem] p-4 md:p-8 shadow-xl border border-slate-100 mb-8">
            <form method="GET" action="{{ route('private-lessons.browse') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <!-- MATIÈRE -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Matière</label>
                    <select name="matiere_id" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-xs md:text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $matiere)
                            <option value="{{ $matiere->id }}" {{ request('matiere_id') == $matiere->id ? 'selected' : '' }}>
                                {{ $matiere->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PRIX MIN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Prix Min (FCFA)</label>
                    <input type="number" name="prix_min" value="{{ request('prix_min') }}" min="0" step="1000"
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-xs md:text-sm focus:border-blue-500 focus:outline-none"
                        placeholder="0">
                </div>

                <!-- PRIX MAX -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Prix Max (FCFA)</label>
                    <input type="number" name="prix_max" value="{{ request('prix_max') }}" min="0" step="1000"
                        class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-xs md:text-sm focus:border-blue-500 focus:outline-none"
                        placeholder="50000">
                </div>

                <!-- DURÉE -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Durée</label>
                    <select name="duree" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-xs md:text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Toutes les durées</option>
                        <option value="30" {{ request('duree') == 30 ? 'selected' : '' }}>30 minutes</option>
                        <option value="60" {{ request('duree') == 60 ? 'selected' : '' }}>1 heure</option>
                        <option value="90" {{ request('duree') == 90 ? 'selected' : '' }}>1h30</option>
                        <option value="120" {{ request('duree') == 120 ? 'selected' : '' }}>2 heures</option>
                    </select>
                </div>

                <!-- TYPE DE COURS -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2 uppercase">Type</label>
                    <select name="type" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl p-3 font-bold text-xs md:text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Tous les types</option>
                        <option value="payant" {{ request('type') == 'payant' ? 'selected' : '' }}>Cours payants</option>
                        <option value="tutoriel" {{ request('type') == 'tutoriel' ? 'selected' : '' }}>Tutoriels gratuits</option>
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-4 flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold text-xs md:text-sm uppercase shadow-lg hover:bg-blue-700 transition">
                        🔍 Rechercher
                    </button>
                    <a href="{{ route('private-lessons.browse') }}" class="flex-1 bg-slate-200 text-slate-700 px-6 py-3 rounded-xl font-bold text-xs md:text-sm uppercase hover:bg-slate-300 transition text-center">
                        ✗ Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- RÉSULTATS -->
        @if($lessons->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($lessons as $lesson)
                    <div class="bg-white rounded-[2rem] overflow-hidden shadow-xl border border-slate-100 hover:shadow-2xl transition group flex flex-col h-full">

                        <!-- EN-TÊTE -->
                        <div class="bg-gradient-to-br from-blue-600 to-purple-600 p-6 text-white">
                            <h3 class="font-black text-xl mb-2 line-clamp-2">{{ $lesson->titre }}</h3>
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ $lesson->teacher->avatar ? asset('storage/'.$lesson->teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($lesson->teacher->name) }}"
                                    class="w-8 h-8 rounded-lg object-cover">
                                <span class="text-sm font-bold opacity-90">{{ $lesson->teacher->name }}</span>
                            </div>
                            @if($lesson->matiere)
                                <span class="inline-block bg-white/20 px-3 py-1 rounded-full text-xs font-bold uppercase">
                                    📘 {{ $lesson->matiere->nom }}
                                </span>
                            @endif
                        </div>

                        <!-- CONTENU -->
                        <div class="p-6">
                            <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ $lesson->description }}</p>

                            <!-- INFOS -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-amber-600 font-black">💰</span>
                                    @if($lesson->type === 'tutoriel')
                                        <span class="font-bold text-emerald-600 text-base">🎓 GRATUIT</span>
                                    @else
                                        <span class="font-bold text-slate-800">{{ number_format($lesson->prix, 0, ',', ' ') }} FCFA</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-purple-600 font-black">⏱</span>
                                    <span class="font-bold text-slate-800">{{ $lesson->duree_minutes }} minutes</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-blue-600 font-black">👥</span>
                                    <span class="font-bold text-slate-800">
                                        {{ $lesson->enrollments->where('payment_status', 'paid')->count() }} / {{ $lesson->places_max }} places
                                    </span>
                                </div>
                            </div>

                            <!-- DISPONIBILITÉS -->
                            @if(is_array($lesson->disponibilites) && count($lesson->disponibilites) > 0)
                                <div class="mb-4">
                                    <p class="text-xs font-bold text-slate-500 uppercase mb-2">Disponibilités :</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach(array_slice($lesson->disponibilites, 0, 3) as $dispo)
                                            <span class="inline-block bg-blue-50 text-blue-700 px-2 py-1 rounded-lg text-[10px] font-bold">
                                                {{ $dispo['jour'] ?? '' }}
                                            </span>
                                        @endforeach
                                        @if(count($lesson->disponibilites) > 3)
                                            <span class="inline-block bg-slate-100 text-slate-600 px-2 py-1 rounded-lg text-[10px] font-bold">
                                                +{{ count($lesson->disponibilites) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- BOUTON -->
                            <a href="{{ route('private-lessons.show', $lesson->id) }}"
                                class="block w-full bg-blue-600 text-white py-3 rounded-xl font-bold text-sm uppercase text-center shadow-lg hover:bg-blue-700 transition group-hover:scale-105">
                                📚 Réserver
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
