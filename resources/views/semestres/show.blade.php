<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $semestre->nom }} - MIO</title>
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
        /* À ajouter dans ton bloc <style> existant */
    nav {
    padding-top: env(safe-area-inset-top, 0px) !important;
    }

    /* Optionnel : Ajuster aussi la hauteur de la navbar pour qu'elle ne paraisse pas trop fine */
    @media (display-mode: standalone) {
    nav {
        min-height: calc(64px + env(safe-area-inset-top));
    }
    }
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white, html.dark .bg-gray-50 { background-color: #0f172a !important; }
        html.dark .text-gray-900, html.dark .text-gray-800 { color: #f8fafc !important; }
        html.dark .text-gray-500, html.dark .text-slate-500, html.dark .text-slate-400 { color: #cbd5e1 !important; }
        html.dark .border-slate-50, html.dark .border-slate-100, html.dark .border-slate-200 { border-color: #334155 !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 overflow-x-hidden transition-colors duration-300">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <nav class="bg-white dark:bg-slate-900 shadow-sm py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <a href="/" class="flex items-center gap-2 group">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="font-black text-gray-800 dark:text-slate-100">RESSOURCES</span>
        </a>
        <a href="/" class="text-gray-500 dark:text-slate-300 hover:text-blue-600 font-bold transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </nav>

    <header class="relative py-12 md:py-20 bg-blue-900 text-white text-center overflow-hidden">
        @if($semestre->image_path)
            <img src="{{ asset('storage/' . $semestre->image_path) }}" class="absolute inset-0 w-full h-full object-cover opacity-20">
        @endif
        <div class="relative z-10">
            <span class="bg-blue-500/30 backdrop-blur-md border border-blue-400 px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest">{{ $semestre->niveau }}</span>
            <h1 class="text-4xl md:text-6xl font-black mt-4 uppercase tracking-tighter">{{ $semestre->nom }}</h1>
            <p class="text-blue-200 mt-2 italic font-medium">Retrouvez tous vos supports de cours et travaux dirigés</p>
        </div>
    </header>

    <main class="py-10 md:py-16 px-6 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($semestre->matieres as $matiere)
                <!-- LA CORRECTION EST ICI : href="{{ route('matiere.show', $matiere->id) }}" -->
                <a href="{{ route('matiere.show', $matiere->id) }}"
                   class="group bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-blue-200/50 hover:border-blue-400 transition-all duration-300 flex flex-col justify-between h-full">

                    <div>
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                            <i class="fas fa-book-open text-xl"></i>
                        </div>
                        <span class="text-[10px] font-black font-mono text-slate-400 uppercase tracking-widest">{{ $matiere->code }}</span>
                        <h3 class="text-xl font-black text-slate-800 mt-2 leading-tight group-hover:text-blue-600 transition-colors">{{ $matiere->nom }}</h3>
                    </div>

                    <div class="mt-10 flex justify-between items-center border-t border-slate-50 pt-6">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Supports disponibles</span>
                        <div class="bg-slate-50 text-slate-800 h-10 w-10 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </div>
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
