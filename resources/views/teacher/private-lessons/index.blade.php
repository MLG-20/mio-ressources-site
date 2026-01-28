<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Cours Particuliers - MIO</title>
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
    <nav class="bg-slate-900 text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="{{ route('teacher.dashboard') }}">
            <div class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="hidden sm:inline font-black uppercase tracking-tighter text-xs md:text-base">Cours Particuliers</span>
            </div>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            <div class="bg-white/10 px-3 md:px-4 py-2 rounded-2xl flex items-center gap-2 md:gap-3">
                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover">
                <span class="text-xs md:text-sm font-bold hidden md:inline truncate">{{ Auth::user()->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}"> @csrf
                <button type="submit" class="bg-red-500 text-white px-3 md:px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg">Déco.</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- HEADER AVEC STATS -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h1 class="text-2xl md:text-4xl font-black text-slate-800">💼 Mes Cours Particuliers</h1>
                <a href="{{ route('teacher.private-lessons.create') }}" class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-xl md:rounded-2xl font-bold text-xs md:text-sm uppercase shadow-lg hover:bg-blue-700 transition text-center">
                    ➕ Créer un cours
                </a>
            </div>

            <!-- STATS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-blue-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Cours actifs</span>
                    <span class="text-2xl md:text-4xl font-black text-slate-800">{{ $lessons->where('statut', 'actif')->count() }}</span>
                </div>
                <div class="bg-slate-900 p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-amber-500 text-white">
                    <span class="text-[10px] font-black text-amber-500 uppercase mb-2 block">Revenus totaux</span>
                    <span class="text-xl md:text-3xl font-black line-clamp-1">{{ number_format($totalRevenue, 0, ',', ' ') }} F</span>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-purple-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Étudiants inscrits</span>
                    <span class="text-2xl md:text-4xl font-black text-purple-600">{{ $lessons->sum('enrollments_paid_count') }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl" role="alert">
                <p class="font-bold">✓ Succès</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl" role="alert">
                <p class="font-bold">⚠ Erreur</p>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- LISTE DES COURS -->
        <div class="bg-white rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
            <h2 class="text-xl md:text-2xl font-black text-slate-800 uppercase mb-6 md:mb-8">📚 Tous mes cours</h2>

            @if($lessons->count() > 0)
                <div class="space-y-4">
                    @foreach($lessons as $lesson)
                        <div class="p-4 md:p-6 bg-slate-50 rounded-2xl md:rounded-3xl flex flex-col md:flex-row justify-between items-start gap-4 group hover:bg-blue-50 transition border-l-4
                            @if($lesson->statut === 'actif') border-green-500 @elseif($lesson->statut === 'complet') border-amber-500 @else border-slate-400 @endif">

                            <div class="flex-1 w-full">
                                <div class="flex flex-wrap items-start gap-3 mb-3">
                                    <h3 class="font-black text-slate-800 text-base md:text-xl">{{ $lesson->titre }}</h3>
                                    <span class="inline-block px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                        @if($lesson->statut === 'actif') bg-green-100 text-green-700
                                        @elseif($lesson->statut === 'complet') bg-amber-100 text-amber-700
                                        @else bg-slate-100 text-slate-600 @endif">
                                        @if($lesson->statut === 'actif') ✓ Actif
                                        @elseif($lesson->statut === 'complet') 🔒 Complet
                                        @else ⏸ Inactif @endif
                                    </span>
                                </div>

                                <p class="text-slate-600 text-xs md:text-sm mb-4 line-clamp-2">{{ $lesson->description }}</p>

                                <div class="flex flex-wrap gap-2 md:gap-3">
                                    @if($lesson->matiere)
                                        <span class="inline-block bg-blue-100 text-blue-700 px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                            📘 {{ $lesson->matiere->nom }}
                                        </span>
                                    @endif
                                    <span class="inline-block bg-amber-100 text-amber-700 px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                        💰 {{ number_format($lesson->prix, 0, ',', ' ') }} F
                                    </span>
                                    <span class="inline-block bg-purple-100 text-purple-700 px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                        ⏱ {{ $lesson->duree_minutes }} min
                                    </span>
                                    <span class="inline-block bg-slate-100 text-slate-700 px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                        👥 {{ $lesson->enrollments_paid_count }} / {{ $lesson->places_max }}
                                    </span>
                                    @if($lesson->start_date)
                                        <span class="inline-block bg-green-100 text-green-700 px-2 md:px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                            📅 {{ $lesson->start_date->format('d/m/Y à H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- ACTIONS -->
                            <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                                <a href="{{ route('teacher.private-lessons.edit', $lesson->id) }}" class="flex-1 sm:flex-none bg-blue-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg md:rounded-xl text-xs font-bold uppercase hover:bg-blue-700 transition text-center">
                                    ✏️ Modifier
                                </a>

                                <form action="{{ route('teacher.private-lessons.toggle', $lesson->id) }}" method="POST" class="flex-1 sm:flex-none">
                                    @csrf
                                    <button type="submit" class="w-full bg-amber-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg md:rounded-xl text-xs font-bold uppercase hover:bg-amber-700 transition">
                                        @if($lesson->statut === 'actif') ⏸ Désactiver @else ▶️ Activer @endif
                                    </button>
                                </form>

                                <form action="{{ route('teacher.private-lessons.destroy', $lesson->id) }}" method="POST" class="flex-1 sm:flex-none" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ? @if($lesson->enrollments_paid_count > 0){{ $lesson->enrollments_paid_count }} étudiants sont inscrits.@endif')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 text-white px-3 md:px-4 py-2 md:py-3 rounded-lg md:rounded-xl text-xs font-bold uppercase hover:bg-red-700 transition">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📚</div>
                    <p class="text-xl font-bold text-slate-600 mb-2">Aucun cours créé</p>
                    <p class="text-slate-500 mb-6">Commencez à monétiser votre expertise en créant votre premier cours particulier</p>
                    <a href="{{ route('teacher.private-lessons.create') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-2xl font-bold text-sm uppercase shadow-lg hover:bg-blue-700 transition">
                        ➕ Créer mon premier cours
                    </a>
                </div>
            @endif
        </div>

    </main>

    @include('layouts.footer')

</body>
</html>
