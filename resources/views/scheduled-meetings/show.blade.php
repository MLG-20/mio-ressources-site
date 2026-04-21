<x-app-layout>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $meeting->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 12px;
        }
        @media (min-width: 768px) {
            .container { padding: 20px; }
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            margin-bottom: 16px;
        }
        @media (min-width: 768px) {
            .card { padding: 30px; margin-bottom: 20px; }
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            padding: 16px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
            border-radius: 12px;
            border-bottom: none;
        }
        @media (min-width: 768px) {
            .card-header { gap: 20px; margin-bottom: 25px; padding: 20px; }
        }
        .header-left h1 {
            font-size: 20px;
            color: white;
            margin-bottom: 8px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        @media (min-width: 768px) {
            .header-left h1 { font-size: 28px; margin-bottom: 10px; }
        }
        .header-left p {
            font-size: 13px;
            color: rgba(255,255,255,0.95);
            margin-bottom: 6px;
            font-weight: 500;
        }
        @media (min-width: 768px) {
            .header-left p { font-size: 15px; margin-bottom: 8px; }
        }
        .header-left .prof-name {
            font-size: 14px;
            color: #fbbf24;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        @media (min-width: 768px) {
            .header-left .prof-name { font-size: 16px; }
        }
        .badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            backdrop-filter: blur(10px);
        }
        .badge.moderator {
            background: rgba(255,193,7,0.2);
            color: #fbbf24;
            border: 1px solid rgba(255,193,7,0.4);
        }
        .header-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            background: #e2e8f0;
            color: #0f172a;
        }
        .btn:hover {
            background: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn.danger {
            background: #ef4444;
            color: white;
        }
        .btn.danger:hover {
            background: #dc2626;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #4f46e5;
        }
        .info-item i {
            font-size: 18px;
            color: #4f46e5;
            width: 24px;
            text-align: center;
        }
        .info-content {
            flex: 1;
        }
        .info-content label {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-content strong {
            display: block;
            font-size: 14px;
            color: #0f172a;
            font-weight: 600;
        }
        #jitsi-container {
            width: 100%;
            height: 700px;
            border-radius: 12px;
            overflow: hidden;
            background: #0f172a;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 15px;
            background: #f1f5f9;
            border-radius: 8px;
            border-left: 4px solid #10b981;
            flex-wrap: wrap;
        }
        .footer-info i {
            color: #10b981;
            font-size: 16px;
        }
        .footer-info span {
            font-size: 13px;
            color: #475569;
        }
        .status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: #ecfdf5;
            border-radius: 12px;
            font-size: 12px;
            color: #047857;
            font-weight: 600;
        }
        .pulse {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-animation 2s infinite;
        }
        @keyframes pulse-animation {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        @media (max-width: 768px) {
            .container { padding: 12px; }
            .card { padding: 20px; }
            .card-header { flex-direction: column; }
            .header-left h1 { font-size: 22px; }
            .header-right { width: 100%; }
            .btn { flex: 1; justify-content: center; }
            #jitsi-container { height: 500px; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="card-header">
                <div class="header-left">
                    <h1>{{ $meeting->title }}</h1>
                    <p><i class="fas fa-chalkboard-user"></i> Professeur: <span class="prof-name">{{ $meeting->user->name }}</span></p>
                    <div class="badges">
                        <span class="badge">
                            <i class="fas fa-video"></i> Cours en ligne
                        </span>
                        @if ($isModerator)
                            <span class="badge moderator">
                                <i class="fas fa-crown"></i> Modérateur
                            </span>
                        @else
                            <span class="badge">
                                <i class="fas fa-user-circle"></i> Participant
                            </span>
                        @endif
                    </div>
                </div>
                <div class="header-right">
                    <a href="{{ route('teacher.dashboard') }}" class="btn">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    @if ($isModerator)
                        <form action="{{ route('scheduled-meetings.close', $meeting) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn danger" onclick="return confirm('Êtes-vous sûr de fermer ce cours ?');">
                                <i class="fas fa-stop-circle"></i> Fermer
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Info Grid -->
            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-door-open"></i>
                    <div class="info-content">
                        <label>Salle</label>
                        <strong>{{ $room }}</strong>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar"></i>
                    <div class="info-content">
                        <label>Programmé</label>
                        <strong>{{ $meeting->scheduled_at->format('d/m/Y à H:i') }}</strong>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-server"></i>
                    <div class="info-content">
                        <label>Domaine</label>
                        <strong>{{ $domain }}</strong>
                    </div>
                </div>
            </div>

            <!-- Jitsi Container -->
            <div id="jitsi-container"></div>

            <!-- Footer Info -->
            <div class="footer-info">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="pulse"></div>
                    <i class="fas fa-info-circle"></i>
                    @if ($isModerator)
                        <span>Le cours se fermera automatiquement 30 minutes après votre départ.</span>
                    @else
                        <span>Connecté en tant que <strong>{{ $user->name }}</strong></span>
                    @endif
                </div>
                <div class="status-indicator">
                    <i class="fas fa-wifi"></i>
                    Session active
                </div>
            </div>
        </div>
    </div>

    <script src="https://{{ $domain }}/external_api.js"></script>
    <script>
        const domain = '{{ $domain }}';
        const roomName = '{{ $room }}';
        const displayName = '{{ $user->name }}';
        const email = '{{ $user->email }}';
        const isModerator = {{ $isModerator ? 'true' : 'false' }};

        const options = {
            roomName: roomName,
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#jitsi-container'),
            userInfo: {
                displayName: displayName,
                email: email,
            },
            configOverwrite: {
                prejoinPageEnabled: false,
            },
            interfaceConfigOverwrite: {
                SHOW_JITSI_WATERMARK: false,
                SHOW_WATERMARK_FOR_GUESTS: false,
                DEFAULT_BACKGROUND: '#0f172a',
                toolbarButtons: isModerator
                    ? ['microphone', 'camera', 'desktop', 'hangup', 'chat', 'participants-pane', 'tileview', 'fullscreen', 'raisehand', 'mute-everyone']
                    : ['microphone', 'camera', 'desktop', 'hangup', 'chat', 'participants-pane', 'tileview', 'fullscreen', 'raisehand'],
            },
        };

        const api = new JitsiMeetExternalAPI(domain, options);

        api.addEventListener('videoConferenceLeft', () => {
            window.location.href = '{{ route("teacher.dashboard") }}';
        });

        @if ($isModerator)
            let inactivityTimeout;
            const checkInactivity = () => {
                inactivityTimeout = setTimeout(() => {
                    fetch('{{ route("scheduled-meetings.close", $meeting) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        window.location.href = '{{ route("teacher.dashboard") }}';
                    });
                }, 30 * 60 * 1000);
            };

            document.addEventListener('mousemove', () => {
                clearTimeout(inactivityTimeout);
                checkInactivity();
            });

            checkInactivity();
        @endif
    </script>
</body>
</x-app-layout>
