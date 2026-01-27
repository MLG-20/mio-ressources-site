<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Révision instantanée</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background: #f8fafc; }
        .container { max-width: 1080px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06); }
        .title { margin: 0 0 8px 0; font-size: 24px; color: #0f172a; }
        .muted { color: #475569; margin: 0 0 16px 0; }
        .row { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; margin-bottom: 12px; }
        .pill { background: #e0f2fe; color: #0ea5e9; padding: 6px 12px; border-radius: 999px; font-weight: 600; font-size: 14px; }
        .input { width: 100%; max-width: 640px; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; background: #f8fafc; }
        .btn { display: inline-flex; align-items: center; gap: 8px; border: none; cursor: pointer; padding: 10px 16px; border-radius: 10px; font-weight: 700; font-size: 14px; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #0f172a; color: #fff; }
        .btn:active { transform: translateY(1px); }
        #jitsi-container { width: 100%; height: 640px; margin-top: 18px; border-radius: 12px; overflow: hidden; background: #0f172a; }
        .small { font-size: 13px; color: #475569; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <p class="pill">Révision instantanée</p>
            <h1 class="title">Salle rapide</h1>
            <p class="muted">Partage le lien ci-dessous avec tes camarades. Pas d'enregistrement, pas de base de données : la salle vit seulement pendant votre session.</p>

            <div class="row">
                <input id="share-link" class="input" type="text" readonly value="{{ $shareUrl }}">
                <button id="copy-btn" class="btn btn-primary">Copier le lien</button>
                <button id="reload-btn" class="btn btn-secondary">Nouvelle salle</button>
            </div>
            <p class="small">Salle : <strong>{{ $room }}</strong> · Domaine Jitsi : {{ $domain }}</p>

            <!-- Toggle Lobby -->
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 16px; padding: 12px 14px; background: #f1f5f9; border-radius: 10px; border: 1px solid #cbd5e1;">
                <input type="checkbox" id="lobby-toggle" {{ $lobbyEnabled ? 'checked' : '' }} style="cursor: pointer; width: 18px; height: 18px;">
                <label for="lobby-toggle" style="cursor: pointer; margin: 0; font-weight: 600; color: #475569; font-size: 14px;">Activer la salle d'attente (lobby)</label>
                <span style="font-size: 12px; color: #64748b; margin-left: auto;">Les nouveaux entrants doivent être acceptés.</span>
            </div>

            <div id="jitsi-container"></div>
        </div>
    </div>

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
