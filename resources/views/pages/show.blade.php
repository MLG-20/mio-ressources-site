<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->titre }} - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* Masquer les légendes automatiques de l'éditeur (nom du fichier et poids) */
    .prose figcaption {
        display: none !important;
    }

    /* Améliorer l'apparence des images pour qu'elles soient "Pro" */
    .prose img {
        max-width: 100% !important;
        width: 100% !important;
        height: auto !important;
        border-radius: 2rem;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        margin: 3rem auto !important;
        display: block !important;
    }

    /* Responsive images dans la prose */
    @media (max-width: 768px) {
        .prose img {
            max-width: 100% !important;
            margin: 2rem auto !important;
        }
    }

    /* S'assurer que les figures avec images ne cassent pas */
    .prose figure {
        margin: 3rem auto !important;
        max-width: 100% !important;
    }

    .prose figure img {
        display: block !important;
    }
</style>
</head>
<body class="bg-white text-slate-900 font-sans">

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <a href="/" class="flex items-center gap-2 group">
            <div class="bg-blue-600 text-white px-2 py-1 rounded-lg font-bold transition-transform group-hover:-rotate-6">MIO</div>
            <span class="font-black text-slate-800 tracking-tight uppercase">Ressources</span>
        </a>
        <a href="/" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-home"></i> <span>Accueil</span>
        </a>
    </nav>

    <!-- HEADER DE LA PAGE -->
    <header class="py-20 bg-slate-900 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl -mr-32 -mt-32"></div>
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center md:text-left">
            <h1 class="text-4xl md:text-7xl font-black tracking-tighter leading-tight uppercase">{{ $page->titre }}</h1>
            <div class="h-1.5 w-24 bg-blue-500 mt-8 rounded-full mx-auto md:mx-0"></div>
        </div>
    </header>

    <!-- IMAGE VEDETTE (si elle existe) -->
    @if($page->cover_image)
        <div class="max-w-4xl mx-auto px-6 py-8">
            <img src="{{ asset('storage/' . $page->cover_image) }}"
                 alt="{{ $page->titre }}"
                 class="w-full h-auto rounded-[2rem] shadow-2xl border-4 border-white">
        </div>
    @endif

    <!-- CONTENU DYNAMIQUE -->
    <main class="max-w-4xl mx-auto py-20 px-6">
        <div class="prose prose-slate lg:prose-lg max-w-none text-slate-700 leading-relaxed">
            {!! $page->contenu !!}
        </div>

        <!-- SECTION CARTE (Affichée seulement sur la page À Propos) -->
        @if($page->slug === 'a-propos' && isset($settings['univ_map']))
            <div class="mt-24">
                <h2 class="text-3xl font-black text-slate-900 mb-8 uppercase tracking-tight">Nous trouver</h2>
                <div class="rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white ring-1 ring-slate-200 h-[450px] w-full">
                    {!! $settings['univ_map'] !!}
                </div>
            </div>
        @endif
    </main>

    <!-- FOOTER SIMPLE -->
    <footer class="bg-slate-50 py-16 px-8 border-t border-slate-100">
        <div class="max-w-4xl mx-auto text-center">
            <p class="text-slate-400 text-xs font-black uppercase tracking-[0.3em]">
                MIO Ressources &bull; Université Iba Der Thiam &bull; {{ date('Y') }}
            </p>
        </div>
    </footer>

    <!-- Style pour forcer l'iframe Google Maps à être responsive -->
    <style>
        iframe { width: 100% !important; height: 100% !important; border: 0; }
    </style>

</body>
</html>
