<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f1f5f9] text-slate-900 font-sans">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50 shadow-sm">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-blue-700 text-white px-3 py-1 rounded-lg font-bold">MIO</div>
            <span class="font-black text-slate-900 tracking-tight text-lg">MON ESPACE</span>
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('forum.index') }}" class="text-slate-700 hover:text-blue-700 font-bold transition flex items-center gap-2">
                <i class="fas fa-comments"></i> Forum
            </a>

            <form method="POST" action="{{ route('logout') }}"> @csrf
                <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-full font-bold text-sm hover:bg-red-600 hover:text-white transition-all flex items-center gap-2">
                    <i class="fas fa-power-off"></i> Déconnexion
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-6">

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r shadow-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded-r shadow-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- COLONNE GAUCHE : PROFIL PREMIUM -->
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden relative">
                    <div class="h-24 bg-gradient-to-r from-blue-600 to-indigo-700"></div>

                    <div class="relative -mt-12 text-center">
                        <div class="relative inline-block group cursor-pointer">
                            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff' }}"
                                 class="w-28 h-28 rounded-full object-cover border-[5px] border-white shadow-md transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-camera text-white text-xl"></i>
                            </div>
                            <form id="avatar-form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('submit-btn').click()">
                        </div>
                        <h2 class="mt-3 text-xl font-black text-slate-800">{{ $user->name }}</h2>
                        <p class="text-blue-600 font-bold text-xs uppercase tracking-[0.2em] text-center mb-4">
                            Étudiant Membre — <span class="bg-blue-100 px-2 py-0.5 rounded text-blue-700">{{ $user->student_level ?? 'L3' }}</span>
                        </p>
                    </div>

                    <div class="p-8 pt-2">
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Information Personnelle</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-slate-400"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ $user->name }}"
                                           class="pl-10 w-full bg-slate-50 border border-slate-200 text-slate-700 font-bold rounded-xl py-3 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none"
                                           placeholder="Votre nom complet">
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200/60">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <span class="text-sm font-black text-slate-700 uppercase tracking-tight">Sécurité & Connexion</span>
                                </div>

                                <div class="mb-4">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-slate-400"></i>
                                        </div>
                                        <input type="email" name="email" value="{{ $user->email }}"
                                               class="pl-10 w-full bg-white border border-slate-200 text-slate-700 font-medium rounded-xl py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm"
                                               placeholder="Votre email">
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <input type="password" name="current_password" placeholder="Mot de passe actuel" class="w-full bg-white border border-slate-200 text-slate-700 rounded-xl py-3 px-4 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                    <div class="grid grid-cols-2 gap-3">
                                        <input type="password" name="new_password" placeholder="Nouveau" class="w-full bg-white border border-slate-200 text-slate-700 rounded-xl py-3 px-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                        <input type="password" name="new_password_confirmation" placeholder="Confirmer" class="w-full bg-white border border-slate-200 text-slate-700 rounded-xl py-3 px-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                    </div>
                                </div>
                            </div>

                            <button id="submit-btn" type="submit" class="mt-6 w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 rounded-xl shadow-lg transition-all transform active:scale-95 flex justify-center items-center gap-2 uppercase tracking-widest">
                                <i class="fas fa-save"></i> Sauvegarder
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-center">
                    <button onclick="document.getElementById('delete-modal').classList.remove('hidden')" class="text-xs font-bold text-red-400 hover:text-red-600 transition-colors uppercase tracking-widest border-b border-transparent hover:border-red-600 pb-1">
                        Supprimer mon compte définitivement
                    </button>
                </div>
            </div>

            <!-- COLONNE DROITE : ACTIVITÉ INTELLIGENTE -->
            <div class="lg:col-span-8 space-y-8" x-data="{ search: '' }">

                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                    </div>
                    <input type="text" x-model="search" placeholder="Rechercher un cours ou une discussion..."
                           class="w-full bg-white border border-slate-200 rounded-2xl py-4 pl-12 pr-4 shadow-sm text-slate-700 font-bold focus:ring-4 focus:ring-blue-100 focus:border-blue-500 outline-none transition-all">
                </div>

                <!-- Révision instantanée (Jitsi) -->
                <div class="bg-gradient-to-r from-sky-600 to-indigo-700 rounded-3xl p-6 text-white shadow-xl flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center shadow-lg shadow-blue-900/30">
                            <i class="fas fa-video text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.3em] opacity-80">Révision instantanée</p>
                            <h3 class="text-xl font-black leading-tight">Lance une salle vidéo en 1 clic</h3>
                            <p class="text-sm opacity-90">Partage l'écran, discute et révise avec tes camarades sans rien installer.</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('meeting.quick') }}" class="inline-flex items-center gap-2 bg-white text-slate-900 font-black px-5 py-3 rounded-xl shadow-lg shadow-blue-900/30 hover:-translate-y-0.5 hover:shadow-2xl transition">
                            <i class="fas fa-bolt text-amber-500"></i> Démarrer maintenant
                        </a>
                        <a href="https://meet.jit.si" target="_blank" class="inline-flex items-center gap-2 bg-white/10 text-white border border-white/20 px-4 py-3 rounded-xl font-bold hover:bg-white/15 transition">
                            <i class="fas fa-shield-alt"></i> Infos sécurité
                        </a>
                    </div>
                </div>

                <!-- NOUVEAUTÉS -->
                @if(isset($nouveautes) && count($nouveautes) > 0)
                <div class="bg-gradient-to-br from-blue-600 to-indigo-900 rounded-[3rem] p-8 shadow-xl text-white relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
                    <h3 class="text-xs font-black uppercase tracking-[0.3em] mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 bg-yellow-400 rounded-full animate-ping"></span>
                        Nouveautés pour vous ({{ $user->student_level }})
                    </h3>
                    <div class="space-y-4">
                        @foreach($nouveautes as $nouv)
                            <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-3xl flex justify-between items-center hover:bg-white/20 transition-all"
                                 x-show="search === '' || '{{ strtolower($nouv->titre) }}'.includes(search.toLowerCase())">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shadow-lg"><i class="fas {{ $nouv->type == 'Vidéo' ? 'fa-play' : 'fa-file-pdf' }}"></i></div>
                                    <div>
                                        <p class="font-bold text-base leading-tight">{{ $nouv->titre }}
                                            @if($nouv->is_premium)<span class="ml-2 text-[9px] bg-yellow-400 text-slate-900 px-2 py-0.5 rounded-full font-black uppercase">{{ $nouv->price }} CFA</span>@endif
                                        </p>
                                        <p class="text-[10px] opacity-60 uppercase font-black tracking-widest mt-1">{{ $nouv->matiere->nom }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('ressource.download', $nouv->id) }}" target="_blank" class="bg-white text-blue-600 w-10 h-10 rounded-full flex items-center justify-center hover:scale-110 transition shadow-lg"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- HISTORIQUE & ACHATS -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-[0.3em] flex items-center gap-2"><i class="fas fa-history text-blue-500"></i> Historique & Achats</h3>
                        <form action="{{ route('user.history.clear') }}" method="POST">@csrf @method('DELETE')
                            <button class="text-[10px] font-bold text-red-400 hover:text-red-600 bg-red-50 px-3 py-1 rounded-full uppercase">Vider</button>
                        </form>
                    </div>
                    <div class="space-y-3">
                        @forelse($downloads as $download)
                            <div class="flex items-center justify-between group p-4 hover:bg-blue-50/50 rounded-2xl transition border border-transparent hover:border-blue-100" x-show="search === '' || '{{ strtolower($download->titre) }}'.includes(search.toLowerCase())">
                                <div class="flex items-center gap-5">
                                    <div class="w-12 h-12 bg-slate-100 group-hover:bg-white text-slate-500 group-hover:text-blue-600 rounded-xl flex items-center justify-center text-lg shadow-sm"><i class="fas {{ $download->type == 'Vidéo' ? 'fa-play' : 'fa-file-pdf' }}"></i></div>
                                    <div>
                                        <a href="{{ route('ressource.download', $download->id) }}" target="_blank" class="font-black text-slate-800 hover:text-blue-600 block text-base">{{ $download->titre }}</a>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold">{{ $download->matiere->nom }}</span>
                                        @if($download->is_premium)<span class="ml-2 text-[8px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-black uppercase border border-amber-200">⭐ Débloqué</span>@endif
                                    </div>
                                </div>
                                <form action="{{ route('user.history.destroy', $download->id) }}" method="POST">@csrf @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-500 transition shadow-sm"><i class="fas fa-times text-xs"></i></button>
                                </form>
                            </div>
                        @empty
                            <p class="text-center py-8 text-slate-400 italic">Aucun document trouvé</p>
                        @endforelse
                    </div>
                </div>

                <!-- PUBLIER UN MÉMOIRE DE FIN D'ÉTUDES -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-[2rem] p-8 shadow-sm border border-indigo-200">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.3em] mb-6 flex items-center gap-2"><i class="fas fa-book text-indigo-600"></i> Publier votre mémoire de fin d'étude</h3>
                    <form action="{{ route('user.memoire.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <div>
                            <input type="text" name="titre" placeholder="Titre de votre mémoire" class="w-full bg-white border border-slate-200 rounded-xl p-4 font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none" required>
                        </div>
                        <div>
                            <textarea name="description" placeholder="Résumé ou description (optionnel)" class="w-full bg-white border border-slate-200 rounded-xl p-4 text-slate-700 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none resize-none h-20"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 ml-1">Fichier PDF*</label>
                                <input type="file" name="file_path" accept=".pdf" class="w-full text-slate-600 text-sm cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:text-indigo-600 file:bg-indigo-100 hover:file:bg-indigo-200" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase mb-2 ml-1">Image de couverture (optionnel)</label>
                                <input type="file" name="cover_image" accept=".jpg,.jpeg,.png" class="w-full text-slate-600 text-sm cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:font-bold file:text-indigo-600 file:bg-indigo-100 hover:file:bg-indigo-200">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-black py-4 rounded-xl shadow-lg transition-all transform active:scale-95 uppercase tracking-widest">
                            📚 Publier mon mémoire
                        </button>
                    </form>
                </div>

                <!-- HISTORIQUE DES MÉMOIRES PUBLIÉS -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.3em] mb-6 flex items-center gap-2"><i class="fas fa-archive text-indigo-600"></i> Vos mémoires publiés</h3>
                    <div class="space-y-3">
                        @forelse($mesMemoires as $memoire)
                            <div class="flex items-start justify-between group p-5 hover:bg-indigo-50/50 rounded-2xl transition border border-indigo-100 bg-indigo-50/30">
                                <div class="flex-1">
                                    <a href="{{ route('ressource.download', $memoire->id) }}" target="_blank" class="font-black text-slate-800 hover:text-indigo-600 block text-base">{{ $memoire->titre }}</a>
                                    <p class="text-[10px] text-slate-400 uppercase font-bold mt-2">Publié le {{ $memoire->created_at->format('d/m/Y à H:i') }}</p>
                                    @if($memoire->description)
                                        <p class="text-xs text-slate-600 mt-2 line-clamp-2">{{ $memoire->description }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('user.memoire.destroy', $memoire->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce mémoire ?');" class="ml-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-red-50 border border-red-200 text-red-500 hover:text-red-700 hover:bg-red-100 transition shadow-sm flex items-center justify-center" title="Supprimer">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-slate-400 italic font-medium">Aucun mémoire publié pour le moment</p>
                                <p class="text-slate-300 text-sm">Commencez par publier votre mémoire ci-dessus</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- DISCUSSIONS -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="font-black text-slate-800 text-lg flex items-center gap-3"><i class="fas fa-comments text-blue-500"></i> Vos discussions</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse($mesSujets as $sujet)
                            <div class="p-6 flex justify-between items-center hover:bg-slate-50 transition" x-show="search === '' || '{{ strtolower($sujet->titre) }}'.includes(search.toLowerCase())">
                                <div><a href="{{ route('forum.sujet', $sujet->id) }}" class="font-bold text-slate-800 hover:text-blue-600 text-lg block">{{ $sujet->titre }}</a><p class="text-xs text-slate-400 mt-1">{{ $sujet->created_at->diffForHumans() }}</p></div>
                                <form action="{{ route('user.sujet.destroy', $sujet->id) }}" method="POST">@csrf @method('DELETE')
                                    <button class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-red-500 shadow-sm"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @empty
                            <div class="p-12 text-center text-slate-400 italic">Aucune discussion lancée.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL SUPPRESSION -->
    <div id="delete-modal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 text-center shadow-2xl">
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

</body>
</html>
