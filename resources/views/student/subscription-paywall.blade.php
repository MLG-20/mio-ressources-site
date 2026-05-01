<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Abonnement étudiant - MIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-3xl w-full">

        {{-- En-tête --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900">Abonnement étudiant requis</h1>
            <p class="mt-3 text-slate-600 max-w-xl mx-auto">
                Votre période gratuite de 3 mois est terminée. Choisissez l'offre qui vous convient pour continuer à profiter de votre espace étudiant.
            </p>
        </div>

        {{-- Infos abonnement actuel --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-sm text-slate-700 space-y-1 mb-8 max-w-md mx-auto">
            <p><strong>Fin de l'essai gratuit :</strong> {{ optional($trialEndsAt)->format('d/m/Y H:i') ?? 'Non définie' }}</p>
            <p><strong>Abonnement actif jusqu'au :</strong> {{ optional($subscriptionPaidUntil)->format('d/m/Y H:i') ?? 'Non actif' }}</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        {{-- Cartes d'offres --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            {{-- Mensuel --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Mensuel</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">500 <span class="text-base font-normal text-slate-500">F</span></p>
                    <p class="text-sm text-slate-500 mt-1">par mois</p>
                </div>
                <ul class="text-sm text-slate-600 space-y-2 mb-6 flex-1">
                    <li>✅ Accès complet 1 mois</li>
                    <li>✅ Cours, groupes, messagerie</li>
                    <li>✅ Sans engagement</li>
                </ul>
                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-slate-700 transition">
                        Choisir — 500 F
                    </button>
                </form>
            </div>

            {{-- Trimestriel (Populaire) --}}
            <div class="bg-blue-600 rounded-2xl shadow-xl p-6 flex flex-col relative">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="bg-yellow-400 text-yellow-900 text-xs font-black px-3 py-1 rounded-full shadow">⭐ Populaire</span>
                </div>
                <div class="mb-4">
                    <p class="text-sm font-semibold text-blue-200 uppercase tracking-wide">Trimestriel</p>
                    <p class="text-3xl font-black text-white mt-1">1 200 <span class="text-base font-normal text-blue-200">F</span></p>
                    <p class="text-sm text-blue-200 mt-1">pour 3 mois</p>
                </div>
                <ul class="text-sm text-blue-100 space-y-2 mb-6 flex-1">
                    <li>✅ Accès complet 3 mois</li>
                    <li>✅ Cours, groupes, messagerie</li>
                    <li>🎉 <strong class="text-white">300 F économisés</strong></li>
                </ul>
                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="quarterly">
                    <button type="submit" class="w-full bg-white text-blue-600 py-3 rounded-xl font-black hover:bg-blue-50 transition">
                        Choisir — 1 200 F
                    </button>
                </form>
            </div>

            {{-- Annuel --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col">
                <div class="mb-4">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Annuel</p>
                    <p class="text-3xl font-black text-slate-900 mt-1">4 000 <span class="text-base font-normal text-slate-500">F</span></p>
                    <p class="text-sm text-slate-500 mt-1">pour 12 mois</p>
                </div>
                <ul class="text-sm text-slate-600 space-y-2 mb-6 flex-1">
                    <li>✅ Accès complet 12 mois</li>
                    <li>✅ Cours, groupes, messagerie</li>
                    <li>🏆 <strong>2 000 F économisés</strong></li>
                </ul>
                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="annual">
                    <button type="submit" class="w-full bg-slate-900 text-white py-3 rounded-xl font-bold hover:bg-slate-700 transition">
                        Choisir — 4 000 F
                    </button>
                </form>
            </div>

        </div>

        {{-- Retour accueil --}}
        <div class="text-center">
            <a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-slate-700 underline">
                Retour à l'accueil
            </a>
        </div>

    </div>
</body>
</html>
