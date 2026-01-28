<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement Réussi - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        @keyframes pulse-border {
            0%, 100% { border-color: rgba(34, 197, 94, 0.5); }
            50% { border-color: rgba(34, 197, 94, 1); }
        }
        .pulse-border { animation: pulse-border 2s infinite; }
    </style>
    <!-- Redirection automatique vers l'espace étudiant immédiatement -->
    <meta http-equiv="refresh" content="1;url={{ route('user.dashboard', [], false) }}#courses">
</head>
<body class="bg-[#f8fafc]">

    <div class="min-h-screen flex items-center justify-center p-3 md:p-4">
        <div class="bg-white rounded-2xl md:rounded-[3rem] p-6 md:p-12 max-w-lg w-full shadow-2xl text-center border-4 pulse-border border-green-500">

            <div class="w-16 md:w-24 h-16 md:h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                <i class="fas fa-check text-green-600 text-3xl md:text-5xl"></i>
            </div>

            <h1 class="text-2xl md:text-4xl font-black text-slate-900 mb-4">✅ Paiement Réussi !</h1>
            <p class="text-sm md:text-base text-slate-600 mb-6 md:mb-8">
                Votre réservation a été confirmée et ajoutée à votre espace étudiant.
            </p>

            <div class="bg-green-50 rounded-lg md:rounded-2xl p-4 md:p-6 mb-6 md:mb-8 text-left border-l-4 border-green-600">
                <h3 class="font-black text-green-900 mb-3 text-sm md:text-base">✓ Confirmation</h3>
                <ul class="space-y-2 text-xs md:text-sm text-green-800">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-circle-check text-green-600"></i>
                        <span>Inscription confirmée et payée</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-circle-check text-green-600"></i>
                        <span>Email de confirmation envoyé</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-circle-check text-green-600"></i>
                        <span>Visible dans "Mes Cours Particuliers"</span>
                    </li>
                </ul>
            </div>

            <div class="bg-blue-50 rounded-lg md:rounded-2xl p-4 md:p-6 mb-6 md:mb-8">
                <p class="text-xs md:text-sm text-blue-800 font-bold">
                    ⏱️ Redirection automatique dans <span class="countdown">1</span> seconde...
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('user.dashboard', [], false) }}?tab=courses#courses" class="flex-1 bg-green-600 text-white py-3 md:py-4 rounded-lg md:rounded-2xl font-bold uppercase text-xs md:text-sm shadow-lg hover:bg-green-700 transition flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-right"></i>
                    Mon Espace Maintenant
                </a>
                <a href="{{ route('private-lessons.browse') }}" class="flex-1 bg-slate-200 text-slate-700 py-3 md:py-4 rounded-lg md:rounded-2xl font-bold uppercase text-xs md:text-sm hover:bg-slate-300 transition">
                    Autres Cours
                </a>
            </div>
        </div>
    </div>

    <script>
        // Compte à rebours visible
        let countdown = 1;
        const countdownElement = document.querySelector('.countdown');

        const interval = setInterval(() => {
            countdown--;
            if (countdownElement) {
                countdownElement.textContent = countdown;
            }
            if (countdown <= 0) {
                clearInterval(interval);
                // Redirection manuelle au cas où
                window.location.href = "{{ route('user.dashboard', [], false) }}#courses";
            }
        }, 1000);
    </script>

</body>
</html>
