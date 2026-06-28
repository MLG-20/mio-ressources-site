{{--
    Composant : installation de la PWA "Installer l'application".

    - Bouton flottant (coin bas-droite) qui ouvre la fenêtre.
    - Fenêtre avec :
        * un bouton d'installation NATIF (Android / Chrome / Edge) via l'événement beforeinstallprompt ;
        * des instructions manuelles par plateforme (Android / iPhone / PC) pour les cas sans bouton natif (ex. iOS).
    - Tout est masqué automatiquement si l'app est déjà installée (mode standalone).

    Ouvrir la fenêtre depuis n'importe quel bouton ailleurs dans la page :
        <button @click="$dispatch('open-pwa-install')">Installer l'app</button>
--}}

{{-- Capture précoce de l'événement d'installation (avant l'init d'Alpine) --}}
<script>
    window.__deferredPwaPrompt = window.__deferredPwaPrompt || null;
    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        window.__deferredPwaPrompt = e;
        window.dispatchEvent(new CustomEvent('pwa-installable'));
    });
</script>

<div
    x-data="pwaInstall()"
    @open-pwa-install.window="open = true"
    @pwa-installable.window="canInstall = true; deferredPrompt = window.__deferredPwaPrompt"
>
    {{-- Bouton flottant icône seule, empilé AU-DESSUS du bouton "remonter en haut".
         Même comportement que lui : n'apparaît qu'après avoir scrollé (variable "scrolled" du <body>). --}}
    <button x-show="scrolled && !isStandalone" x-cloak
            x-transition:enter="transition scale-0 rotate-180"
            x-transition:enter-end="scale-100 rotate-0"
            @click="open = true"
            aria-label="Installer l'application"
            class="fixed bottom-24 right-6 md:bottom-28 md:right-10 z-[90] bg-blue-600 text-white w-12 h-12 md:w-14 md:h-14 rounded-2xl shadow-2xl flex items-center justify-center text-2xl hover:bg-blue-700 transition-all transform hover:-translate-y-2 border-4 border-white/20">
        📲
    </button>

    {{-- Fenêtre (téléportée dans <body> pour ne pas être rognée par un parent en overflow-hidden) --}}
    <template x-teleport="body">
        <div x-show="open" x-cloak
             x-transition.opacity
             @click="open = false"
             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-sm p-4">

            <div @click.stop
                 x-show="open"
                 x-transition
                 class="w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">

                {{-- En-tête --}}
                <div class="bg-blue-600 px-6 py-5 text-white text-center relative">
                    <button @click="open = false"
                            class="absolute top-3 right-4 text-white/80 hover:text-white text-3xl leading-none">&times;</button>
                    <div class="text-4xl mb-1">📲</div>
                    <h3 class="text-lg font-bold">Installer MIO Ressources</h3>
                    <p class="text-sm text-white/80 mt-1">Accède à tes ressources comme une vraie application, directement depuis ton écran d'accueil.</p>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Bouton natif (Android / Chrome / Edge) --}}
                    <button x-show="canInstall" @click="install()"
                            class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-full transition active:scale-95 shadow-lg shadow-blue-600/30">
                        ⬇️ Installer maintenant
                    </button>

                    <p x-show="canInstall" class="text-center text-xs text-slate-400">— ou installe manuellement selon ton appareil —</p>

                    {{-- Instructions par plateforme --}}
                    <div class="space-y-3 text-sm">
                        <div class="rounded-2xl border border-slate-100 dark:border-slate-700 p-4">
                            <p class="font-semibold text-slate-800 dark:text-slate-100 mb-1">🤖 Android (Chrome)</p>
                            <p class="text-slate-500 dark:text-slate-400">Menu <b>⋮</b> (3 points) en haut à droite → <b>« Installer l'application »</b></p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 dark:border-slate-700 p-4">
                            <p class="font-semibold text-slate-800 dark:text-slate-100 mb-1">🍏 iPhone / iPad (Safari)</p>
                            <p class="text-slate-500 dark:text-slate-400">Icône <b>Partager</b> ⬆️ → <b>« Sur l'écran d'accueil »</b> → Ajouter</p>
                        </div>

                        <div class="rounded-2xl border border-slate-100 dark:border-slate-700 p-4">
                            <p class="font-semibold text-slate-800 dark:text-slate-100 mb-1">💻 PC (Chrome / Edge)</p>
                            <p class="text-slate-500 dark:text-slate-400">Icône <b>⊕</b> (ou écran) dans la barre d'adresse → <b>« Installer »</b></p>
                        </div>
                    </div>

                    <button @click="open = false"
                            class="w-full text-slate-500 dark:text-slate-400 text-sm py-2 hover:text-slate-700 dark:hover:text-slate-200">
                        Plus tard
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function pwaInstall() {
        return {
            open: false,
            canInstall: false,
            deferredPrompt: null,
            isStandalone: false,

            init() {
                // L'app tourne-t-elle déjà en mode installé ?
                this.isStandalone = window.matchMedia('(display-mode: standalone)').matches
                    || window.navigator.standalone === true;

                // Récupère un éventuel prompt capturé avant l'init d'Alpine
                if (window.__deferredPwaPrompt) {
                    this.deferredPrompt = window.__deferredPwaPrompt;
                    this.canInstall = true;
                }

                // Quand l'installation est terminée, on masque tout
                window.addEventListener('appinstalled', () => {
                    this.canInstall = false;
                    this.open = false;
                    this.isStandalone = true;
                    window.__deferredPwaPrompt = null;
                });
            },

            async install() {
                if (!this.deferredPrompt) return;
                this.deferredPrompt.prompt();
                await this.deferredPrompt.userChoice;
                this.deferredPrompt = null;
                this.canInstall = false;
                window.__deferredPwaPrompt = null;
            },
        };
    }
</script>
