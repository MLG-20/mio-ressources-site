<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Mes Cours Vidéo - MIO</title>
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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f1f5f9] dark:bg-slate-950 text-slate-900 dark:text-slate-100 font-sans transition-colors duration-300">

    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl dark:shadow-none flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <style>
        /* Fallback dark mode pour améliorer le contraste global */
        html.dark body { background: #020617 !important; color: #e2e8f0 !important; }
        html.dark .bg-white, html.dark .bg-\[\#f1f5f9\], html.dark .bg-slate-50 { background-color: #0f172a !important; }
        html.dark .text-slate-900, html.dark .text-slate-800 { color: #f8fafc !important; }
        html.dark .text-slate-700, html.dark .text-slate-600, html.dark .text-slate-500, html.dark .text-slate-400, html.dark .text-gray-500 { color: #cbd5e1 !important; }
        html.dark .border-slate-50, html.dark .border-slate-100, html.dark .border-slate-200, html.dark .border-gray-200 { border-color: #334155 !important; }
    </style>

    <!-- NAVBAR -->
    <nav class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 shadow-sm dark:shadow-none transition-colors duration-300">
        <a href="/" class="flex items-center gap-2 group">
            <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            <span class="hidden sm:inline font-black text-slate-900 dark:text-slate-100 tracking-tight text-xs md:text-lg">MES COURS VIDÉO</span>
        </a>
        <div class="flex items-center gap-2 md:gap-6">
            <a href="{{ route('user.dashboard') }}" class="text-slate-700 dark:text-slate-300 hover:text-blue-700 font-bold transition flex items-center gap-1 md:gap-2 text-xs md:text-sm">
                <i class="fas fa-home"></i> <span class="hidden md:inline">Mon Espace</span>
            </a>
            <a href="{{ route('forum.index') }}" class="text-slate-700 dark:text-slate-300 hover:text-blue-700 font-bold transition flex items-center gap-1 md:gap-2 text-xs md:text-sm">
                <i class="fas fa-comments"></i> <span class="hidden md:inline">Forum</span>
            </a>
            <form method="POST" action="{{ route('logout') }}"> @csrf
                <button type="submit" class="bg-red-50 text-red-600 px-3 md:px-4 py-2 rounded-full font-bold text-xs md:text-sm hover:bg-red-600 hover:text-white transition-all flex items-center gap-1 md:gap-2">
                    <i class="fas fa-power-off"></i> <span class="hidden md:inline">Déconnexion</span>
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-bold rounded-r shadow-sm dark:shadow-none flex items-start md:items-center gap-2 text-sm md:text-base">
                <i class="fas fa-check-circle flex-shrink-0 mt-0.5 md:mt-0"></i> <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-bold rounded-r shadow-sm dark:shadow-none flex items-start md:items-center gap-2 text-sm md:text-base">
                <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5 md:mt-0"></i> <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 md:mb-8 p-3 md:p-4 bg-blue-100 border-l-4 border-blue-500 text-blue-700 font-bold rounded-r shadow-sm dark:shadow-none flex items-start md:items-center gap-2 text-sm md:text-base">
                <i class="fas fa-info-circle flex-shrink-0 mt-0.5 md:mt-0"></i> <span>{{ session('info') }}</span>
            </div>
        @endif

        <!-- Header Section -->
        <div class="mb-8 md:mb-10">
            <h1 class="text-2xl md:text-4xl font-black text-slate-900 mb-2 flex items-center gap-2 md:gap-3">
                <div class="w-10 md:w-12 h-10 md:h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg dark:shadow-none flex-shrink-0">
                    <i class="fas fa-graduation-cap text-lg md:text-2xl"></i>
                </div>
                <span>Mes Cours Vidéo</span>
            </h1>
            <p class="text-sm md:text-base text-slate-600 font-medium px-0 md:pl-0">Inscrivez-vous aux cours de vos professeurs et rejoignez-les en direct !</p>
        </div>

        <!-- Section: Mes cours inscrits -->
        <div class="mb-8 md:mb-10">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-400 mb-4 md:mb-6 flex items-center gap-2">
                <i class="fas fa-book-reader text-blue-500"></i>
                <span class="text-slate-600">Mes Cours Inscrits <span class="text-blue-600">({{ $myCourses->count() }})</span></span>
            </h2>

            @if($myCourses->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($myCourses as $course)
                        <div class="bg-white rounded-xl md:rounded-[2rem] p-4 md:p-6 shadow-sm dark:shadow-none border border-slate-100 hover:shadow-xl dark:hover:shadow-none transition-all group">
                            <div class="flex items-start gap-3 md:gap-4 mb-4">
                                <div class="w-12 md:w-14 h-12 md:h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center text-white shadow-lg dark:shadow-none group-hover:scale-110 transition-transform flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher text-lg md:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 text-base md:text-lg leading-tight mb-2 truncate">{{ $course->title }}</h3>
                                    <p class="text-xs text-slate-500 flex items-center gap-2 truncate">
                                        <i class="fas fa-user flex-shrink-0"></i>
                                        <span class="truncate">{{ $course->user->name }}</span>
                                    </p>
                                    <p class="text-xs text-slate-400 flex items-center gap-2 mt-1">
                                        <i class="fas fa-calendar-alt flex-shrink-0"></i>
                                        <span class="truncate">{{ $course->scheduled_at->format('d/m/Y H:i') }}</span>
                                    </p>
                                </div>
                            </div>

                            @if($course->status === 'active')
                                <div class="mb-4 inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black uppercase">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                                    EN DIRECT
                                </div>
                            @else
                                <div class="mb-4 inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-black uppercase">
                                    <i class="fas fa-clock"></i>
                                    Prog.
                                </div>
                            @endif

                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('scheduled-meetings.show', $course) }}" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold py-2 md:py-3 rounded-lg md:rounded-xl hover:shadow-lg dark:hover:shadow-none transition text-center text-xs md:text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-video"></i>
                                    <span>Rejoindre</span>
                                </a>
                                <form action="{{ route('student.courses.unenroll', $course) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Vous désinscrire de ce cours ?');" class="w-full bg-red-50 text-red-600 font-bold py-2 md:py-3 rounded-lg md:rounded-xl hover:bg-red-600 hover:text-white transition text-xs md:text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span class="hidden md:inline">Quitter</span><span class="md:hidden">X</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl md:rounded-[2rem] p-8 md:p-12 shadow-sm dark:shadow-none border border-slate-100 text-center">
                    <div class="w-16 md:w-20 h-16 md:h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-2xl md:text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-black text-slate-900 mb-2">Aucun cours inscrit</h3>
                    <p class="text-sm md:text-base text-slate-600">Inscrivez-vous aux cours disponibles ci-dessous !</p>
                </div>
            @endif
        </div>

        <!-- Section: Tous les cours disponibles -->
        <div class="mb-8 md:mb-10">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-slate-400 mb-4 md:mb-6 flex items-center gap-2">
                <i class="fas fa-list text-blue-500"></i>
                <span class="text-slate-600">Tous les Cours <span class="text-blue-600">({{ $availableCourses->count() }})</span></span>
            </h2>

            @if($availableCourses->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    @foreach($availableCourses as $course)
                        @php
                            $isEnrolled = $myCourses->contains('id', $course->id);
                            $isOwner = $course->user_id === auth()->id();
                        @endphp

                        <div class="bg-white rounded-xl md:rounded-[2rem] p-4 md:p-6 shadow-sm dark:shadow-none border border-slate-100 hover:shadow-xl dark:hover:shadow-none transition-all group">
                            <div class="flex items-start gap-3 md:gap-4 mb-4">
                                <div class="w-12 md:w-14 h-12 md:h-14 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg dark:shadow-none group-hover:scale-110 transition-transform flex-shrink-0">
                                    <i class="fas fa-video text-lg md:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 text-base md:text-lg leading-tight mb-2 truncate">{{ $course->title }}</h3>
                                    <p class="text-xs text-slate-500 flex items-center gap-2 truncate">
                                        <i class="fas fa-chalkboard-user flex-shrink-0"></i>
                                        <span class="truncate">{{ $course->user->name }}</span>
                                        @if($isOwner)
                                            <span class="text-blue-600 font-black flex-shrink-0">(V.)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-400 flex items-center gap-2 mt-1">
                                        <i class="fas fa-calendar-alt flex-shrink-0"></i>
                                        <span class="truncate">{{ $course->scheduled_at->format('d/m/Y H:i') }}</span>
                                    </p>
                                </div>
                            </div>

                            @if($course->status === 'active')
                                <div class="mb-4 inline-flex items-center gap-2 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-black uppercase">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                                    EN DIRECT
                                </div>
                            @else
                                <div class="mb-4 inline-flex items-center gap-2 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-black uppercase">
                                    <i class="fas fa-clock"></i>
                                    Prog.
                                </div>
                            @endif

                            <div class="flex flex-col gap-2">
                                @if($isEnrolled)
                                    <a href="{{ route('scheduled-meetings.show', $course) }}" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold py-2 md:py-3 rounded-lg md:rounded-xl hover:shadow-lg dark:hover:shadow-none transition text-center text-xs md:text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-video"></i>
                                        <span>Rejoindre</span>
                                    </a>
                                @elseif($isOwner)
                                    <a href="{{ route('teacher.dashboard') }}" class="w-full bg-slate-100 text-slate-700 font-bold py-2 md:py-3 rounded-lg md:rounded-xl hover:bg-slate-200 transition text-center text-xs md:text-sm flex items-center justify-center gap-2">
                                        <i class="fas fa-user-tie"></i>
                                        <span>Gérer</span>
                                    </a>
                                @else
                                    <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-2 md:py-3 rounded-lg md:rounded-xl hover:shadow-lg dark:hover:shadow-none transition text-xs md:text-sm flex items-center justify-center gap-2">
                                            <i class="fas fa-user-plus"></i>
                                            <span>S'inscrire</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl md:rounded-[2rem] p-8 md:p-12 shadow-sm dark:shadow-none border border-slate-100 text-center">
                    <div class="w-16 md:w-20 h-16 md:h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-inbox text-2xl md:text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg md:text-xl font-black text-slate-900 mb-2">Aucun cours disponible</h3>
                    <p class="text-sm md:text-base text-slate-600">Aucun professeur n'a encore programmé de cours.</p>
                </div>
            @endif
        </div>

    </main>

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
</body>
</html>
