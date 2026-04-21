<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Espace Enseignant - MIO</title>
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
        /* Lisibilité des champs en mode clair */
        input,
        select,
        textarea {
            background-color: #f8fafc;
            color: #0f172a;
            border: 1px solid #cbd5e1;
        }
        input::placeholder,
        textarea::placeholder {
            color: #64748b;
        }
        /* Fallback dark mode pour améliorer le contraste global */
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white, html.dark .bg-\[\#f8fafc\], html.dark .bg-slate-50 { background-color: #0f172a !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400, html.dark .text-gray-500 { color: #cbd5e1 !important; }
        html.dark .border-slate-50, html.dark .border-slate-100, html.dark .border-slate-200, html.dark .border-gray-200 { border-color: #334155 !important; }
        /* Contraste renforcé des champs en mode sombre */
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            border: 1px solid #334155 !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #94a3b8 !important;
        }
        html.dark details.bg-slate-50,
        html.dark div.bg-slate-50 {
            background-color: #1e293b !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden transition-colors duration-300" x-data="{ tab: 'bureau' }">

    <!-- NAVBAR -->
    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <nav class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <a href="/">
           <div class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="hidden sm:inline font-black uppercase tracking-tighter text-xs md:text-base">Espace Enseignant</span>
            </div>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            <div class="bg-slate-100 dark:bg-white/10 px-3 md:px-4 py-2 rounded-2xl flex items-center gap-2 md:gap-3">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover">
                <span class="text-xs md:text-sm font-bold hidden md:inline truncate">{{ $user->name }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}"> @csrf
                <button type="submit" class="bg-red-500 text-white px-3 md:px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg">Déconnexion</button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- MENU NAVIGATION PAR ONGRETS -->
        <div class="flex gap-2 md:gap-4 mb-8 md:mb-10 overflow-x-auto pb-2 scrollbar-hide">
            <button @click="tab = 'bureau'" :class="tab === 'bureau' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">📊 Bureau</button>
            <button @click="tab = 'cours'" :class="tab === 'cours' ? 'bg-purple-600 text-white shadow-purple-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">🎥 Cours</button>
            <a href="{{ route('teacher.private-lessons.index') }}" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap bg-amber-600 text-white hover:bg-amber-700">💼 Cours Particuliers</a>
            <button @click="tab = 'forum'" :class="tab === 'forum' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">💬 Forum</button>
            <button @click="tab = 'settings'" :class="tab === 'settings' ? 'bg-slate-900 text-white' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">⚙️ Params</button>
            <button @click="tab = 'aide'" :class="tab === 'aide' ? 'bg-teal-600 text-white shadow-teal-200' : 'bg-white text-slate-500'" class="px-4 md:px-8 py-3 md:py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl transition-all whitespace-nowrap">❓ Aide</button>
        </div>

        <!-- 1. ONGLET BUREAU -->
        <div x-show="tab === 'bureau'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">
            <!-- KPI -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-blue-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Publications</span>
                    <span class="text-3xl md:text-4xl font-black text-slate-800">{{ $mesPublications->count() }}</span>
                </div>
                <div class="bg-slate-900 p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-amber-500 text-white">
                    <span class="text-[10px] font-black text-amber-500 uppercase mb-2 block">Mon Solde</span>
                    <span class="text-2xl md:text-3xl font-black line-clamp-1">{{ number_format($totalRevenus, 0, ',', ' ') }} F</span>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-purple-600">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Spécialité</span>
                    <span class="text-sm md:text-base font-black text-purple-600 uppercase line-clamp-2">{{ $user->specialty ?? 'À renseigner' }}</span>
                </div>
            </div>

            <!-- Publier -->
            <div class="bg-white rounded-xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-6 md:mb-8">Nouvel Ouvrage</h3>
                <form action="{{ route('teacher.publication.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isPremium: false }" class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    @csrf
                    <input type="text" name="titre" placeholder="Titre" class="md:col-span-2 w-full bg-slate-50 border-0 rounded-2xl p-3 md:p-4 font-bold text-sm md:text-base" required>
                    <select name="type" class="bg-slate-50 border-0 rounded-2xl p-3 md:p-4 font-bold text-sm md:text-base">
                        <option value="Livre">📚 Livre</option>
                        <option value="Mémoire">🎓 Mémoire</option>
                    </select>
                    <div class="flex items-center justify-between bg-slate-50 p-3 md:p-4 rounded-2xl">
                        <label class="flex items-center gap-2 text-sm md:text-base"><input type="checkbox" name="is_premium" x-model="isPremium"> <span class="text-xs md:text-sm font-bold uppercase">Vendre</span></label>
                        <input type="number" name="price" x-show="isPremium" placeholder="Prix CFA" class="w-24 p-2 rounded-xl border-0 font-bold text-xs md:text-sm">
                    </div>
                    <textarea name="description" placeholder="Résumé..." class="md:col-span-2 w-full bg-slate-50 border-0 rounded-2xl p-3 md:p-4 text-sm md:text-base"></textarea>
                    <input type="file" name="file_path" class="text-xs md:text-sm" required>
                    <input type="file" name="cover_image" class="text-xs md:text-sm">
                    <button type="submit" class="md:col-span-2 bg-blue-600 text-white py-4 md:py-5 rounded-2xl md:rounded-[2rem] font-black shadow-xl uppercase text-sm md:text-base">Publier</button>
                </form>
            </div>

            <!-- Historique des Publications -->
            <div class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8">📚 Historique de vos publications</h3>
                @if($mesPublications->count() > 0)
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($mesPublications as $pub)
                            <div class="p-6 bg-slate-50 rounded-3xl flex justify-between items-start group hover:bg-blue-50 transition border-l-4 border-blue-600">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg">{{ $pub->titre }}</p>
                                    <div class="flex flex-wrap gap-3 mt-3">
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                            @if($pub->type === 'Livre') 📚 Livre @elseif($pub->type === 'Mémoire') 🎓 Mémoire @endif
                                        </span>
                                        @if($pub->is_premium)
                                            <span class="inline-block bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">💰 Premium - {{ number_format($pub->price, 0, ',', ' ') }} F</span>
                                        @else
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">✓ Gratuit</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold mt-3">Publié le {{ $pub->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex gap-2 ml-2 md:ml-4 flex-shrink-0">
                                    <form action="{{ route('teacher.publication.destroy', $pub->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 md:px-6 py-2 md:py-3 rounded-2xl font-black text-xs shadow-lg hover:bg-red-600 transition uppercase">🗑️ <span class="hidden md:inline">Supprimer</span></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 md:py-12">
                        <p class="text-slate-400 font-bold text-base md:text-lg">Aucune publication pour le moment</p>
                        <p class="text-slate-300 text-xs md:text-sm mt-2">Publiez votre premier ouvrage dans la section ci-dessus</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. ONGLET COURS VIDÉO -->
        <div x-show="tab === 'cours'" x-cloak class="space-y-6 md:space-y-8 animate-in fade-in duration-500">
            <!-- Stats Cours -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-amber-500">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Planifiés</span>
                    <span class="text-3xl md:text-4xl font-black text-amber-600">{{ $meetings->where('status', 'scheduled')->count() }}</span>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl text-white">
                    <span class="text-[10px] font-black text-green-100 uppercase mb-2 block">En Direct</span>
                    <span class="text-3xl md:text-4xl font-black">{{ $meetings->where('status', 'active')->count() }}</span>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-xl md:rounded-[2.5rem] shadow-xl border-b-4 border-slate-400">
                    <span class="text-[10px] font-black text-slate-400 uppercase mb-2 block">Terminés</span>
                    <span class="text-3xl md:text-4xl font-black text-slate-600">{{ $meetings->where('status', 'closed')->count() }}</span>
                </div>
            </div>

            <!-- Créer un cours -->
            <div class="bg-white rounded-xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-lg md:text-xl font-black text-slate-800 uppercase mb-6 md:mb-8">🎥 Planifier un Cours Vidéo</h3>
                <form action="{{ route('scheduled-meetings.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    @csrf
                    <input type="text" name="title" placeholder="Titre du cours (ex: Algèbre Linéaire - Chapitre 3)" class="md:col-span-2 w-full bg-slate-50 border-0 rounded-2xl p-3 md:p-4 font-bold text-sm md:text-base" required>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2 mb-2 block">Date et Heure du cours</label>
                        <input type="datetime-local" name="scheduled_at" class="w-full bg-slate-50 border-0 rounded-2xl p-3 md:p-4 font-bold text-sm md:text-base" required>
                    </div>
                    <button type="submit" class="md:col-span-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-4 md:py-5 rounded-2xl md:rounded-[2rem] font-black shadow-xl uppercase text-sm md:text-base hover:from-purple-700 hover:to-indigo-700 transition-all">
                        <i class="fas fa-video mr-2"></i> Créer le Cours
                    </button>
                </form>
            </div>

            <!-- Liste des cours -->
            <div class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8">📋 Mes Cours Vidéo</h3>
                @if($meetings->count() > 0)
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @foreach($meetings as $meeting)
                            <div class="p-6 bg-slate-50 rounded-3xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 group hover:bg-purple-50 transition border-l-4 {{ $meeting->status === 'scheduled' ? 'border-amber-500' : ($meeting->status === 'active' ? 'border-green-500' : 'border-slate-400') }}">
                                <div class="flex-1">
                                    <p class="font-black text-slate-800 text-lg">{{ $meeting->title }}</p>
                                    <div class="flex flex-wrap gap-3 mt-3">
                                        @if($meeting->status === 'scheduled')
                                            <span class="inline-block bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                                <i class="fas fa-clock mr-1"></i> Planifié
                                            </span>
                                        @elseif($meeting->status === 'active')
                                            <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase animate-pulse">
                                                <i class="fas fa-broadcast-tower mr-1"></i> EN DIRECT
                                            </span>
                                        @else
                                            <span class="inline-block bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                                                <i class="fas fa-check mr-1"></i> Terminé
                                            </span>
                                        @endif
                                        <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-bold">
                                            <i class="fas fa-calendar mr-1"></i> {{ $meeting->scheduled_at->format('d/m/Y à H:i') }}
                                        </span>
                                        <span class="inline-block bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-[10px] font-bold font-mono">
                                            {{ $meeting->room_name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    @if($meeting->status !== 'closed')
                                        <a href="{{ route('scheduled-meetings.show', $meeting) }}" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-xs shadow-lg hover:from-purple-700 hover:to-indigo-700 transition uppercase">
                                            <i class="fas fa-play mr-1"></i> Rejoindre
                                        </a>
                                    @endif
                                    @if($meeting->status === 'active')
                                        <form action="{{ route('scheduled-meetings.close', $meeting) }}" method="POST" onsubmit="return confirm('Fermer ce cours ?');">
                                            @csrf
                                            <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-2xl font-black text-xs shadow-lg hover:bg-red-600 transition uppercase">
                                                <i class="fas fa-stop mr-1"></i> Fermer
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('scheduled-meetings.destroy', $meeting) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce cours ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-slate-200 text-slate-600 px-4 py-3 rounded-2xl font-black text-xs shadow-lg hover:bg-red-100 hover:text-red-600 transition uppercase" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mb-6">
                            <i class="fas fa-video text-3xl text-purple-500"></i>
                        </div>
                        <p class="text-slate-400 font-bold text-lg">Aucun cours vidéo pour le moment</p>
                        <p class="text-slate-300 text-sm mt-2">Créez votre premier cours dans la section ci-dessus</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. ONGLET FORUM -->
        <div x-show="tab === 'forum'" x-cloak class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-10 shadow-xl animate-in slide-in-from-bottom-4 duration-500">
            <h3 class="text-xl font-black text-slate-800 uppercase mb-8">Interagir avec les étudiants</h3>
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                @foreach($recentSujets as $sujet)
                    <div class="p-6 bg-slate-50 rounded-3xl flex justify-between items-center group hover:bg-blue-50 transition">
                        <div>
                            <p class="font-black text-slate-800">{{ $sujet->titre }}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold mt-1">{{ $sujet->user->name }} • {{ $sujet->category->nom }}</p>
                        </div>
                        <a href="{{ route('forum.sujet', $sujet->id) }}" class="bg-white text-blue-600 px-6 py-2 rounded-full font-black text-xs shadow-sm group-hover:bg-blue-600 group-hover:text-white transition">RÉPONDRE</a>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. ONGLET PARAMÈTRES (SÉCURITÉ & PROFIL) -->
        <div x-show="tab === 'settings'" x-cloak class="max-w-2xl mx-auto space-y-8 animate-in zoom-in duration-500">
            <div class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-10 shadow-xl">
                <h3 class="text-xl font-black text-slate-800 uppercase mb-8 text-center">Réglages du compte</h3>
                <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Ma Spécialité</label>
                        <input type="text" name="specialty" value="{{ $user->specialty }}" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-black text-blue-600">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Nom Complet</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-bold">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-50 border-0 rounded-2xl p-4 font-bold">
                    </div>

                    <div class="p-6 bg-red-50 rounded-3xl border border-red-100">
                        <p class="text-[10px] font-black text-red-500 uppercase mb-4 tracking-widest">Zone de sécurité</p>
                        <input type="password" name="current_password" placeholder="Mot de passe actuel" class="w-full bg-white border-0 rounded-xl p-4 mb-3 text-sm">
                        <input type="password" name="new_password" placeholder="Nouveau mot de passe" class="w-full bg-white border-0 rounded-xl p-4 mb-3 text-sm">
                        <input type="password" name="new_password_confirmation" placeholder="Confirmer le nouveau" class="w-full bg-white border-0 rounded-xl p-4 text-sm">
                    </div>

                    <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-[2rem] font-black shadow-xl hover:bg-blue-600 transition-all">ENREGISTRER LES MODIFICATIONS</button>
                </form>
            </div>

            <!-- SUPPRESSION COMPTE -->
            <div class="bg-red-50 p-8 rounded-[2rem] border border-red-100 text-center">
                <button onclick="document.getElementById('modal-del').classList.remove('hidden')" class="text-red-500 font-black text-xs uppercase tracking-widest hover:underline">Supprimer mon compte définitivement</button>
            </div>
        </div>
        <!-- ONGLET AIDE -->
        <div x-show="tab === 'aide'" x-cloak class="space-y-6 md:space-y-8">
            <div class="bg-white rounded-xl md:rounded-[3rem] p-6 md:p-10 shadow-xl border border-slate-100">
                <h2 class="text-xl md:text-2xl font-black text-slate-800 uppercase mb-1">Centre d'aide</h2>
                <p class="text-slate-500 text-sm mb-8">Comment utiliser toutes les fonctionnalités de votre espace enseignant.</p>

                <div class="space-y-3">

                    <!-- Bureau -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>📊 Bureau</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Votre tableau de bord principal affiche vos statistiques clés :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Publications</strong> — nombre total de ressources publiées.</li>
                                <li><strong>Mon Solde</strong> — revenus cumulés issus des téléchargements payants.</li>
                                <li><strong>Spécialité</strong> — votre domaine d'enseignement (modifiable dans Paramètres).</li>
                            </ul>
                            <p class="font-bold text-slate-700">Publier un ouvrage :</p>
                            <ol class="list-decimal pl-5 space-y-1">
                                <li>Renseignez le titre de l'ouvrage.</li>
                                <li>Choisissez le type (Livre, Mémoire, Résumé…).</li>
                                <li>Sélectionnez le semestre concerné.</li>
                                <li>Indiquez le prix (0 = gratuit pour les étudiants).</li>
                                <li>Joignez le fichier PDF et une image de couverture.</li>
                                <li>Cliquez sur <strong>Publier</strong> — la ressource est immédiatement disponible.</li>
                            </ol>
                        </div>
                    </details>

                    <!-- Cours -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>🎥 Cours</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Mettez à disposition des cours vidéo ou des liens pour vos étudiants.</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Ajoutez un lien vidéo (YouTube, Google Drive, Loom…).</li>
                                <li>Associez-le à un semestre spécifique.</li>
                                <li>Les étudiants inscrits peuvent y accéder depuis leur espace <strong>Cours</strong>.</li>
                                <li>Vous pouvez modifier ou supprimer un cours à tout moment.</li>
                            </ul>
                        </div>
                    </details>

                    <!-- Cours Particuliers -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>💼 Cours Particuliers</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Proposez des sessions individuelles payantes aux étudiants.</p>
                            <ol class="list-decimal pl-5 space-y-1">
                                <li>Cliquez sur <strong>Cours Particuliers</strong> dans la navigation.</li>
                                <li>Créez une offre : titre, description, durée, tarif.</li>
                                <li>Les étudiants trouvent votre offre et paient via <strong>PayTech</strong> (Wave, Orange Money, carte bancaire…).</li>
                                <li>Une fois le paiement confirmé, une réunion <strong>Jitsi</strong> est programmée automatiquement.</li>
                                <li>Vous recevez une notification par email avec le lien de la réunion.</li>
                            </ol>
                        </div>
                    </details>

                    <!-- Forum -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>💬 Forum</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Interagissez directement avec les étudiants dans l'espace de discussion.</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Les sujets sont organisés par niveau et semestre.</li>
                                <li>Répondez aux questions dans les fils de discussion.</li>
                                <li>Vous pouvez modifier ou supprimer vos propres messages.</li>
                                <li>Accédez au forum complet via le menu principal du site.</li>
                            </ul>
                        </div>
                    </details>

                    <!-- Paramètres -->
                    <details class="group bg-slate-50 rounded-2xl overflow-hidden">
                        <summary class="flex items-center justify-between p-5 cursor-pointer font-black text-slate-700 uppercase text-xs tracking-widest list-none select-none">
                            <span>⚙️ Paramètres</span>
                            <i class="fas fa-chevron-down text-slate-400 transition-transform duration-200 group-open:rotate-180"></i>
                        </summary>
                        <div class="px-5 pb-5 text-slate-600 text-sm space-y-3">
                            <p>Gérez les informations de votre compte :</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Photo de profil</strong> — importez une image JPG/PNG (visible par les étudiants).</li>
                                <li><strong>Spécialité</strong> — indiquez votre domaine d'enseignement.</li>
                                <li><strong>Mot de passe</strong> — modifiez votre mot de passe de connexion (saisissez l'ancien, puis le nouveau).</li>
                                <li><strong>Supprimer le compte</strong> — action irréversible, toutes vos publications seront effacées définitivement.</li>
                            </ul>
                        </div>
                    </details>

                </div>

                <!-- Contact support -->
                <div class="mt-8 bg-teal-50 border border-teal-100 rounded-2xl p-5 flex items-start gap-4">
                    <i class="fas fa-life-ring text-teal-500 text-xl mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="font-black text-slate-800 text-sm uppercase mb-1">Besoin d'aide supplémentaire ?</p>
                        <p class="text-slate-500 text-sm">Contactez l'administrateur de la plateforme via le forum ou l'adresse email de support.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL SUPPRESSION -->
    <div id="modal-del" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-2xl md:rounded-[3rem] p-6 md:p-10 text-center shadow-2xl">
            <h3 class="text-2xl font-black text-slate-900 mb-4 uppercase">Adieu ?</h3>
            <p class="text-slate-500 mb-8">Cette action est irréversible. Toutes vos publications seront supprimées.</p>
            <form action="{{ route('teacher.account.delete') }}" method="POST">
                @csrf @method('DELETE')
                <input type="password" name="password" placeholder="Mot de passe" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 mb-6 text-center font-bold" required>
                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('modal-del').classList.add('hidden')" class="flex-1 bg-slate-100 py-4 rounded-2xl font-bold">ANNULER</button>
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
