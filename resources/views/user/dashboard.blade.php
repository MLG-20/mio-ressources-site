<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Étudiant - MIO</title>
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
        /* Scrollbar Personnalisée - BLEU MAGNIFIQUE */
        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #2563eb, #1d4ed8); border-radius: 10px; box-shadow: 0 0 6px rgba(37, 99, 235, 0.5); }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(to bottom, #1d4ed8, #1e40af); box-shadow: 0 0 12px rgba(37, 99, 235, 0.8); }
        /* Firefox */
        * { scrollbar-color: #2563eb transparent; scrollbar-width: thin; }
        /* Input range slider */
        input[type="range"] { -webkit-appearance: none; width: 100%; height: 6px; border-radius: 5px; background: linear-gradient(to right, #dbeafe, #bfdbfe); outline: none; }
        input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1d4ed8); cursor: pointer; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.5); transition: all 0.2s; }
        input[type="range"]::-webkit-slider-thumb:hover { transform: scale(1.2); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.7); }
        input[type="range"]::-moz-range-thumb { width: 20px; height: 20px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1d4ed8); cursor: pointer; border: none; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.5); transition: all 0.2s; }
        input[type="range"]::-moz-range-thumb:hover { transform: scale(1.2); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.7); }
        /* Bouton retour animé */
        .btn-retour { position: relative; overflow: hidden; }
        .btn-retour::before { content: ''; position: absolute; top: 50%; left: -100%; width: 100%; height: 100%; background: rgba(255,255,255,0.2); transform: translateY(-50%); transition: left 0.3s ease; }
        .btn-retour:hover::before { left: 100%; }
        /* Fallback dark mode pour améliorer le contraste global */
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white, html.dark .bg-\[\#f8fafc\], html.dark .bg-slate-50 { background-color: #0f172a !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400, html.dark .text-gray-500 { color: #cbd5e1 !important; }
        html.dark .border-slate-50, html.dark .border-slate-100, html.dark .border-slate-200, html.dark .border-gray-200 { border-color: #334155 !important; }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden transition-colors duration-300" x-data="{
        tab: window.location.hash ? window.location.hash.substring(1) : (localStorage.getItem('activeTab') || 'bureau'),
        search: '',
        showScrollTop: false,
        mobileMenuOpen: false,
        setTab(newTab) {
            this.tab = newTab;
            window.location.hash = newTab;
            localStorage.setItem('activeTab', newTab);
            this.mobileMenuOpen = false;
        }
    }" @scroll.window="showScrollTop = window.scrollY > 300" x-init="
        // Forcer la détection du hash au chargement
        if (window.location.hash) {
            tab = window.location.hash.substring(1);
        }
        // Écouter les changements de hash
        window.addEventListener('hashchange', () => {
            if (window.location.hash) tab = window.location.hash.substring(1);
        });
        // Re-vérifier après un court délai pour garantir le changement
        setTimeout(() => {
            if (window.location.hash) {
                tab = window.location.hash.substring(1);
            }
        }, 100);
    " :class="{ 'overflow-hidden': mobileMenuOpen }">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <!-- NAVBAR -->
    <nav class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <a href="/">
           <div class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="hidden sm:inline font-black uppercase tracking-tighter text-xs md:text-base">Espace Étudiant</span>
            </div>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            <button @click="setTab('profil')" class="bg-slate-100 dark:bg-white/10 hover:bg-slate-200 dark:hover:bg-white/20 px-3 md:px-4 py-2 rounded-2xl flex items-center gap-2 md:gap-3 transition-all transform hover:scale-105">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff' }}" class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover cursor-pointer">
                <span class="text-xs md:text-sm font-bold hidden md:inline truncate cursor-pointer">{{ $user->name }}</span>
            </button>
            <form method="POST" action="{{ route('logout') }}"> @csrf
                <button type="submit" class="bg-red-500 text-white px-3 md:px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg">Déconnexion</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- MENU NAVIGATION - DESKTOP VERSION -->
        <div class="hidden md:flex gap-2 md:gap-4 mb-8 md:mb-10 overflow-x-auto pb-2 scrollbar-hide">
            <button @click="setTab('bureau')" :class="tab === 'bureau' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">📊 Bureau</button>
            <button @click="setTab('profil')" :class="tab === 'profil' ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">👤 Profil</button>
            <button @click="setTab('messages')" :class="tab === 'messages' ? 'bg-pink-600 text-white shadow-pink-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">💬 Messages</button>
            <button @click="setTab('groupes')" :class="tab === 'groupes' ? 'bg-indigo-600 text-white shadow-indigo-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">👥 Groupes</button>
            <button @click="setTab('cours')" :class="tab === 'cours' ? 'bg-green-600 text-white shadow-green-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">🎓 Cours</button>
            <button @click="setTab('historique')" :class="tab === 'historique' ? 'bg-amber-600 text-white shadow-amber-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">📚 Historique</button>
            <button @click="setTab('aide')" :class="tab === 'aide' ? 'bg-teal-600 text-white shadow-teal-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">❓ Aide</button>
        </div>

        <!-- MENU MOBILE - HAMBURGER VERSION -->
        <div class="md:hidden mb-6 flex items-center justify-between bg-white rounded-2xl p-3 shadow-lg border border-slate-100">
            <div class="font-black uppercase text-xs tracking-widest text-slate-700">
                <span v-if="tab === 'bureau'">📊 Bureau</span>
                <span v-else-if="tab === 'profil'">👤 Profil</span>
                <span v-else-if="tab === 'messages'">💬 Messages</span>
                <span v-else-if="tab === 'groupes'">👥 Groupes</span>
                <span v-else-if="tab === 'cours'">🎓 Cours</span>
                <span v-else-if="tab === 'historique'">📚 Historique</span>
                <span v-else-if="tab === 'aide'">❓ Aide</span>
            </div>
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <i :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'" class="fas text-lg text-slate-700"></i>
            </button>
        </div>

        <!-- MENU MOBILE DROPDOWN -->
        <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="md:hidden mb-6 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <button @click="setTab('bureau')" :class="tab === 'bureau' ? 'bg-blue-50 border-l-4 border-blue-600 text-blue-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-chart-pie text-lg w-5 flex-shrink-0"></i>
                <span>Bureau</span>
            </button>
            <button @click="setTab('profil')" :class="tab === 'profil' ? 'bg-purple-50 border-l-4 border-purple-600 text-purple-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-user text-lg w-5 flex-shrink-0"></i>
                <span>Profil</span>
            </button>
            <button @click="setTab('messages')" :class="tab === 'messages' ? 'bg-pink-50 border-l-4 border-pink-600 text-pink-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-comments text-lg w-5 flex-shrink-0"></i>
                <span>Messages</span>
            </button>
            <button @click="setTab('groupes')" :class="tab === 'groupes' ? 'bg-indigo-50 border-l-4 border-indigo-600 text-indigo-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-users text-lg w-5 flex-shrink-0"></i>
                <span>Groupes</span>
            </button>
            <button @click="setTab('cours')" :class="tab === 'cours' ? 'bg-green-50 border-l-4 border-green-600 text-green-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-graduation-cap text-lg w-5 flex-shrink-0"></i>
                <span>Cours</span>
            </button>
            <button @click="setTab('historique')" :class="tab === 'historique' ? 'bg-amber-50 border-l-4 border-amber-600 text-amber-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-book text-lg w-5 flex-shrink-0"></i>
                <span>Historique</span>
            </button>
            <button @click="setTab('aide')" :class="tab === 'aide' ? 'bg-teal-50 border-l-4 border-teal-600 text-teal-600' : 'text-slate-700 hover:bg-slate-50'" class="w-full text-left px-4 py-3 font-bold uppercase text-xs tracking-wide flex items-center gap-3 transition border-l-4 border-transparent">
                <i class="fas fa-circle-question text-lg w-5 flex-shrink-0"></i>
                <span>Aide</span>
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r shadow-sm flex items-start md:items-center gap-2 text-sm md:text-base">
                <i class="fas fa-check-circle flex-shrink-0 mt-0.5 md:mt-0"></i> <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded-r shadow-sm text-sm md:text-base">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 1. ONGLET BUREAU -->
        <div x-show="tab === 'bureau'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">

            <!-- Révision instantanée (Jitsi) -->
            <div class="bg-gradient-to-r from-sky-600 to-indigo-700 rounded-2xl md:rounded-3xl p-4 md:p-6 text-white shadow-xl dark:shadow-none flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-2xl bg-white/15 flex items-center justify-center shadow-lg shadow-blue-900/30 dark:shadow-none flex-shrink-0">
                        <i class="fas fa-video text-lg md:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Révision instantanée</p>
                        <h3 class="text-base md:text-xl font-black leading-tight">Lance une salle vidéo en 1 clic</h3>
                        <p class="text-xs md:text-sm opacity-90 mt-1">Partage l'écran, discute et révise avec tes camarades sans rien installer.</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <a href="{{ route('meeting.quick') }}" class="inline-flex items-center gap-2 bg-white text-slate-900 font-black px-4 md:px-5 py-2 md:py-3 rounded-xl shadow-lg shadow-blue-900/30 dark:shadow-none hover:-translate-y-0.5 hover:shadow-2xl dark:hover:shadow-none transition text-xs md:text-sm">
                        <i class="fas fa-bolt text-amber-500"></i> Démarrer
                    </a>
                    <a href="https://meet.jit.si" target="_blank" class="inline-flex items-center gap-2 bg-white/10 text-white border border-white/20 px-3 md:px-4 py-2 md:py-3 rounded-xl font-bold hover:bg-white/15 transition text-xs md:text-sm">
                        <i class="fas fa-shield-alt"></i> <span class="hidden md:inline">Infos sécurité</span><span class="md:hidden">Info</span>
                    </a>
                </div>
            </div>

            <!-- Cours Vidéo avec Professeurs -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl md:rounded-3xl p-4 md:p-6 text-white shadow-xl dark:shadow-none flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="w-10 md:w-12 h-10 md:h-12 rounded-2xl bg-white/15 flex items-center justify-center shadow-lg shadow-purple-900/30 dark:shadow-none flex-shrink-0">
                        <i class="fas fa-chalkboard-teacher text-lg md:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Cours Vidéo</p>
                        <h3 class="text-base md:text-xl font-black leading-tight">Rejoins les cours de tes profs en direct</h3>
                        <p class="text-xs md:text-sm opacity-90 mt-1">Inscris-toi aux cours programmés et participe aux sessions en temps réel.</p>
                    </div>
                </div>
                @if(Auth::user()->role !== 'admin')
                <div class="flex flex-wrap gap-2 md:gap-3">
                    <a href="{{ route('student.courses') }}" class="inline-flex items-center gap-2 bg-white text-purple-900 font-black px-4 md:px-5 py-2 md:py-3 rounded-xl shadow-lg shadow-purple-900/30 dark:shadow-none hover:-translate-y-0.5 hover:shadow-2xl dark:hover:shadow-none transition text-xs md:text-sm whitespace-nowrap">
                        <i class="fas fa-graduation-cap text-pink-500"></i> <span class="hidden md:inline">Voir mes cours</span><span class="md:hidden">Cours</span>
                    </a>
                </div>
                @endif
            </div>

            <!-- NOUVEAUTÉS -->
            @if(isset($nouveautes) && count($nouveautes) > 0)
            <div class="bg-gradient-to-br from-blue-600 to-indigo-900 rounded-2xl md:rounded-[3rem] p-4 md:p-8 shadow-xl dark:shadow-none text-white relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 md:w-32 h-24 md:h-32 bg-white/10 rounded-full blur-3xl -mr-12 md:-mr-16 -mt-12 md:-mt-16"></div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] mb-4 md:mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></span>
                    Nouveautés ({{ $user->student_level }})
                </h3>
                <div class="space-y-3 md:space-y-4">
                    @foreach($nouveautes as $nouv)
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-3 md:p-5 rounded-2xl md:rounded-3xl flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-white/20 transition-all gap-3"
                             x-show="search === '' || '{{ strtolower($nouv->titre) }}'.includes(search.toLowerCase())">
                            <div class="flex items-center gap-3 md:gap-5 min-w-0">
                                <div class="w-10 md:w-12 h-10 md:h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0 text-lg md:text-base"><i class="fas {{ $nouv->type == 'Vidéo' ? 'fa-play' : 'fa-file-pdf' }}"></i></div>
                                <div class="min-w-0">
                                    <p class="font-bold text-sm md:text-base leading-tight truncate">{{ $nouv->titre }}
                                        @if($nouv->is_premium)<span class="ml-2 text-[8px] md:text-[9px] bg-yellow-400 text-slate-900 px-2 py-0.5 rounded-full font-black uppercase whitespace-nowrap">{{ $nouv->price }} CFA</span>@endif
                                    </p>
                                    <p class="text-[10px] opacity-60 uppercase font-black tracking-widest mt-1 truncate">{{ $nouv->matiere->nom }}</p>
                                </div>
                            </div>
                            <a href="{{ route('ressource.download', $nouv->id) }}" target="_blank" class="bg-white text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition shadow-lg flex-shrink-0"><i class="fas fa-arrow-right"></i></a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- 2. ONGLET PROFIL -->
        <div x-show="tab === 'profil'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">
            <div class="bg-white rounded-xl md:rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-6 md:mb-8">👤 Mon Profil</h3>

                <form id="avatar-form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="text-center mb-8">
                        <div class="relative inline-block group cursor-pointer">
                            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff' }}"
                                 class="w-24 md:w-32 h-24 md:h-32 rounded-full object-cover border-4 border-slate-200 shadow-xl transition-transform duration-300 group-hover:scale-105 mx-auto">
                            <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-camera text-white text-xl"></i>
                            </div>
                            <input type="file" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('submit-btn').click()">
                        </div>
                        <h2 class="mt-4 text-xl md:text-2xl font-black text-slate-800">{{ $user->name }}</h2>
                        <p class="text-blue-600 font-bold text-xs uppercase tracking-[0.2em]">
                            Étudiant — <span class="bg-blue-100 px-2 py-0.5 rounded text-blue-700">{{ $user->student_level ?? 'L3' }}</span>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nom complet</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-slate-400"></i>
                                </div>
                                <input type="text" name="name" value="{{ $user->name }}"
                                       class="pl-10 w-full bg-slate-50 border-0 text-slate-700 font-bold rounded-2xl p-3 md:p-4 text-sm md:text-base focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                                       placeholder="Votre nom complet">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-slate-400"></i>
                                </div>
                                <input type="email" name="email" value="{{ $user->email }}"
                                       class="pl-10 w-full bg-slate-50 border-0 text-slate-700 font-medium rounded-2xl p-3 md:p-4 text-sm md:text-base focus:ring-2 focus:ring-blue-500 outline-none"
                                       placeholder="Votre email">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mot de passe actuel</label>
                            <input type="password" name="current_password" placeholder="••••••••" class="w-full bg-slate-50 border-0 text-slate-700 rounded-2xl p-3 md:p-4 text-sm md:text-base focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nouveau mot de passe</label>
                            <input type="password" name="new_password" placeholder="••••••••" class="w-full bg-slate-50 border-0 text-slate-700 rounded-2xl p-3 md:p-4 text-sm md:text-base focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Confirmer nouveau mot de passe</label>
                            <input type="password" name="new_password_confirmation" placeholder="••••••••" class="w-full bg-slate-50 border-0 text-slate-700 rounded-2xl p-3 md:p-4 text-sm md:text-base focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <button id="submit-btn" type="submit" class="mt-6 md:mt-8 w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black py-4 md:py-5 rounded-2xl shadow-xl transition-all transform hover:scale-[1.02] active:scale-95 flex justify-center items-center gap-2 uppercase tracking-widest text-sm md:text-base">
                        <i class="fas fa-save"></i> Sauvegarder les modifications
                    </button>
                </form>

                <div class="text-center mt-8">
                    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors uppercase tracking-widest border-b border-transparent hover:border-red-600 pb-1">
                        Supprimer mon compte définitivement
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. ONGLET MESSAGES -->
        <div x-show="tab === 'messages'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">
            <!-- Barre de recherche -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-pink-500 transition-colors"></i>
                </div>
                <input type="text" x-model="search" placeholder="Rechercher dans les messages..."
                       class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-12 pr-4 shadow-sm text-slate-700 font-bold focus:ring-4 focus:ring-pink-100 focus:border-pink-500 outline-none transition-all text-sm md:text-base">
            </div>

            <!-- FORMULAIRE ÉCRIRE UN MESSAGE -->

    <div class="flex items-center gap-3 mb-2">
        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-md">
            FORUM {{ Auth::user()->student_level ?? 'Général' }}
        </span>
        <p class="text-slate-500 text-sm font-bold">Espace de discussion de votre classe</p>
    </div>

    <div class="bg-white rounded-2xl md:rounded-3xl p-6 md:p-8 shadow-xl border border-slate-100">
        <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-6 flex items-center gap-2">
            <i class="fas fa-edit text-blue-600"></i> Lancer une discussion
        </h3>

        <form action="{{ route('forum.message.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Sujet</label>
                <input type="text" name="titre" placeholder="Ex: Problème exercice Java..."
                       class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl p-4 text-slate-700 font-bold outline-none focus:border-blue-500 transition-all" required>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Message</label>
                <textarea name="contenu" placeholder="Écrivez votre message pour la classe ici..." class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 text-slate-700 outline-none h-32 font-medium focus:border-blue-500 transition-all" required></textarea>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-black py-4 rounded-2xl shadow-lg transition-all uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Publier pour la classe
            </button>
        </form>
    </div>

    <div class="space-y-4 pt-4">
        <h3 class="text-lg font-black text-slate-800 uppercase pl-2 border-l-4 border-blue-500 ml-1">
            Discussions récentes ({{ Auth::user()->student_level ?? 'Général' }})
        </h3>

        @if(isset($sujetsDeMaClasse) && count($sujetsDeMaClasse) > 0)
            @foreach($sujetsDeMaClasse as $sujet)
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-blue-200 transition group">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                    <div class="flex items-start gap-4 min-w-0">
                        <div class="flex-shrink-0">
                            <img src="{{ $sujet->user->avatar ? asset('storage/'.$sujet->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($sujet->user->name).'&background=random&color=fff' }}"
                                 class="w-12 h-12 rounded-full object-cover border-2 border-slate-100 shadow-sm">
                        </div>

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('forum.sujet', $sujet->id) }}" class="font-black text-slate-800 text-lg hover:text-blue-600 transition line-clamp-1 block">
                                {{ $sujet->titre }}
                            </a>
                            <div class="flex items-center gap-2 mt-1 text-xs">
                                <span class="font-bold text-slate-500">{{ $sujet->user->name }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-400">{{ $sujet->created_at->diffForHumans() }}</span>
                                @if($sujet->user_id === Auth::id())
                                    <span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-[10px] font-black uppercase">MOI</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('forum.sujet', $sujet->id) }}" class="bg-slate-50 text-slate-600 px-4 py-3 rounded-xl font-bold text-xs hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                            Voir <i class="fas fa-arrow-right"></i>
                        </a>

                        @if($sujet->user_id === Auth::id())
                            <form action="{{ route('user.sujet.destroy', $sujet->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette discussion ?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-50 text-red-500 px-4 py-3 rounded-xl font-bold text-xs hover:bg-red-600 hover:text-white transition">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>

                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-12 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                <p class="text-slate-500 font-bold">Aucune discussion pour le moment.</p>
                <p class="text-slate-400 text-sm mt-1">Lancez le premier sujet pour votre classe !</p>
            </div>
        @endif
    </div>
</div>

        <!-- 4. ONGLET GROUPES DE TRAVAIL -->
        <div x-show="tab === 'groupes'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500" x-data="{ showCreateModal: false, showInviteModal: false, showMembersModal: false, selectedGroup: null, selectedMembers: null }">

            <!-- Bouton créer un groupe -->
            <div class="flex justify-end">
                <button @click="showCreateModal = true" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-black text-sm shadow-xl hover:shadow-2xl transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Créer un groupe
                </button>
            </div>

            <!-- Liste des groupes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $myWorkGroups = Auth::user()->workGroups ?? collect();
                @endphp
                @if($myWorkGroups->count() > 0)
                    @foreach($myWorkGroups as $group)
                    <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-100 hover:shadow-2xl transition">
                        <div class="flex items-start justify-between mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black shadow-lg">
                                {{ strtoupper(substr($group->name, 0, 1)) }}
                            </div>
                            @if($group->creator_id === Auth::id())
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Créateur</span>
                            @endif
                        </div>

                        <h3 class="font-black text-lg text-slate-800 mb-2 truncate">{{ $group->name }}</h3>
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $group->description ?? 'Aucune description' }}</p>

                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-4">
                            <i class="fas fa-users"></i>
                            <span>{{ $group->members->count() }} membre(s)</span>
                        </div>

                        <!-- Liste des membres dans la carte -->
                        @if($group->members->count() > 0)
                        <div class="mb-4 p-3 bg-slate-50 rounded-xl">
                            <p class="text-xs font-bold text-slate-600 mb-2">Membres:</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($group->members->take(5) as $member)
                                <div class="flex items-center gap-1.5 bg-white px-2 py-1 rounded-lg text-xs">
                                    <img src="{{ $member->avatar ? asset('storage/'.$member->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=6366f1&color=fff&size=24' }}"
                                         class="w-4 h-4 rounded-full object-cover">
                                    <span class="font-semibold text-slate-700 truncate max-w-[80px]">{{ $member->name }}</span>
                                </div>
                                @endforeach
                                @if($group->members->count() > 5)
                                <div class="flex items-center bg-slate-200 px-2 py-1 rounded-lg text-xs font-bold text-slate-600">
                                    +{{ $group->members->count() - 5 }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('groups.show', $group->id) }}" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-xl font-bold text-xs text-center hover:shadow-lg transition">
                                <i class="fas fa-video"></i> Entrer
                            </a>

                            @if($group->creator_id === Auth::id())
                            @php $membersJson = json_encode($group->members->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'creator_id' => $group->creator_id])->values()->all()) @endphp
                            <button @click="selectedGroup = {{ $group->id }}; selectedMembers = {{ $membersJson }}; showMembersModal = true" class="bg-green-100 text-green-600 px-4 py-2 rounded-xl font-bold text-xs hover:bg-green-200 transition" title="Gérer les membres">
                                <i class="fas fa-users"></i>
                            </button>
                            <button @click="selectedGroup = {{ $group->id }}; showInviteModal = true" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-xl font-bold text-xs hover:bg-blue-200 transition">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Supprimer ce groupe ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 px-4 py-2 rounded-xl font-bold text-xs hover:bg-red-200 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <form action="{{ route('groups.leave', $group->id) }}" method="POST" onsubmit="return confirm('Quitter ce groupe ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-orange-100 text-orange-600 px-4 py-2 rounded-xl font-bold text-xs hover:bg-orange-200 transition">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-span-full bg-slate-50 rounded-2xl p-10 text-center border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-indigo-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-700 mb-2">Aucun groupe pour le moment</h3>
                        <p class="text-slate-500 mb-4">Créez votre premier groupe de travail pour collaborer avec d'autres étudiants</p>
                        <button @click="showCreateModal = true" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-2xl font-black shadow-lg hover:shadow-xl transition">
                            Créer un groupe
                        </button>
                    </div>
                @endif
            </div>

            <!-- MODAL: Créer un groupe -->
            <div x-show="showCreateModal" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4" @click.self="showCreateModal = false">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800">Créer un groupe</h3>
                        <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form action="{{ route('groups.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nom du groupe *</label>
                            <input type="text" name="name" required maxlength="255"
                                   class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition"
                                   placeholder="Ex: Projet Java L3">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Description (optionnelle)</label>
                            <textarea name="description" rows="3" maxlength="1000"
                                      class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition resize-none"
                                      placeholder="Objectif du groupe..."></textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="showCreateModal = false" class="flex-1 bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition">
                                Annuler
                            </button>
                            <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition">
                                Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL: Inviter des membres -->
            <div x-show="showInviteModal" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4" @click.self="showInviteModal = false">
                <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800">Inviter un membre</h3>
                        <button @click="showInviteModal = false" class="text-slate-400 hover:text-slate-600 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form :action="'/groupes/' + selectedGroup + '/invite'" method="POST" class="space-y-4">
                        @csrf

                        <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 mb-4">
                            <div class="flex items-start gap-2 text-sm text-indigo-700">
                                <i class="fas fa-info-circle mt-0.5 flex-shrink-0"></i>
                                <p><strong>L'étudiant recevra un email</strong> avec le lien d'accès au groupe. Il pourra alors rejoindre la salle Jitsi permanente.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email de l'étudiant *</label>
                            <input type="email" name="email" required
                                   class="w-full border-2 border-slate-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"
                                   placeholder="exemple@email.com">
                            <p class="text-xs text-slate-500 mt-2">L'étudiant doit être inscrit sur la plateforme</p>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="showInviteModal = false" class="flex-1 bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition">
                                Annuler
                            </button>
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                                <i class="fas fa-envelope"></i> Envoyer l'invitation
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL: Gérer les membres du groupe -->
            <div x-show="showMembersModal" x-cloak class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4" @click.self="showMembersModal = false">
                <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-black text-slate-800">Gérer les membres</h3>
                        <button @click="showMembersModal = false" class="text-slate-400 hover:text-slate-600 transition">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <template x-for="member in (selectedMembers || [])" :key="member.id">
                            <div class="flex items-center justify-between bg-slate-50 p-4 rounded-xl hover:bg-slate-100 transition">
                                <div class="flex items-center gap-3 flex-1">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-black flex-shrink-0">
                                        <span x-text="member.name.charAt(0).toUpperCase()"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-slate-800 truncate" x-text="member.name"></p>
                                        <p class="text-xs text-slate-500" x-show="member.id === member.creator_id">Créateur du groupe</p>
                                    </div>
                                </div>

                                <!-- Bouton supprimer (sauf pour le créateur) -->
                                <div x-show="member.id !== member.creator_id" class="flex-shrink-0">
                                    <form :action="'/groupes/' + selectedGroup + '/members/' + member.id" method="POST" onsubmit="return confirm('Retirer ce membre du groupe ?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-2 rounded-lg font-bold text-xs transition">
                                            <i class="fas fa-trash"></i> Retirer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex gap-3 pt-6 mt-6 border-t border-slate-200">
                        <button type="button" @click="showMembersModal = false" class="flex-1 bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. ONGLET COURS -->
        <div x-show="tab === 'cours'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">

            <!-- Barre de recherche -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-green-500 transition-colors"></i>
                </div>
                <input type="text" x-model="search" placeholder="Rechercher un cours..."
                       class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-12 pr-4 shadow-sm text-slate-700 font-bold focus:ring-4 focus:ring-green-100 focus:border-green-500 outline-none transition-all text-sm md:text-base">
            </div>

            <!-- MES COURS PARTICULIERS RÉSERVÉS -->
            <div class="bg-white rounded-xl md:rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-6 flex items-center gap-2">
                    <i class="fas fa-calendar-check text-green-600"></i> Mes Cours Particuliers
                </h3>

                <div class="space-y-4">
                    @forelse($mesCoursParticuliers as $enrollment)
                        <div class="group p-5 md:p-6 border border-slate-200 rounded-2xl hover:border-green-300 hover:bg-green-50 transition">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 md:gap-6">
                                <div class="flex-1 min-w-0">
                                    <!-- Titre du cours et professeur -->
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-book-open text-green-600"></i>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-black text-slate-800 text-base md:text-lg line-clamp-2">{{ $enrollment->privateLesson->titre }}</h4>
                                            <p class="text-slate-500 text-sm flex items-center gap-1 mt-1">
                                                <i class="fas fa-user-tie text-purple-500"></i>
                                                {{ $enrollment->privateLesson->teacher->name }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Détails du cours -->
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 ml-15">
                                        <div class="text-xs">
                                            <p class="text-slate-400 font-bold uppercase">Date</p>
                                            <p class="text-slate-800 font-black">{{ $enrollment->scheduled_at?->format('d/m/Y') ?? 'À définir' }}</p>
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-slate-400 font-bold uppercase">Heure</p>
                                            <p class="text-slate-800 font-black">{{ $enrollment->scheduled_at?->format('H:i') ?? '--:--' }}</p>
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-slate-400 font-bold uppercase">Durée</p>
                                            <p class="text-slate-800 font-black">{{ $enrollment->privateLesson->duree_minutes }} min</p>
                                        </div>
                                        <div class="text-xs">
                                            <p class="text-slate-400 font-bold uppercase">Matière</p>
                                            <p class="text-slate-800 font-black">{{ $enrollment->privateLesson->matiere?->nom ?? 'Général' }}</p>
                                        </div>
                                    </div>

                                    <!-- Statut -->
                                    <div class="flex items-center gap-2 mt-4">
                                        @if($enrollment->session_status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold uppercase rounded-full">
                                                <i class="fas fa-check-double"></i> Complété
                                            </span>
                                        @elseif($enrollment->session_status === 'active')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 text-xs font-bold uppercase rounded-full animate-pulse">
                                                <i class="fas fa-circle text-red-600"></i> En cours
                                            </span>
                                        @elseif($enrollment->session_status === 'cancelled')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold uppercase rounded-full">
                                                <i class="fas fa-times-circle"></i> Annulé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 text-xs font-bold uppercase rounded-full">
                                                <i class="fas fa-calendar-check"></i> Programmé
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-2 md:gap-3 w-full md:w-auto">
                                    @if($enrollment->session_status === 'active' || ($enrollment->scheduled_at && now() >= $enrollment->scheduled_at->subMinutes(5)))
                                        <a href="{{ route('private-lessons.room', $enrollment->id) }}" class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-black px-4 md:px-5 py-2 md:py-3 rounded-xl shadow-lg hover:shadow-2xl transition text-xs md:text-sm whitespace-nowrap">
                                            <i class="fas fa-video"></i> Rejoindre
                                        </a>
                                    @else
                                        <button disabled class="inline-flex items-center justify-center gap-2 bg-slate-200 text-slate-400 font-black px-4 md:px-5 py-2 md:py-3 rounded-xl text-xs md:text-sm whitespace-nowrap cursor-not-allowed">
                                            <i class="fas fa-lock"></i> Pas disponible
                                        </button>
                                    @endif

                                    <!-- Bouton d'annulation -->
                                    @if($enrollment->session_status !== 'cancelled' && $enrollment->session_status !== 'completed')
                                        <form method="POST" action="{{ route('private-lessons.enrollment.cancel', $enrollment->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')" class="inline-flex items-center justify-center gap-2 bg-red-50 text-red-600 font-black px-4 md:px-5 py-2 md:py-3 rounded-xl hover:bg-red-100 transition text-xs md:text-sm w-full md:w-auto border border-red-200">
                                                <i class="fas fa-trash-alt"></i> Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="w-16 h-16 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-calendar-times text-slate-400 text-2xl"></i>
                            </div>
                            <p class="text-slate-500 font-bold mb-4">Aucun cours particulier réservé</p>
                            <a href="{{ route('private-lessons.browse') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white font-black px-6 py-3 rounded-xl hover:bg-blue-700 transition text-sm">
                                <i class="fas fa-search"></i> Parcourir les cours
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- MES COURS VIDÉO (Sessions avec professeurs) -->
            <div class="bg-white rounded-xl md:rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-slate-100 text-center">
                @if(Auth::user()->role !== 'admin')
                <div class="w-20 h-20 bg-purple-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-purple-600 text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Mes Cours Vidéo</h3>
                <p class="text-slate-500 mb-6">Accédez à tous vos cours programmés et inscriptions</p>
                <a href="{{ route('student.courses') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-black px-6 py-3 rounded-2xl shadow-lg hover:shadow-2xl transition">
                    <i class="fas fa-chalkboard-teacher"></i> Voir mes cours
                </a>
                @endif
            </div>
        </div>

        <!-- 6. ONGLET HISTORIQUE -->
        <div x-show="tab === 'historique'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">
            <!-- Barre de recherche -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-slate-400 group-focus-within:text-amber-500 transition-colors"></i>
                </div>
                <input type="text" x-model="search" placeholder="Rechercher dans l'historique..."
                       class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-12 pr-4 shadow-sm text-slate-700 font-bold focus:ring-4 focus:ring-amber-100 focus:border-amber-500 outline-none transition-all text-sm md:text-base">
            </div>

            <div class="bg-white rounded-xl md:rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 md:mb-8 gap-3">
                    <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase flex items-center gap-2">
                        <i class="fas fa-history text-blue-500"></i> Historique & Achats
                    </h3>
                    <form action="{{ route('user.history.clear') }}" method="POST">@csrf @method('DELETE')
                        <button class="bg-red-50 text-red-600 px-4 py-2 rounded-2xl font-bold text-xs uppercase hover:bg-red-600 hover:text-white transition">
                            <i class="fas fa-trash"></i> Vider
                        </button>
                    </form>
                </div>

                <div class="space-y-3 md:space-y-4">
                    @forelse($downloads as $download)
                        <div class="flex items-center justify-between group p-4 md:p-5 hover:bg-blue-50 rounded-2xl transition border border-slate-100 hover:border-blue-200 gap-3"
                             x-show="search === '' || '{{ strtolower($download->titre) }}'.includes(search.toLowerCase())">
                            <div class="flex items-center gap-4 md:gap-5 min-w-0 flex-1">
                                <div class="w-12 md:w-14 h-12 md:h-14 bg-slate-100 group-hover:bg-white text-slate-500 group-hover:text-blue-600 rounded-2xl flex items-center justify-center text-lg md:text-xl shadow-sm flex-shrink-0">
                                    <i class="fas {{ $download->type == 'Vidéo' ? 'fa-play' : 'fa-file-pdf' }}"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('ressource.download', $download->id) }}" target="_blank" class="font-black text-slate-800 hover:text-blue-600 block text-sm md:text-base truncate">{{ $download->titre }}</a>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] text-slate-400 uppercase font-bold truncate">{{ $download->matiere->nom }}</span>
                                        @if($download->is_premium)
                                            <span class="text-[8px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-black uppercase border border-amber-200 flex-shrink-0">⭐ Débloqué</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('user.history.destroy', $download->id) }}" method="POST">@csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 hover:text-red-500 hover:bg-red-50 transition shadow-sm flex-shrink-0">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-inbox text-slate-400 text-2xl"></i>
                            </div>
                            <p class="text-slate-400 font-bold">Aucun document téléchargé</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- PUBLIER UN MÉMOIRE -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-xl md:rounded-[2.5rem] p-6 md:p-10 shadow-xl border border-indigo-200">
                <h3 class="text-base md:text-lg font-black text-slate-800 uppercase mb-6 flex items-center gap-2">
                    <i class="fas fa-book text-indigo-600"></i> Publier votre mémoire
                </h3>
                <form action="{{ route('user.memoire.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 md:space-y-5">
                    @csrf
                    <div>
                        <input type="text" name="titre" placeholder="Titre de votre mémoire" class="w-full bg-white border-0 rounded-2xl p-3 md:p-4 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none text-sm md:text-base" required>
                    </div>
                    <div>
                        <textarea name="description" placeholder="Résumé ou description (optionnel)" class="w-full bg-white border-0 rounded-2xl p-3 md:p-4 text-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none resize-none h-20 text-sm md:text-base"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 ml-1">Fichier PDF*</label>
                            <input type="file" name="file_path" accept=".pdf" class="w-full text-slate-600 text-sm cursor-pointer file:mr-4 file:py-2 md:py-3 file:px-3 md:file:px-4 file:rounded-xl file:border-0 file:font-bold file:text-indigo-600 file:bg-indigo-100 hover:file:bg-indigo-200 transition" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 ml-1">Couverture</label>
                            <input type="file" name="cover_image" accept=".jpg,.jpeg,.png" class="w-full text-slate-600 text-sm cursor-pointer file:mr-4 file:py-2 md:py-3 file:px-3 md:file:px-4 file:rounded-xl file:border-0 file:font-bold file:text-indigo-600 file:bg-indigo-100 hover:file:bg-indigo-200 transition">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black py-3 md:py-4 rounded-2xl shadow-lg transition-all transform active:scale-95 uppercase tracking-widest text-sm md:text-base">
                        <i class="fas fa-book"></i> Publier mon mémoire
                    </button>
                </form>
            </div>
        </div>
        <!-- ONGLET AIDE -->
        <div x-show="tab === 'aide'" x-cloak class="space-y-6 md:space-y-8">
            <div class="bg-white rounded-xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h2 class="text-xl md:text-2xl font-black text-slate-800 uppercase mb-1">Centre d'aide</h2>
                <p class="text-slate-500 text-sm mb-8">Comment utiliser toutes les fonctionnalités de votre espace étudiant.</p>

                <div class="space-y-3">

                    <!-- Bureau -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>📊 Bureau</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Le Bureau est votre page d'accueil. Il affiche un résumé de votre activité :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Vos ressources téléchargées récemment.</li>
                                <li>Les cours auxquels vous êtes inscrits.</li>
                                <li>Vos groupes de travail actifs.</li>
                                <li>Les dernières publications des enseignants.</li>
                            </ul>
                            <p>Cliquez sur n'importe quelle carte pour accéder à la section correspondante.</p>
                        </div>
                    </details>

                    <!-- Profil -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>👤 Profil</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Personnalisez votre compte :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Photo de profil</strong> — importez une image JPG/PNG visible par la communauté.</li>
                                <li><strong>Informations personnelles</strong> — mettez à jour votre nom et votre email.</li>
                                <li><strong>Mot de passe</strong> — saisissez l'ancien mot de passe, puis le nouveau (2 fois pour confirmer).</li>
                                <li><strong>Supprimer le compte</strong> — action irréversible nécessitant votre mot de passe.</li>
                            </ul>
                            <p>Cliquez sur votre avatar dans la barre de navigation pour accéder rapidement à cette section.</p>
                        </div>
                    </details>

                    <!-- Messages / Forum -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>💬 Messages &amp; Forum</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Participez aux discussions de la communauté MIO :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Retrouvez ici tous vos messages postés dans le forum.</li>
                                <li>Modifiez ou supprimez vos messages existants.</li>
                                <li>Pour ouvrir un nouveau sujet, accédez au <strong>Forum</strong> depuis le menu principal du site.</li>
                                <li>Les discussions sont organisées par niveau (L1, L2, L3, M1, M2).</li>
                            </ul>
                        </div>
                    </details>

                    <!-- Groupes de travail -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>👥 Groupes de travail</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Collaborez avec vos camarades en groupes de travail :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Rejoignez un groupe existant ou créez le vôtre.</li>
                                <li>Partagez des documents et des ressources avec les membres.</li>
                                <li>Organisez des sessions de travail via les <strong>Meetings Jitsi</strong> intégrés.</li>
                                <li>Les membres d'un groupe reçoivent des notifications pour les nouvelles activités.</li>
                            </ul>
                        </div>
                    </details>

                    <!-- Cours -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>🎓 Cours &amp; Cours Particuliers</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p><strong>Cours vidéo :</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Accédez aux cours vidéo publiés par les enseignants.</li>
                                <li>Les cours sont filtrés par semestre pour faciliter la navigation.</li>
                            </ul>
                            <p class="font-bold text-slate-700">Cours particuliers :</p>
                            <ol class="list-decimal pl-5 space-y-1">
                                <li>Accédez à la section <strong>Cours Particuliers</strong> depuis le menu principal.</li>
                                <li>Parcourez les offres des enseignants disponibles.</li>
                                <li>Cliquez sur <strong>Réserver</strong> et payez via PayTech (Wave, Orange Money, carte bancaire…).</li>
                                <li>Une réunion Jitsi est créée automatiquement après confirmation du paiement.</li>
                                <li>Vous recevez un email avec le lien et l'heure de la session.</li>
                            </ol>
                        </div>
                    </details>

                    <!-- Historique -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>📚 Historique</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Retrouvez toutes les ressources que vous avez téléchargées ou consultées :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Accès rapide à vos derniers téléchargements sans avoir à les rechercher.</li>
                                <li>Supprimez une entrée individuelle ou effacez tout l'historique.</li>
                                <li>Les ressources payantes déjà achetées restent accessibles indéfiniment.</li>
                            </ul>
                        </div>
                    </details>

                    <!-- Paiements -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>💳 Paiements PayTech</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>La plateforme utilise <strong>PayTech</strong> pour les paiements sécurisés.</p>
                            <p><strong>Moyens de paiement acceptés :</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Wave</li>
                                <li>Orange Money</li>
                                <li>Free Money</li>
                                <li>Carte bancaire (Visa / Mastercard)</li>
                            </ul>
                            <p class="font-bold text-slate-700">Comment ça marche :</p>
                            <ol class="list-decimal pl-5 space-y-1">
                                <li>Cliquez sur <strong>Télécharger</strong> ou <strong>Réserver</strong> sur une ressource payante.</li>
                                <li>Vous êtes redirigé vers la page de paiement sécurisée PayTech.</li>
                                <li>Choisissez votre moyen de paiement et validez.</li>
                                <li>Après confirmation, l'accès à la ressource est immédiat et automatique.</li>
                            </ol>
                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-700">
                                <i class="fas fa-shield-halved mr-1"></i> Vos données bancaires ne sont jamais stockées sur MIO Ressources. Tous les paiements sont traités directement par PayTech.
                            </div>
                        </div>
                    </details>

                </div>

                <!-- Contact support -->
                <div class="mt-8 bg-teal-50 border border-teal-100 rounded-2xl p-5 flex items-start gap-4">
                    <i class="fas fa-life-ring text-teal-500 text-xl mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="font-black text-slate-800 text-sm uppercase mb-1">Besoin d'aide supplémentaire ?</p>
                        <p class="text-slate-500 text-sm">Posez votre question dans le <strong>Forum</strong> ou contactez l'administrateur de la plateforme MIO Ressources.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- BOUTON SCROLL TO TOP DESIGNER ET ANIMÉ -->
    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-show="showScrollTop" x-transition class="fixed bottom-8 right-8 z-50 btn-retour inline-flex items-center justify-center bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-500 hover:from-blue-700 hover:via-blue-600 hover:to-cyan-600 text-white font-black p-4 md:p-5 rounded-full shadow-2xl hover:shadow-3xl hover:scale-110 active:scale-95 transform transition-all duration-300 group" title="Remonter en haut">
        <i class="fas fa-arrow-up text-xl md:text-2xl transform group-hover:-translate-y-1 transition-transform duration-300"></i>
    </button>

    <!-- MODAL SUPPRESSION -->
    <div id="delete-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-2xl md:rounded-[3rem] p-6 md:p-10 text-center shadow-2xl">
            <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase">Supprimer le compte ?</h3>
            <p class="text-slate-500 mb-8 text-sm">Cette action est définitive. Confirmez avec votre mot de passe.</p>
            <form action="{{ route('user.account.delete') }}" method="POST">
                @csrf @method('DELETE')
                <input type="password" name="password" placeholder="Mot de passe" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 mb-6 text-center font-bold" required>
                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')" class="flex-1 bg-slate-100 py-4 rounded-2xl font-bold">ANNULER</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white py-4 rounded-2xl font-bold">SUPPRIMER</button>
                </div>
            </form>
        </div>
    </div>

<script>
    (function () {
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-toggle-icon');
        if (!btn || !icon) return;
        const syncIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
        };
        syncIcon();
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncIcon();
        });
    })();
</script>
</body>
</html>
