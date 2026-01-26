<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><title>Merci - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-6 text-white">

    <div class="max-w-lg w-full text-center bg-white/5 backdrop-blur-xl border border-white/10 rounded-[3rem] p-10 shadow-2xl">

        <div class="w-24 h-24 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-[0_0_40px_rgba(34,197,94,0.4)] animate-bounce">
            <i class="fas fa-check text-4xl text-white"></i>
        </div>

        <h1 class="text-4xl font-black uppercase tracking-tighter mb-4">C'est validé !</h1>
        <p class="text-slate-400 font-medium mb-8 leading-relaxed">
            Nous avons envoyé le document et la facture à votre adresse email.
        </p>

        <!-- BOUTON TÉLÉCHARGEMENT DIRECT -->
        @if(session('download_link'))
            <a href="{{ session('download_link') }}" target="_blank" class="block w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-500 transition shadow-lg mb-4">
                <i class="fas fa-download mr-2"></i> Télécharger maintenant
            </a>
        @endif

        <div class="grid grid-cols-2 gap-4 mt-8">
            <a href="/" class="bg-white/10 py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition">Accueil</a>

            @auth
                <a href="{{ route('user.dashboard') }}" class="bg-white/10 py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition">Mon Espace</a>
            @else
                <a href="{{ route('login') }}" class="bg-white/10 py-3 rounded-xl font-bold text-sm hover:bg-white/20 transition">Se connecter</a>
            @endauth
        </div>
    </div>

</body>
</html>
