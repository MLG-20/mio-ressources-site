<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum MIO - Communauté</title>
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
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden transition-colors duration-300">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 transition-colors duration-300" style="padding-top: env(safe-area-inset-top);">
        <a href="/" class="flex items-center gap-2">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="hidden sm:inline font-black text-slate-800 dark:text-slate-100 text-xs md:text-base">FORUM</span>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            <span class="hidden md:inline text-sm font-medium text-slate-500 dark:text-slate-300">Bonjour, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf
                <button type="submit" class="text-red-500 text-xs md:text-sm font-bold hover:underline">Déco.</button>
            </form>
        </div>
    </nav>

    <header class="bg-slate-900 py-8 md:py-12 px-4 md:px-6 text-center">
        <h1 class="text-2xl md:text-5xl font-black text-white tracking-tighter uppercase">Espace Communautaire</h1>
        <p class="text-slate-400 mt-2 text-xs md:text-base">Échangez avec les autres étudiants de l'université</p>
    </header>

    <main class="max-w-7xl mx-auto py-8 md:py-12 px-4 md:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            @foreach($categories as $cat)
                <a href="{{ route('forum.category', $cat->id) }}" class="group bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-500 transition-all flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 md:gap-6 min-w-0">
                        <div class="w-12 md:w-14 h-12 md:h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all flex-shrink-0">
                            <i class="fas fa-comments text-lg md:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base md:text-xl font-black text-slate-800 truncate">{{ $cat->nom }}</h3>
                            <p class="text-slate-500 text-xs md:text-sm line-clamp-1">{{ $cat->description }}</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-3 md:px-4 py-2 rounded-2xl text-center min-w-[70px] md:min-w-[80px] flex-shrink-0">
                        <span class="block font-black text-slate-800 text-base md:text-lg">{{ $cat->sujets_count }}</span>
                        <span class="text-[9px] md:text-[10px] uppercase font-bold text-slate-400">Sujets</span>
                    </div>
                </a>
            @endforeach
        </div>
    </main>
</body>
<script>
    (function () {
        const btn = document.getElementById('theme-toggle');
        const icon = document.getElementById('theme-toggle-icon');
        if (!btn || !icon) return;
        const syncIcon = () => {
            const isDark = document.documentElement.classList.contains('dark');
            icon.className = 'fas ' + (isDark ? 'fa-sun' : 'fa-moon');
        };
        syncIcon();
        btn.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            syncIcon();
        });
    })();
</script>
</html>
