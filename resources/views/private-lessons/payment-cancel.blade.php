<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Paiement Annulé - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc]">

    <div class="min-h-screen flex items-center justify-center p-3 md:p-4">
        <div class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-12 max-w-lg w-full shadow-2xl text-center">

            <div class="w-16 md:w-24 h-16 md:h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                <i class="fas fa-exclamation-triangle text-amber-600 text-3xl md:text-5xl"></i>
            </div>

            <h1 class="text-2xl md:text-4xl font-black text-slate-900 mb-4">Paiement Annulé</h1>
            <p class="text-sm md:text-base text-slate-600 mb-6 md:mb-8">
                Votre paiement a été annulé. Aucun montant n'a été débité de votre compte.
            </p>

            <div class="bg-amber-50 rounded-lg md:rounded-2xl p-4 md:p-6 mb-6 md:mb-8">
                <p class="text-xs md:text-sm text-amber-800 font-bold">
                    💡 Vous pouvez réessayer à tout moment en retournant sur la page du cours.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('private-lessons.browse') }}" class="flex-1 bg-blue-600 text-white py-3 md:py-4 rounded-lg md:rounded-2xl font-bold uppercase text-xs md:text-sm shadow-lg hover:bg-blue-700 transition">
                    Voir les Cours
                </a>
                <a href="{{ route('user.dashboard') }}" class="flex-1 bg-slate-200 text-slate-700 py-3 md:py-4 rounded-lg md:rounded-2xl font-bold uppercase text-xs md:text-sm hover:bg-slate-300 transition">
                    Mon Espace
                </a>
            </div>
        </div>
    </div>

</body>
</html>
