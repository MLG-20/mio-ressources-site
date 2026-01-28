<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Révision instantanée - MIO</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        /* Scrollbar Personnalisée - BLEU MAGNIFIQUE */
        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, #2563eb, #1d4ed8); border-radius: 10px; box-shadow: 0 0 6px rgba(37, 99, 235, 0.5); }
        ::-webkit-scrollbar-thumb:hover { background: linear-gradient(to bottom, #1d4ed8, #1e40af); box-shadow: 0 0 12px rgba(37, 99, 235, 0.8); }
        * { scrollbar-color: #2563eb transparent; scrollbar-width: thin; }
        #jitsi-container { width: 100%; height: 400px; border-radius: 1rem; overflow: hidden; background: #0f172a; }
        @media (min-width: 768px) { #jitsi-container { height: 640px; } }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-900">

    <!-- NAVBAR -->
    <nav class="bg-slate-900 text-white py-4 px-4 md:px-8 flex justify-between items-center sticky top-0 z-50 shadow-xl">
        <a href="{{ route('user.dashboard') }}">
           <div class="flex items-center gap-2 md:gap-3">
                <x-application-logo class="w-8 md:w-10 h-8 md:h-10" />
                <span class="hidden sm:inline font-black uppercase tracking-tighter text-xs md:text-base">Révision Instantanée</span>
            </div>
        </a>
        <div class="flex items-center gap-2 md:gap-4">
            @auth
            <div class="bg-white/10 px-3 md:px-4 py-2 rounded-2xl flex items-center gap-2 md:gap-3">
                <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=0D8ABC&color=fff' }}" class="w-6 md:w-8 h-6 md:h-8 rounded-lg object-cover">
                <span class="text-xs md:text-sm font-bold hidden md:inline truncate">{{ auth()->user()->name }}</span>
            </div>
            @endauth
            <a href="{{ route('user.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-xl font-bold text-xs uppercase shadow-lg transition">
                <i class="fas fa-arrow-left md:hidden"></i>
                <span class="hidden md:inline">Retour</span>
            </a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 md:py-10 px-4 md:px-6">

        <!-- CARTE PRINCIPALE -->
        <div class="bg-gradient-to-br from-sky-600 to-indigo-700 rounded-2xl md:rounded-3xl p-6 md:p-8 text-white shadow-2xl mb-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-video text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] opacity-80">Révision instantanée</p>
                    <h1 class="text-xl md:text-3xl font-black">Salle rapide · {{ $room }}</h1>
                </div>
            </div>
            <p class="text-sm md:text-base opacity-90 mb-6">Partage le lien ci-dessous avec tes camarades. Pas d'enregistrement, la salle vit seulement pendant votre session.</p>

            <!-- ACTIONS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest mb-2 opacity-80">Lien de partage</label>
                    <input id="share-link" type="text" readonly value="{{ $shareUrl }}" class="w-full bg-white/20 border-2 border-white/30 text-white placeholder-white/60 rounded-xl px-4 py-3 font-bold text-sm focus:bg-white/30 focus:outline-none transition">
                </div>
                <div class="flex gap-2">
                    <button id="copy-btn" class="flex-1 bg-white text-blue-600 font-black py-3 px-4 rounded-xl shadow-lg hover:shadow-2xl hover:scale-105 transition text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-copy"></i> Copier
                    </button>
                    <button id="reload-btn" class="bg-white/20 hover:bg-white/30 text-white font-black p-3 rounded-xl border-2 border-white/30 transition" title="Nouvelle salle">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- SALLE D'ATTENTE -->
            <div class="mt-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 flex items-center gap-3">
                <input type="checkbox" id="lobby-toggle" {{ $lobbyEnabled ? 'checked' : '' }} class="w-5 h-5 rounded cursor-pointer accent-white">
                <div class="flex-1">
                    <label for="lobby-toggle" class="cursor-pointer font-bold text-sm block">Salle d'attente activée</label>
                    <p class="text-[11px] opacity-70">Les nouveaux participants doivent être acceptés par un modérateur</p>
                </div>
            </div>
        </div>

        <!-- CONTENEUR JITSI -->
        <div class="bg-white rounded-2xl md:rounded-3xl p-4 md:p-6 shadow-xl">
            <div id="jitsi-container"></div>
        </div>

        <!-- INFO DOMAINE -->
        <div class="mt-4 text-center">
            <p class="text-xs text-slate-400 font-medium">
                <i class="fas fa-server text-blue-500"></i> Domaine Jitsi : <span class="font-bold text-slate-600">{{ $domain }}</span>
            </p>
        </div>

    </main>

    <script src="https://meet.jit.si/external_api.js"></script>
    <script>
        const roomName = @json($room);
        const domain = @json($domain);
        const displayName = @json($user?->name ?? 'Utilisateur');
        const email = @json($user?->email ?? '');
        const parentNode = document.getElementById('jitsi-container');
        const lobbyToggle = document.getElementById('lobby-toggle');

        const api = new JitsiMeetExternalAPI(domain, {
            roomName,
            parentNode,
            width: '100%',
            height: 640,
            userInfo: { displayName, email },
            configOverwrite: {
                prejoinPageEnabled: true,
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                toolbarButtons: [
                    'microphone', 'camera', 'desktop', 'hangup', 'chat', 'participants-pane',
                    'tileview', 'fullscreen', 'raisehand', 'toggle-camera', 'select-background'
                ],
                LOBBY_MODE_ENABLED: @json($lobbyEnabled),
            },
        });

        // Toggle lobby dynamiquement
        lobbyToggle.addEventListener('change', async () => {
            try {
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({ lobby: lobbyToggle.checked }),
                });
                if (response.ok) {
                    console.log('Lobby ' + (lobbyToggle.checked ? 'activée' : 'désactivée'));
                }
            } catch (e) {
                console.error('Erreur lors du changement de lobby:', e);
                lobbyToggle.checked = !lobbyToggle.checked;
            }
        });

        document.getElementById('copy-btn').addEventListener('click', async () => {
            const link = document.getElementById('share-link').value;
            try {
                await navigator.clipboard.writeText(link);
                alert('Lien copié dans le presse-papier.');
            } catch (e) {
                alert('Impossible de copier automatiquement. Copiez le lien manuellement.');
            }
        });

        document.getElementById('reload-btn').addEventListener('click', () => {
            const url = new URL(window.location.href);
            url.searchParams.delete('room');
            window.location.href = url.toString();
        });
    </script>
</body>
</html>
