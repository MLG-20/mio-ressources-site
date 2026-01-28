<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cours en ligne - {{ $enrollment->privateLesson->titre }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-white">

    <!-- NAVBAR -->
    <nav class="bg-slate-950 py-3 md:py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2 md:gap-3 min-w-0">
            <x-application-logo class="w-6 md:w-10 h-6 md:h-10 flex-shrink-0" />
            <div class="min-w-0">
                <span class="font-black uppercase tracking-tighter text-xs md:text-base block">Cours Particulier</span>
                <p class="text-[10px] md:text-xs text-slate-400 line-clamp-1">{{ $enrollment->privateLesson->titre }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">
            <div class="bg-white/10 px-2 md:px-4 py-2 rounded-lg md:rounded-2xl flex items-center gap-2 md:gap-3 hidden sm:flex">
                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover">
                <span class="text-[10px] md:text-sm font-bold hidden md:inline truncate">{{ Auth::user()->name }}</span>
            </div>
            <a href="{{ Auth::user()->role === 'professeur' ? route('teacher.private-lessons.index') : route('user.dashboard') }}"
                class="bg-red-500 text-white px-2 md:px-4 py-2 rounded-lg md:rounded-xl font-bold text-[10px] md:text-xs uppercase shadow-lg hover:bg-red-600 transition">
                Quitter
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-4 md:py-6 px-3 md:px-6">

        <!-- INFO CARD -->
        <div class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-xl md:rounded-[2rem] p-4 md:p-6 mb-4 md:mb-6 shadow-2xl">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                <div class="min-w-0">
                    <p class="text-[10px] md:text-xs text-white/70 font-bold uppercase mb-1">Cours</p>
                    <p class="text-sm md:text-lg font-black line-clamp-2">{{ $enrollment->privateLesson->titre }}</p>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs text-white/70 font-bold uppercase mb-1">Professeur</p>
                    <div class="flex items-center gap-2">
                        <img src="{{ $enrollment->privateLesson->teacher->avatar ? asset('storage/'.$enrollment->privateLesson->teacher->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($enrollment->privateLesson->teacher->name) }}"
                            class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover flex-shrink-0">
                        <p class="text-xs md:text-sm font-black line-clamp-1">{{ $enrollment->privateLesson->teacher->name }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-[10px] md:text-xs text-white/70 font-bold uppercase mb-1">Horaire prévu</p>
                    <p class="text-xs md:text-sm font-black line-clamp-1">
                        @if($enrollment->scheduled_at)
                            {{ $enrollment->scheduled_at->format('d/m/Y à H:i') }}
                        @else
                            ⏰ À définir
                        @endif
                    </p>
                </div>
            </div>

            @if($enrollment->session_status === 'scheduled')
                <div class="mt-3 md:mt-4 bg-white/10 rounded-lg md:rounded-xl p-3 md:p-4 text-center">
                    <p class="text-xs md:text-sm font-bold">⏳ En attente du démarrage par le professeur...</p>
                </div>
            @elseif($enrollment->session_status === 'active')
                <div class="mt-3 md:mt-4 bg-green-500/20 rounded-lg md:rounded-xl p-3 md:p-4 text-center border-2 border-green-400">
                    <p class="text-xs md:text-sm font-bold">✓ Session en cours depuis {{ $enrollment->started_at->diffForHumans() }}</p>
                </div>
            @endif
        </div>

        <!-- JITSI CONTAINER -->
        <div id="jitsi-container" class="bg-slate-950 rounded-lg md:rounded-[2rem] shadow-2xl overflow-hidden" style="height: calc(100vh - 250px); min-height: 300px; max-height: 80vh;">
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 md:h-16 w-12 md:w-16 border-b-4 border-white mx-auto mb-4"></div>
                    <p class="text-base md:text-lg font-bold">Chargement de la salle...</p>
                </div>
            </div>
        </div>

    </main>

    <!-- JITSI MEET API -->
    <script src="https://8x8.vc/vpaas-magic-cookie-91fa1fc0a0a3479c8d8e825bf68c4be7/external_api.js"></script>
    <script>
        const domain = '8x8.vc';
        const options = {
            roomName: 'vpaas-magic-cookie-91fa1fc0a0a3479c8d8e825bf68c4be7/{{ $enrollment->jitsi_room_name }}',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-container'),
            configOverwrite: {
                startWithAudioMuted: false,
                startWithVideoMuted: false,
                prejoinPageEnabled: false,
                disableDeepLinking: true
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'download', 'help', 'mute-everyone'
                ]
            },
            userInfo: {
                displayName: '{{ Auth::user()->name }}'
            }
        };

        const api = new JitsiMeetExternalAPI(domain, options);

        // Événements
        api.addEventListener('videoConferenceJoined', () => {
            console.log('✅ Joined conference');
        });

        api.addEventListener('videoConferenceLeft', () => {
            console.log('👋 Left conference');
            window.location.href = '{{ Auth::user()->role === "professeur" ? route("teacher.private-lessons.index") : route("user.dashboard") }}';
        });

        api.addEventListener('readyToClose', () => {
            window.location.href = '{{ Auth::user()->role === "professeur" ? route("teacher.private-lessons.index") : route("user.dashboard") }}';
        });
    </script>

</body>
</html>
