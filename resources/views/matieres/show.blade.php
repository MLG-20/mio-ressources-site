<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $matiere->nom }} - MIO Ressources</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] text-slate-900 font-sans">

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2 group">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="font-black text-slate-800 tracking-tight">RESSOURCES</span>
        </a>
        <a href="{{ route('semestre.show', $matiere->semestre_id) }}" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            <span class="hidden sm:inline">Retour au {{ $matiere->semestre->nom }}</span>
        </a>
    </nav>

    @if(session('error'))
    <div class="mx-auto max-w-5xl px-6 mt-4">
        <div class="rounded-2xl border border-red-200 bg-red-50 text-red-700 px-4 py-3 font-medium">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="mx-auto max-w-5xl px-6 mt-4">
        <div class="rounded-2xl border border-green-200 bg-green-50 text-green-700 px-4 py-3 font-medium">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- HEADER MATIÈRE -->
    <header class="py-16 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto px-6 text-center md:text-left">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <div>
                    <span class="bg-blue-100 text-blue-700 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest">{{ $matiere->code }}</span>
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 mt-4 tracking-tighter">{{ $matiere->nom }}</h1>
                    <p class="text-slate-500 mt-4 text-lg font-medium">Bibliothèque des supports pédagogiques officiels</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100 hidden lg:block">
                    <span class="block text-2xl font-black text-blue-600">{{ $matiere->ressources->count() }}</span>
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">Documents</span>
                </div>
            </div>
        </div>
    </header>

    <main class="py-12 max-w-5xl mx-auto px-6">

        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-3">
            <i class="fas fa-folder-open text-blue-500"></i> Ressources disponibles
        </h2>

        <div class="grid gap-4">
            @forelse($matiere->ressources as $ressource)
                <div class="group bg-white p-5 rounded-3xl border border-slate-200 shadow-sm flex flex-col sm:flex-row justify-between items-center hover:shadow-xl hover:border-blue-400 transition-all duration-300">
                    <div class="flex items-center gap-5 w-full sm:w-auto">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-colors duration-300 {{ $ressource->type == 'Vidéo' ? 'bg-red-50 text-red-600 group-hover:bg-red-600' : 'bg-blue-50 text-blue-600 group-hover:bg-blue-600' }} group-hover:text-white">
                            @if($ressource->type == 'Vidéo')
                                <i class="fas fa-play text-xl"></i>
                            @elseif($ressource->type == 'Cours')
                                <i class="fas fa-book text-xl"></i>
                            @else
                                <i class="fas fa-file-lines text-xl"></i>
                            @endif
                        </div>

                        <div>
                            <h3 class="font-black text-slate-800 text-lg group-hover:text-blue-600 transition-colors">{{ $ressource->titre }}</h3>

                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $ressource->type }}</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>

                                <!-- 1. INSERTION DU BADGE PREMIUM / GRATUIT ICI -->
                                @if($ressource->is_premium)
                                    <div class="flex items-center gap-2">
                                        <span class="bg-amber-100 text-amber-700 px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest border border-amber-200 flex items-center gap-1">
                                            <i class="fas fa-star text-[8px]"></i> Premium
                                        </span>
                                        <span class="text-slate-900 font-black text-xs">{{ number_format($ressource->price, 0, ',', ' ') }} CFA</span>
                                    </div>
                                @else
                                    <span class="text-emerald-500 font-bold text-[10px] uppercase tracking-tight flex items-center gap-1">
                                        <i class="fas fa-check-circle text-[8px]"></i> Gratuit
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-0 w-full sm:w-auto">
                        <!-- 2. INSERTION DE LA LOGIQUE DE BOUTON (OUVRIR vs ACHETER) -->
                        @php
                            $hasPurchased = false;
                            if(auth()->check() && $ressource->is_premium) {
                                // On vérifie si l'achat existe dans la table
                                $hasPurchased = \App\Models\Purchase::where('user_id', auth()->id())
                                                ->where('ressource_id', $ressource->id)
                                                ->exists();
                            }
                        @endphp

                        @if(!$ressource->is_premium || $hasPurchased)
                            <!-- BOUTON ACCÈS LIBRE -->
                            <div class="flex gap-2 w-full">
                                @php
                                    $extension = strtolower(pathinfo($ressource->file_path, PATHINFO_EXTENSION));
                                    $isPdf = $extension === 'pdf';
                                @endphp

                                @if($isPdf && $ressource->type !== 'Vidéo')
                                    <!-- Bouton Voir (pour PDF) -->
                                    <a href="{{ route('ressource.view', $ressource->id) }}"
                                       target="_blank"
                                       class="flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg flex-1">
                                        <i class="fas fa-eye"></i>
                                        Voir
                                    </a>
                                    <!-- Bouton Télécharger -->
                                    <a href="{{ auth()->check() ? route('ressource.download', $ressource->id) : (asset('storage/' . $ressource->file_path)) }}"
                                       class="flex items-center justify-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold hover:bg-slate-700 transition-all shadow-lg flex-1">
                                        <i class="fas fa-download"></i>
                                        Télécharger
                                    </a>
                                @else
                                    <!-- Bouton Ouvrir/Regarder (pour autres fichiers/vidéos) -->
                                    <a href="{{ auth()->check() ? route('ressource.download', $ressource->id) : (asset('storage/' . $ressource->file_path)) }}"
                                       target="_blank"
                                       class="flex items-center justify-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-200 w-full">
                                        <i class="fas {{ $ressource->type == 'Vidéo' ? 'fa-play-circle' : 'fa-cloud-download-alt' }}"></i>
                                        {{ $ressource->type == 'Vidéo' ? 'Regarder' : 'Ouvrir' }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <!-- BOUTON ACHAT -->
                            @auth
                                <a href="{{ route('payment.pay', [$ressource->id, 'ressource']) }}" class="flex items-center justify-center gap-2 bg-amber-500 text-white px-8 py-3 rounded-2xl font-bold hover:bg-amber-600 transition-all">
                                    <i class="fas fa-shopping-cart"></i> Débloquer ({{ $ressource->price }} CFA)
                                </a>
                            @else
                                <button type="button" class="flex items-center justify-center gap-2 bg-amber-500 text-white px-8 py-3 rounded-2xl font-bold hover:bg-amber-600 transition-all w-full" onclick="openGuestCheckoutModal({{ $ressource->id }}, 'ressource')">
                                    <i class="fas fa-shopping-cart"></i> Débloquer ({{ $ressource->price }} CFA)
                                </button>
                            @endauth
                        @endif
                    </div>
                </div>

                @auth
                    @if($ressource->is_premium)
                        <div class="mt-2 px-5 pb-5">
                            <x-rate-item :itemId="$ressource->id" type="ressource" />
                        </div>
                     @endif
                @endauth

            @empty
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-slate-300">
                    <i class="fas fa-box-open text-5xl text-slate-200 mb-4"></i>
                    <p class="text-slate-400 font-bold tracking-tight">Aucun document n'a été publié pour cette matière.</p>
                </div>
            @endforelse
        </div>

    </main>

    @include('layouts.footer')

    <!-- MODAL EMAIL POUR VISITEURS NON CONNECTÉS -->
    <div id="guestCheckoutModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
            <h2 class="text-2xl font-black text-slate-800 mb-2">Débloquer le document</h2>
            <p class="text-slate-600 text-sm mb-6">Entrez votre email pour procéder au paiement</p>

            <form id="guestCheckoutForm" method="GET">
                <input type="email" name="guest_email" placeholder="votre@email.com"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl mb-4 focus:outline-none focus:ring-2 focus:ring-amber-500"
                       required>

                <div class="flex gap-3">
                    <button type="button" onclick="closeGuestCheckoutModal()"
                            class="flex-1 px-4 py-3 border border-slate-200 rounded-xl font-bold text-slate-800 hover:bg-slate-50 transition">
                        Annuler
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-amber-500 text-white rounded-xl font-bold hover:bg-amber-600 transition">
                        Continuer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let pendingResourceId = null;
        let pendingResourceType = null;

        function openGuestCheckoutModal(id, type) {
            pendingResourceId = id;
            pendingResourceType = type;
            document.getElementById('guestCheckoutModal').classList.remove('hidden');
        }

        function closeGuestCheckoutModal() {
            document.getElementById('guestCheckoutModal').classList.add('hidden');
            pendingResourceId = null;
            pendingResourceType = null;
        }

        document.getElementById('guestCheckoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = document.querySelector('input[name="guest_email"]').value;
            window.location.href = `{{ route('payment.pay', ['ID', 'TYPE']) }}`
                .replace('ID', pendingResourceId)
                .replace('TYPE', pendingResourceType) +
                `?guest_email=${encodeURIComponent(email)}`;
        });
    </script>

</body>
</html>
