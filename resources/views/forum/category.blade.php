<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->nom }} - Forum MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-900">

    <nav class="bg-white border-b py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="{{ route('forum.index') }}" class="flex items-center gap-2">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="font-black text-slate-800 uppercase">Forum</span>
        </a>
        <a href="{{ route('forum.index') }}" class="text-slate-500 font-bold hover:text-blue-600">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux catégories
        </a>
    </nav>

    <header class="bg-white border-b py-10 px-6">
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 uppercase tracking-tighter">{{ $category->nom }}</h1>
                <p class="text-slate-500 mt-1">{{ $category->description }}</p>
            </div>
            <!-- BOUTON NOUVEAU SUJET -->
            <button onclick="document.getElementById('modal-sujet').classList.remove('hidden')" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">
                <i class="fas fa-plus mr-2"></i> NOUVELLE DISCUSSION
            </button>
        </div>
    </header>

    <main class="max-w-5xl mx-auto py-10 px-6">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400">Discussions</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hidden md:table-cell">Réponses</th>
                        <th class="px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400 text-right">Dernière activité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sujets as $sujet)
                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer group">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                        <i class="fas {{ $sujet->est_epingle ? 'fa-thumbtack' : 'fa-comment' }}"></i>
                                    </div>
                                    <div>
                                        <a href="{{ route('forum.sujet', $sujet->id) }}" class="text-lg font-bold text-slate-800 group-hover:text-blue-600 block">
                                            {{ $sujet->titre }}
                                        </a>
                                        <span class="text-xs text-slate-400 font-medium">Par <strong>{{ $sujet->user->name }}</strong></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 hidden md:table-cell">
                                <span class="bg-slate-100 px-3 py-1 rounded-full text-xs font-black text-slate-600">
                                    {{ $sujet->messages_count }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right text-xs text-slate-400 font-bold uppercase tracking-tighter">
                                {{ $sujet->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center text-slate-400 italic">
                                <i class="fas fa-ghost text-4xl mb-4 block"></i>
                                Aucune discussion pour le moment. Soyez le premier !
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $sujets->links() }}
        </div>
    </main>

    <!-- MODAL NOUVEAU SUJET (Caché par défaut) -->
    <div id="modal-sujet" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-6">
        <div class="bg-white w-full max-w-xl rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-8 border-b flex justify-between items-center">
                <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase">Lancer une discussion</h2>
                <button onclick="document.getElementById('modal-sujet').classList.add('hidden')" class="text-slate-400 hover:text-red-500 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="{{ route('forum.message.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="forum_category_id" value="{{ $category->id }}">
                <div>
                    <label class="block text-xs font-black uppercase text-slate-400 mb-2">Titre de votre question</label>
                    <input type="text" name="titre" placeholder="Ex: Quelqu'un a le cours de maths ?" class="w-full bg-slate-50 border-0 rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-bold" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-lg hover:bg-blue-700 transition-all">
                    PUBLIER MAINTENANT
                </button>
            </form>
        </div>
    </div>

</body>
</html>
