<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#1e40af">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="MIO">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <title>Abonnement étudiant — MIO Ressources</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap');
        * { font-family: 'Figtree', sans-serif; }

        body {
            padding-top: env(safe-area-inset-top);
            padding-bottom: env(safe-area-inset-bottom);
            padding-left: env(safe-area-inset-left);
            padding-right: env(safe-area-inset-right);
        }

        .card-popular {
            background: linear-gradient(145deg, #2563eb, #1d4ed8);
            box-shadow: 0 20px 60px rgba(37, 99, 235, 0.45);
        }

        .card-popular:hover {
            box-shadow: 0 25px 70px rgba(37, 99, 235, 0.55);
            transform: translateY(-4px);
        }

        .card-base {
            transition: all 0.25s ease;
        }

        .card-base:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.10);
        }

        .badge-pulse {
            animation: pulse-badge 2s ease-in-out infinite;
        }

        @keyframes pulse-badge {
            0%, 100% { box-shadow: 0 0 0 0 rgba(250, 204, 21, 0.5); }
            50% { box-shadow: 0 0 0 8px rgba(250, 204, 21, 0); }
        }

        .btn-white {
            background: #fff;
            color: #1d4ed8;
            transition: all 0.2s ease;
        }
        .btn-white:hover {
            background: #eff6ff;
            transform: scale(1.02);
        }

        .btn-dark {
            background: #0f172a;
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-dark:hover {
            background: #1e293b;
            transform: scale(1.02);
        }

        .glass-info {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .dot-pattern {
            background-image: radial-gradient(rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .feature-item::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            margin-right: 8px;
            vertical-align: middle;
            flex-shrink: 0;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">

    {{-- HERO --}}
    <div class="dot-pattern relative overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 40%, #2563eb 70%, #3b82f6 100%)">

        {{-- Orbes décoratifs --}}
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-5 pt-12 pb-16 text-center">

            {{-- Logo --}}
            <div class="inline-flex items-center gap-2 bg-white/15 rounded-2xl px-4 py-2 mb-8">
                <img src="/favicon.svg" alt="MIO" class="w-8 h-8 rounded-lg">
                <span class="text-white font-bold text-sm tracking-wide">MIO Ressources</span>
            </div>

            {{-- Titre --}}
            <h1 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4">
                Continue à apprendre<br>
                <span class="text-blue-200">sans interruption</span>
            </h1>
            <p class="text-blue-100 text-base md:text-lg max-w-md mx-auto leading-relaxed mb-8">
                Ton essai gratuit est terminé. Active ton abonnement et garde accès à tous tes cours, groupes et ressources.
            </p>

            {{-- Info statut --}}
            <div class="glass-info inline-flex flex-col sm:flex-row gap-4 rounded-2xl px-6 py-4 text-sm text-white/90">
                <span>
                    <span class="text-white/60 text-xs uppercase tracking-wide block mb-0.5">Essai gratuit terminé</span>
                    <span class="font-semibold">{{ optional($trialEndsAt)->format('d/m/Y') ?? '—' }}</span>
                </span>
                <span class="hidden sm:block w-px bg-white/20"></span>
                <span>
                    <span class="text-white/60 text-xs uppercase tracking-wide block mb-0.5">Abonnement actif jusqu'au</span>
                    <span class="font-semibold">{{ optional($subscriptionPaidUntil)->format('d/m/Y') ?? 'Non actif' }}</span>
                </span>
            </div>

            @if(session('error'))
                <div class="mt-6 mx-auto max-w-md p-4 rounded-2xl text-sm text-center font-medium" style="background:rgba(254,226,226,0.15); border:1px solid rgba(254,202,202,0.4); color:#fecaca;">
                    ⚠️ {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    {{-- CONTENU PRINCIPAL --}}
    <div class="max-w-4xl mx-auto px-5 pt-8 pb-16">

        {{-- CARTES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            {{-- MENSUEL --}}
            <div class="card-base bg-white rounded-3xl border border-slate-100 shadow-sm p-7 flex flex-col">
                <div class="mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Mensuel</p>
                    <div class="flex items-end gap-1 mb-1">
                        <span class="text-4xl font-black text-slate-900">500</span>
                        <span class="text-slate-400 font-semibold mb-1">F CFA</span>
                    </div>
                    <p class="text-slate-400 text-sm">par mois</p>
                </div>

                <ul class="space-y-3 mb-8 flex-1 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Accès complet 1 mois
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Cours, groupes, messagerie
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Sans engagement
                    </li>
                </ul>

                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">
                    <button type="submit" class="btn-dark w-full py-3.5 rounded-2xl font-bold text-sm">
                        Choisir — 500 F
                    </button>
                </form>
            </div>

            {{-- TRIMESTRIEL (Populaire) --}}
            <div class="card-popular rounded-3xl p-7 flex flex-col relative transition-all duration-300">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="badge-pulse bg-yellow-400 text-yellow-900 text-xs font-black px-4 py-1.5 rounded-full shadow-lg inline-block">
                        ⭐ Le plus populaire
                    </span>
                </div>

                <div class="mb-6 mt-2">
                    <p class="text-xs font-bold text-blue-200 uppercase tracking-widest mb-3">Trimestriel</p>
                    <div class="flex items-end gap-1 mb-1">
                        <span class="text-4xl font-black text-white">1 200</span>
                        <span class="text-blue-200 font-semibold mb-1">F CFA</span>
                    </div>
                    <p class="text-blue-200 text-sm">pour 3 mois</p>
                </div>

                <ul class="space-y-3 mb-8 flex-1 text-sm text-blue-100">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Accès complet 3 mois
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Cours, groupes, messagerie
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="text-white font-bold">300 F économisés</span>
                    </li>
                </ul>

                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="quarterly">
                    <button type="submit" class="btn-white w-full py-3.5 rounded-2xl font-black text-sm">
                        Choisir — 1 200 F
                    </button>
                </form>
            </div>

            {{-- ANNUEL --}}
            <div class="card-base bg-white rounded-3xl border border-slate-100 shadow-sm p-7 flex flex-col">
                <div class="mb-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Annuel</p>
                    <div class="flex items-end gap-1 mb-1">
                        <span class="text-4xl font-black text-slate-900">4 000</span>
                        <span class="text-slate-400 font-semibold mb-1">F CFA</span>
                    </div>
                    <p class="text-slate-400 text-sm">pour 12 mois</p>
                </div>

                <ul class="space-y-3 mb-8 flex-1 text-sm text-slate-600">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Accès complet 12 mois
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Cours, groupes, messagerie
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span class="font-bold text-slate-800">2 000 F économisés</span>
                    </li>
                </ul>

                <form action="{{ route('student.subscription.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan" value="annual">
                    <button type="submit" class="btn-dark w-full py-3.5 rounded-2xl font-bold text-sm">
                        Choisir — 4 000 F
                    </button>
                </form>
            </div>

        </div>

        {{-- TRUST --}}
        <div class="mt-10 grid grid-cols-3 gap-4 text-center">
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                <div class="text-2xl mb-1">🔒</div>
                <p class="text-xs font-semibold text-slate-700">Paiement sécurisé</p>
                <p class="text-xs text-slate-400 mt-0.5">via PayTech</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                <div class="text-2xl mb-1">⚡</div>
                <p class="text-xs font-semibold text-slate-700">Accès immédiat</p>
                <p class="text-xs text-slate-400 mt-0.5">après paiement</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
                <div class="text-2xl mb-1">📩</div>
                <p class="text-xs font-semibold text-slate-700">Confirmation email</p>
                <p class="text-xs text-slate-400 mt-0.5">instantanée</p>
            </div>
        </div>

        {{-- RETOUR --}}
        <div class="text-center mt-8">
            <a href="{{ route('home') }}" class="text-sm text-slate-400 hover:text-slate-600 transition-colors">
                ← Retour à l'accueil
            </a>
        </div>

    </div>

</body>
</html>
