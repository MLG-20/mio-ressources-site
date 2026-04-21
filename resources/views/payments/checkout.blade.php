<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Paiement PayTech &bull; MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap'); body { font-family: 'Outfit', sans-serif; }</style>
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-6">

    <div class="max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 bg-white rounded-[3rem] shadow-2xl overflow-hidden">

        <!-- RÉCAPITULATIF (Gauche) -->
        <div class="p-12 bg-slate-950 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/20 rounded-full blur-[80px]"></div>

            <a href="/" class="text-slate-500 hover:text-white transition font-black text-xs uppercase tracking-widest mb-12 block">
                <i class="fas fa-arrow-left"></i> Annuler
            </a>

            <span class="text-blue-500 font-black text-xs uppercase tracking-widest mb-4 block">Votre Panier</span>
            <h1 class="text-4xl font-black leading-tight mb-4">{{ $item->titre }}</h1>
            <div class="flex items-baseline gap-2">
                <span class="text-6xl font-black">{{ $item->price }}</span>
                <span class="text-xl font-bold text-blue-500">CFA</span>
            </div>
        </div>

        <!-- ACTION (Droite) -->
        <div class="p-12 flex flex-col justify-center items-center text-center">
            <img src="https://paytech.sn/assets/img/logo.png" class="h-12 mb-8" alt="PayTech">

            <h2 class="text-2xl font-black text-slate-800 mb-2">Paiement Sécurisé</h2>
            <p class="text-slate-400 text-sm mb-10 px-8">Vous allez être redirigé vers PayTech pour choisir votre méthode (Wave, Orange Money, Carte).</p>

            @guest
                <form method="GET" action="{{ route('payment.pay', ['id' => $item->id, 'type' => $type]) }}" class="w-full">
                    <input type="email" name="guest_email" required
                           class="w-full mb-4 px-6 py-4 rounded-[1.5rem] border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-600"
                           placeholder="Votre email (pour recevoir le document)" value="{{ old('guest_email') }}">
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-5 rounded-[2rem] font-black text-lg shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                        PROCÉDER AU PAIEMENT
                    </button>
                </form>
            @else
                <a href="{{ route('payment.pay', ['id' => $item->id, 'type' => $type]) }}"
                   class="w-full bg-blue-600 text-white py-5 rounded-[2rem] font-black text-lg shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                    PROCÉDER AU PAIEMENT
                </a>
            @endguest

            <div class="mt-8 flex gap-4 opacity-40 grayscale">
                <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6">
            </div>
        </div>
    </div>

</body>
</html>
