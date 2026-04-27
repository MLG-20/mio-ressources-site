<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Accès MIO Ressources</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #2563eb; border-radius: 10px; }
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white { background-color: #0f172a !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-400, html.dark .text-slate-300 { color: #cbd5e1 !important; }
        html.dark .border-slate-50 { border-color: #334155 !important; }
    </style>
</head>
<body class="antialiased bg-white text-slate-900 transition-colors duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 100)">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[120] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <!-- Bouton Remonter -->
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})" x-show="scrolled" x-cloak
            class="fixed bottom-8 right-8 z-[110] bg-blue-600 text-white w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:bg-blue-700 transition-all transform hover:-translate-y-1">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Wrapper Principal : items-stretch est la clé -->
    <div class="min-h-screen flex flex-col lg:flex-row w-full items-stretch" style="padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); padding-left: env(safe-area-inset-left); padding-right: env(safe-area-inset-right);">

        <!-- CÔTÉ GAUCHE : FORMULAIRE -->
        <div class="w-full lg:w-[45%] flex flex-col bg-white dark:bg-slate-900 relative z-10 shadow-2xl transition-colors duration-300">

            <!-- Navbar interne -->
            <div class="p-6 md:px-12 flex justify-between items-center">
                <a href="/" class="flex items-center gap-2 group">
                    <x-application-logo class="w-8 h-8" />
                    <span class="text-base font-black tracking-tighter text-slate-800 dark:text-slate-100 uppercase">MIO <span class="font-light text-slate-400 dark:text-slate-300">Ressources</span></span>
                </a>
                <a href="/" class="text-[10px] font-black text-slate-400 dark:text-slate-300 hover:text-blue-600 transition uppercase tracking-widest">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
            </div>

            <!-- Centre du contenu -->
            <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 md:px-20 py-12">
                <div class="w-full max-w-md mx-auto">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer gauche -->
            <div class="p-6 text-center border-t border-slate-50 dark:border-slate-700">
                <p class="text-slate-300 dark:text-slate-400 text-[9px] font-bold uppercase tracking-[0.3em]">&copy; {{ date('Y') }} MIO RESSOURCES</p>
            </div>
        </div>

        <!-- CÔTÉ DROIT : IMAGE DYNAMIQUE (Totalement remplie) -->
        <div class="hidden lg:flex relative flex-1 bg-slate-900 overflow-hidden">
            @php
                $bgImage = $globalSettings['auth_bg_image'] ?? null;
                if ($bgImage && !str_starts_with($bgImage, 'http')) {
                    $bgImage = asset('storage/' . $bgImage);
                }
                $finalBg = $bgImage ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070';
            @endphp

            <!-- L'image utilise h-full pour s'étirer sur toute la hauteur de la colonne gauche -->
            <img class="absolute inset-0 h-full w-full object-cover opacity-50 transform scale-105"
                 src="{{ $finalBg }}" alt="Branding">

            <div class="absolute inset-0 bg-gradient-to-br from-blue-900/90 via-transparent to-black/60"></div>

            <!-- Texte Centré -->
            <div class="relative z-10 w-full px-20 flex flex-col justify-center">
                <div class="h-1.5 w-16 bg-blue-500 mb-8 rounded-full shadow-lg shadow-blue-500/50"></div>

                <h2 class="text-6xl font-black text-white leading-[0.95] tracking-tighter mb-8 uppercase drop-shadow-2xl">
                    {!! nl2br(e($globalSettings['auth_title'] ?? 'L\'excellence commence ici.')) !!}
                </h2>

                <p class="text-blue-100 text-xl font-light leading-relaxed italic border-l-4 border-blue-500/30 pl-8">
                    "{{ $globalSettings['auth_description'] ?? 'Simplifier l\'accès au savoir pour bâtir l\'avenir de chaque étudiant.' }}"
                </p>

                <div class="flex gap-6 mt-16 opacity-80">
                    <div class="flex flex-col"><span class="text-2xl font-black text-white">47</span><span class="text-[9px] font-bold text-blue-400 uppercase tracking-widest">Matières</span></div>
                    <div class="w-px h-10 bg-white/10"></div>
                    <div class="flex flex-col"><span class="text-2xl font-black text-white">L1-L3</span><span class="text-[9px] font-bold text-blue-400 uppercase tracking-widest">Cursus complet</span></div>
                </div>
            </div>
        </div>

    </div>
</body>
@include('components.theme-toggle-script')
</html>
