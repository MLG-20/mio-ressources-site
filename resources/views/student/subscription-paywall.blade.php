<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Abonnement étudiant - MIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl border border-slate-200 p-8">
        <div class="text-center">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900">Abonnement étudiant requis</h1>
            <p class="mt-3 text-slate-600">
                Votre période gratuite de 3 mois est terminée. Pour continuer a utiliser votre dashboard étudiant,
                activez votre abonnement à <strong>500 F / mois</strong>.
            </p>
        </div>

        <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-200 p-4 text-sm text-slate-700 space-y-2">
            <p><strong>Fin de l'essai gratuit :</strong> {{ optional($trialEndsAt)->format('d/m/Y H:i') ?? 'Non définie' }}</p>
            <p><strong>Abonnement actif jusqu'au :</strong> {{ optional($subscriptionPaidUntil)->format('d/m/Y H:i') ?? 'Non actif' }}</p>
            <p><strong>Montant :</strong> 500 F / mois</p>
        </div>

        @if(session('error'))
            <div class="mt-4 p-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('home') }}" class="flex-1 text-center bg-slate-900 text-white py-3 rounded-xl font-bold">
                Retour à l'accueil
            </a>
            <form action="{{ route('student.subscription.pay') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full text-center bg-blue-600 text-white py-3 rounded-xl font-bold">
                    Payer avec PayTech (500 F)
                </button>
            </form>
        </div>
    </div>
</body>
</html>
