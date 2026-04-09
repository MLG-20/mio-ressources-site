<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $group->name }} - Salle de Groupe</title>
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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://meet.jit.si/external_api.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        /* Scrollbar Personnalisée */
        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #2563eb, #1d4ed8); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(to bottom, #1d4ed8, #1e40af); }
        * { scrollbar-color: #2563eb transparent; scrollbar-width: thin; }
    </style>
</head>
<body class="bg-[#f8fafc] dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen transition-colors duration-300">

    <!-- NAVBAR -->
    <button id="theme-toggle" type="button"
            class="fixed bottom-6 left-4 md:left-8 z-[95] bg-white/90 text-slate-700 dark:bg-slate-800 dark:text-yellow-300 border border-slate-200 dark:border-slate-700 w-12 h-12 rounded-2xl shadow-2xl flex items-center justify-center hover:scale-105 transition-all"
            aria-label="Changer le theme">
        <i id="theme-toggle-icon" class="fas fa-moon"></i>
    </button>

    <nav class="bg-white dark:bg-slate-900 text-slate-800 dark:text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 shadow-xl border-b border-slate-200 dark:border-slate-700 transition-colors duration-300">
        <div class="flex items-center gap-3 md:gap-4">
            <a href="/">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
            </a>
            <div class="hidden md:block">
                <p class="text-xs font-black uppercase tracking-[0.2em] opacity-70">Groupe de Travail</p>
                <p class="font-black text-lg">{{ $group->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 md:gap-4">
            <div class="bg-slate-100 dark:bg-white/10 px-3 md:px-4 py-2 rounded-2xl flex items-center gap-2 md:gap-3">
                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}"
                     class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover">
                <span class="text-xs md:text-sm font-bold hidden md:inline truncate">{{ Auth::user()->name }}</span>
            </div>
            <a href="{{ route('user.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 px-3 md:px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg transition">
                <i class="fas fa-arrow-left md:mr-2"></i><span class="hidden md:inline">Retour</span>
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- Informations du groupe -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl md:rounded-3xl p-4 md:p-6 text-white shadow-xl mb-6 md:mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="w-12 md:w-14 h-12 md:h-14 rounded-2xl bg-white/15 flex items-center justify-center shadow-lg flex-shrink-0">
                        <i class="fas fa-users text-xl md:text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-[0.3em] opacity-80 mb-1">Salle Permanente</p>
                        <h1 class="text-xl md:text-2xl font-black truncate">{{ $group->name }}</h1>
                        @if($group->description)
                        <p class="text-sm opacity-90 mt-2 line-clamp-2">{{ $group->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl flex items-center gap-2 text-sm">
                        <i class="fas fa-user-tie"></i>
                        <span class="font-bold">{{ $group->creator->name }}</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl flex items-center gap-2 text-sm">
                        <i class="fas fa-users"></i>
                        <span class="font-bold">{{ $group->members->count() }} membre(s)</span>
                    </div>
                </div>
            </div>

            <!-- Liste des membres -->
            <div class="mt-6 pt-4 border-t border-white/20">
                <p class="text-xs font-black uppercase tracking-[0.2em] opacity-70 mb-3">Membres du groupe</p>
                <div class="space-y-2">
                    @foreach($group->members as $member)
                    <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-lg flex items-center justify-between text-sm hover:bg-white/15 transition">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <img src="{{ $member->avatar ? asset('storage/'.$member->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($member->name).'&background=fff&color=6366f1&size=32' }}"
                                 class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                            <span class="font-bold truncate">{{ $member->name }}</span>
                            @if($member->id === $group->creator_id)
                            <span class="bg-amber-400 text-amber-900 px-2 py-0.5 rounded-full text-xs font-black flex-shrink-0">Créateur</span>
                            @endif
                        </div>

                        <!-- Bouton supprimer (visible seulement pour le créateur) -->
                        @if(Auth::id() === $group->creator_id && $member->id !== $group->creator_id)
                        <form action="{{ route('groups.remove-member', [$group->id, $member->id]) }}" method="POST" onsubmit="return confirm('Retirer {{ $member->name }} du groupe ?')" class="flex-shrink-0 ml-2">
                            @csrf @method('DELETE')
                            <button type="submit" class="bg-red-500/20 hover:bg-red-500/40 text-red-300 px-2 py-1 rounded text-xs font-bold transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Conteneur Jitsi -->
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <div id="jitsi-container" class="w-full" style="height: 70vh; min-height: 500px;"></div>
        </div>

    </main>

    <script>
        // Configuration Jitsi Meet
        const domain = 'meet.jit.si';
        const options = {
            roomName: '{{ $group->room_name }}',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                prejoinPageEnabled: false,
                disableDeepLinking: true,
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone'
                ],
            },
            userInfo: {
                displayName: '{{ Auth::user()->name }}'
            }
        };

        const api = new JitsiMeetExternalAPI(domain, options);

        // Log de connexion
        api.addEventListener('videoConferenceJoined', () => {
            console.log('Connecté au groupe de travail: {{ $group->name }}');
        });
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
