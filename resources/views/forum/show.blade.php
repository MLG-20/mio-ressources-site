<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sujet->titre }} - Forum MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans">

    <!-- NAVBAR -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="{{ route('forum.index') }}" class="flex items-center gap-2 group">
            <div class="bg-blue-600 text-white px-2 py-1 rounded-lg font-bold transition-transform group-hover:-rotate-6">MIO</div>
            <span class="font-black text-slate-800 tracking-tight">FORUM</span>
        </a>
        <a href="{{ route('forum.category', $sujet->forum_category_id) }}" class="text-slate-500 font-bold hover:text-blue-600 transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour à {{ $sujet->category->nom }}
        </a>
    </nav>

    <main class="max-w-4xl mx-auto py-10 px-6">

        <!-- EN-TÊTE DU SUJET -->
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-200">
                    {{ $sujet->category->nom }}
                </span>
                <span class="text-slate-400 text-xs font-bold uppercase tracking-tighter">
                    Lancé {{ $sujet->created_at->diffForHumans() }}
                </span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter leading-[1.1]">
                {{ $sujet->titre }}
            </h1>
        </div>

        <div class="space-y-10">

            <!-- MESSAGE DE L'AUTEUR (BOX PREMIUM) -->
            <div class="bg-white p-8 md:p-10 rounded-[3rem] border border-slate-200 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-3 h-full bg-blue-600"></div>
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Photo de l'auteur -->
                    <div class="flex-shrink-0">
                        <div class="relative inline-block group">
                            <img src="{{ $sujet->user->avatar ? asset('storage/' . $sujet->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($sujet->user->name) . '&background=0D8ABC&color=fff&size=128' }}"
                                 class="w-20 h-20 rounded-[2rem] object-cover border-4 border-white shadow-2xl transition-transform group-hover:rotate-6"
                                 alt="{{ $sujet->user->name }}">
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-col mb-6">
                            <span class="text-2xl font-black text-slate-900 tracking-tight">{{ $sujet->user->name }}</span>
                            <span class="text-[10px] text-blue-600 font-black uppercase tracking-[0.2em]">Auteur de la discussion</span>
                        </div>
                        <div class="text-slate-700 text-xl leading-relaxed font-medium italic bg-slate-50 p-6 rounded-3xl border border-slate-100">
                            "{{ $sujet->titre }}"
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPTEUR DE RÉPONSES -->
            <div class="flex items-center gap-4 pt-4">
                <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.3em]">
                    {{ $sujet->messages->count() }} Réponses
                </h2>
                <div class="h-px flex-1 bg-slate-100"></div>
            </div>

            <!-- LISTE DES MESSAGES -->
            @foreach($sujet->messages as $message)
                <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col md:flex-row gap-6 hover:shadow-lg transition-shadow duration-300">
                    <!-- Photo de celui qui répond -->
                    <div class="flex-shrink-0">
                        <img src="{{ $message->user->avatar ? asset('storage/' . $message->user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=64748b&color=fff' }}"
                             class="w-14 h-14 rounded-2xl object-cover border border-slate-50 shadow-md"
                             alt="{{ $message->user->name }}">
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="font-black text-slate-800 text-lg tracking-tight">{{ $message->user->name }}</span>
                                <span class="ml-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $message->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Actions sur le message (si c'est le mien) -->
                            @if($message->user_id === Auth::id())
                                <form action="{{ route('forum.message.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce message ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 bg-red-50 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="text-slate-600 leading-relaxed text-lg">
                            {!! nl2br(e($message->contenu)) !!}
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- FORMULAIRE DE RÉPONSE (DESIGN SOMBRE PREMIUM) -->
            <div class="mt-16 bg-slate-900 rounded-[3rem] p-8 md:p-14 shadow-2xl relative overflow-hidden">
                <!-- Décoration en arrière-plan -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl -mr-32 -mt-32"></div>

                <div class="relative z-10">
                    <h3 class="text-3xl font-black text-white tracking-tighter uppercase mb-2 text-center md:text-left">Apportez votre aide</h3>
                    <p class="text-slate-400 mb-8 text-center md:text-left">Votre réponse aidera sûrement d'autres étudiants.</p>

                    <form action="{{ route('forum.sujet.reply', $sujet->id) }}" method="POST">
                        @csrf
                        <textarea name="contenu" rows="5"
                                  class="w-full bg-slate-800/50 border-2 border-slate-700 rounded-[2rem] p-8 text-white text-lg placeholder-slate-500 focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all mb-8"
                                  placeholder="Écrivez votre message ici..." required></textarea>

                        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                            <div class="flex items-center gap-3 text-slate-500">
                                <i class="fas fa-info-circle"></i>
                                <span class="text-xs font-bold uppercase tracking-widest">Restez courtois et précis</span>
                            </div>
                            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-12 py-5 rounded-[2rem] font-black text-xl hover:bg-blue-500 transition-all transform active:scale-95 shadow-xl shadow-blue-600/20">
                                PUBLIER MA RÉPONSE <i class="fas fa-paper-plane ml-3"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-16 text-center">
        <div class="h-px w-24 bg-slate-200 mx-auto mb-8"></div>
        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.3em]">
            MIO Forum &bull; Communauté d'échange
        </p>
    </footer>

</body>
</html>
