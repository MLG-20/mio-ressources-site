// PWA Update Manager
// Détecte les mises à jour du Service Worker et notifie l'utilisateur

class PWAUpdateManager {
    constructor() {
        this.updateBanner = null;
        this.registration = null;
        this.updateAvailable = false;
    }

    init() {
        if (!('serviceWorker' in navigator)) return;

        // Écouter les mises à jour du Service Worker
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            if (this.updateAvailable) {
                console.log('✅ Mise à jour appliquée ! Rechargement...');
                window.location.reload();
            }
        });

        // Vérifier les mises à jour toutes les 30 secondes
        this.checkForUpdates();
        setInterval(() => this.checkForUpdates(), 30000);

        // Vérifier aussi au chargement de la page
        window.addEventListener('load', () => this.checkForUpdates());
    }

    async checkForUpdates() {
        try {
            if (!navigator.serviceWorker.controller) return;

            this.registration = await navigator.serviceWorker.getRegistrations();
            if (this.registration.length === 0) return;

            const reg = this.registration[0];
            await reg.update();

            // Vérifier s'il y a un waiting Service Worker (nouvelle version)
            if (reg.waiting) {
                console.log('🔄 Nouvelle version disponible !');
                this.showUpdateBanner();
                this.updateAvailable = true;
            }
        } catch (error) {
            console.error('Erreur vérification mise à jour:', error);
        }
    }

    showUpdateBanner() {
        if (this.updateBanner) return; // Banner déjà affichée

        const banner = document.createElement('div');
        banner.id = 'pwa-update-banner';
        banner.className = 'fixed top-0 left-0 right-0 z-[999] bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-3 md:py-4 shadow-lg';
        banner.innerHTML = `
            <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 flex-1">
                    <i class="fas fa-sync-alt animate-spin text-xl"></i>
                    <p class="text-sm md:text-base font-semibold">Une nouvelle version de MIO Ressources est disponible !</p>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <button id="pwa-update-later" class="px-3 md:px-4 py-2 bg-blue-700 hover:bg-blue-800 rounded-lg text-xs md:text-sm font-semibold transition-colors">
                        Plus tard
                    </button>
                    <button id="pwa-update-now" class="px-3 md:px-4 py-2 bg-white text-blue-600 hover:bg-slate-100 rounded-lg text-xs md:text-sm font-bold transition-colors">
                        Mettre à jour maintenant
                    </button>
                </div>
            </div>
        `;

        document.body.insertBefore(banner, document.body.firstChild);

        // Ajouter du padding au body pour la banner
        document.body.style.paddingTop = banner.offsetHeight + 'px';

        // Événements des boutons
        document.getElementById('pwa-update-now').addEventListener('click', () => this.applyUpdate());
        document.getElementById('pwa-update-later').addEventListener('click', () => this.dismissBanner());

        this.updateBanner = banner;
    }

    dismissBanner() {
        if (this.updateBanner) {
            this.updateBanner.remove();
            document.body.style.paddingTop = '0';
            this.updateBanner = null;
        }
    }

    applyUpdate() {
        // Dire au waiting Service Worker de prendre le contrôle
        if (this.registration && this.registration.length > 0) {
            const reg = this.registration[0];
            if (reg.waiting) {
                reg.waiting.postMessage({ type: 'SKIP_WAITING' });
            }
        }
    }
}

// Initialiser au chargement du document
document.addEventListener('DOMContentLoaded', () => {
    const updateManager = new PWAUpdateManager();
    updateManager.init();
});
