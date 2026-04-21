<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $page->titre }} - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        (function () {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = saved || (prefersDark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>
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
    html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
    html.dark .bg-white { background-color: #0f172a !important; }
    html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
    html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400 { color: #cbd5e1 !important; }
    html.dark .border-slate-100, html.dark .border-slate-200, html.dark .border-white { border-color: #334155 !important; }
</style>
</head>
<body class="bg-white text-slate-900 font-sans overflow-x-hidden transition-colors duration-300">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <!-- NAVBAR MINI -->
    <nav class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-700 py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 transition-colors duration-300">
        <a href="/" class="flex items-center gap-2 group">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="font-black text-slate-800 dark:text-slate-100 tracking-tight uppercase">Ressources</span>
        </a>
        <a href="/" class="text-slate-500 dark:text-slate-300 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-home"></i> <span>Accueil</span>
        </a>
    </nav>

    <!-- HEADER DE LA PAGE -->
    <header class="py-12 md:py-20 bg-slate-900 text-white relative overflow-hidden">
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
    <main class="max-w-4xl mx-auto py-10 md:py-20 px-6 bg-white dark:bg-slate-950">
        <div class="prose prose-slate lg:prose-lg max-w-none text-slate-700 dark:text-slate-100 leading-relaxed">
            {!! $page->contenu !!}
        </div>


    {{-- SECTION CONTACT & CARTE côte à côte (uniquement sur la page À propos) --}}
    @if($page->slug === 'a-propos')
        <section class="mt-6 mb-6 md:mt-24 md:mb-16">
            <div class="flex flex-col md:flex-row gap-4 md:gap-16 items-stretch w-full max-w-full md:max-w-4xl mx-0 md:mx-auto px-0 md:px-6">
                {{-- Bloc Contact --}}
                <div class="flex-1 bg-white dark:bg-slate-900 rounded-none md:rounded-[2rem] shadow-2xl border-0 md:border-4 border-blue-100 dark:border-slate-700 p-2 md:p-12 flex flex-col justify-center mb-4 md:mb-0">
                    <h2 class="text-3xl font-black text-blue-700 dark:text-blue-300 mb-8 uppercase tracking-tight text-center">Contactez-nous</h2>
                    @if(session('contact_success'))
                        <div class="mb-6 p-4 bg-blue-600 text-white rounded-2xl font-bold text-center animate-bounce">{{ session('contact_success') }}</div>
                    @endif
                    <form action="{{ route('contact.send') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-2">Nom</label>
                                <input type="text" id="nom" name="nom" required class="w-full rounded-xl bg-white dark:bg-slate-800 border border-blue-200 dark:border-slate-700 p-4 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" id="email" name="email" required class="w-full rounded-xl bg-white dark:bg-slate-800 border border-blue-200 dark:border-slate-700 p-4 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label for="message" class="block text-xs font-bold uppercase text-slate-600 dark:text-slate-300 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required class="w-full rounded-xl bg-white dark:bg-slate-800 border border-blue-200 dark:border-slate-700 p-4 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        </div>
                        <div class="text-center mt-8">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-10 rounded-full shadow-xl uppercase tracking-widest transition-all">Envoyer</button>
                        </div>
                    </form>
                </div>
                {{-- Bloc Carte --}}
                @if(isset($settings['univ_map']))
                <div class="flex-1 bg-white dark:bg-slate-900 rounded-none md:rounded-[2rem] shadow-2xl border-0 md:border-4 border-blue-100 dark:border-slate-700 p-2 md:p-12 flex flex-col justify-center">
                    <h2 class="text-3xl font-black text-blue-700 dark:text-blue-300 mb-8 uppercase tracking-tight text-center">Nous trouver</h2>
                    <div class="relative w-full" style="padding-bottom: 56.25%; min-height:220px;">
                        <div class="absolute inset-0 rounded-none md:rounded-[2rem] overflow-hidden shadow-xl border-0 md:border-4 border-white ring-0 md:ring-1 ring-slate-200">
                            {!! $settings['univ_map'] !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
    @endif
    </main>

    @include('layouts.footer')

    <!-- Style pour forcer l'iframe Google Maps à être responsive -->
    <style>
        /* Responsive Google Maps et suppression des marges sur mobile */
        .prose iframe,
        section iframe {
            width: 100% !important;
            height: 100% !important;
            min-height: 220px;
            border: 0;
            display: block;
        }
        @media (max-width: 768px) {
            .flex.md\:flex-row {
                flex-direction: column !important;
            }
            .flex-1 {
                width: 100% !important;
                max-width: 100% !important;
            }
            section > div.flex {
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .rounded-none {
                border-radius: 0 !important;
            }
            .border-0 {
                border-width: 0 !important;
            }
            .p-2 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }
    </style>

</body>
@include('components.theme-toggle-script')
</html>
