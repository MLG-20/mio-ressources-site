<div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 z-[999] bg-white dark:bg-gray-100 text-slate-900 dark:text-slate-900 p-4 md:p-6 shadow-2xl border-t-4 border-blue-600 transform transition-all duration-500" style="display: none;">
    <div class="max-w-7xl mx-auto">
        <!-- CONTENU PRINCIPAL -->
        <div class="flex flex-col md:flex-row gap-6 md:gap-8 items-start md:items-center">
            <div class="flex-1">
                <h3 class="text-lg md:text-xl font-bold mb-2 tracking-tight text-white dark:text-white">🍪 Préférences de Confidentialité</h3>
                <p class="text-white dark:text-white text-sm md:text-base leading-relaxed mb-4 md:mb-0">
                    Nous utilisons des cookies essentiels pour la sécurité, et des services analytiques pour améliorer votre expérience.
                    <a href="{{ route('page.show', 'politique-confidentialite') }}" target="_blank" class="text-blue-600 dark:text-blue-600 hover:text-blue-700 dark:hover:text-blue-700 font-semibold underline">En savoir plus</a>
                </p>
            </div>

            <!-- BOUTONS -->
            <div class="flex flex-col sm:flex-row gap-2 md:gap-3 w-full md:w-auto">
                <button id="cookie-refuse" class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-200 dark:bg-slate-200 hover:bg-slate-300 dark:hover:bg-slate-300 text-slate-900 dark:text-slate-900 font-semibold rounded-lg transition-all text-sm md:text-base whitespace-nowrap">
                    Refuser
                </button>
                <button id="cookie-customize" class="px-4 md:px-6 py-2.5 md:py-3 bg-slate-300 dark:bg-slate-300 hover:bg-slate-400 dark:hover:bg-slate-400 text-slate-900 dark:text-slate-900 font-semibold rounded-lg transition-all text-sm md:text-base whitespace-nowrap">
                    Personnaliser
                </button>
                <button id="cookie-accept" class="px-4 md:px-6 py-2.5 md:py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-lg text-sm md:text-base whitespace-nowrap">
                    Accepter tout
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL PERSONNALISATION -->
<div id="cookie-modal" class="fixed inset-0 z-[1000] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden transition-all duration-300">
    <div class="bg-white dark:bg-gray-100 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-slate-200 dark:border-slate-200">
        <!-- HEADER -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 md:p-8 flex items-center justify-between">
            <h2 class="text-2xl md:text-3xl font-bold">Paramètres des Cookies</h2>
            <button id="cookie-modal-close" class="text-2xl hover:opacity-70 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- CONTENU -->
        <div class="p-6 md:p-8 space-y-6">
            <!-- ESSENTIELS -->
            <div class="border-b border-slate-200 dark:border-slate-200 pb-6">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-900 mb-1">🔒 Cookies Essentiels</h3>
                        <p class="text-slate-700 dark:text-slate-700 text-sm">Nécessaires au fonctionnement du site (authentification, sécurité)</p>
                    </div>
                    <div class="relative inline-block w-12 h-6 bg-green-600 rounded-full cursor-not-allowed opacity-60">
                        <span class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold">✓</span>
                    </div>
                </div>
                <ul class="text-sm text-slate-700 dark:text-slate-700 space-y-1 ml-0">
                    <li>• <strong>Session Laravel</strong> - Maintient votre session utilisateur</li>
                    <li>• <strong>XSRF-TOKEN</strong> - Protection contre les attaques CSRF</li>
                </ul>
            </div>

            <!-- ANALYTIQUES -->
            <div class="border-b border-slate-200 dark:border-slate-200 pb-6">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-900 mb-1">📊 Analytics</h3>
                        <p class="text-slate-700 dark:text-slate-700 text-sm">Pour comprendre comment vous utilisez le site et l'améliorer</p>
                    </div>
                    <label class="relative inline-block w-12 h-6 bg-slate-300 dark:bg-slate-300 rounded-full cursor-pointer">
                        <input type="checkbox" id="cookie-analytics" class="sr-only peer" checked>
                        <span class="absolute inset-y-0 left-0 bg-white dark:bg-white rounded-full shadow transition peer-checked:translate-x-6 peer-checked:bg-blue-600 w-5 h-5 m-0.5"></span>
                    </label>
                </div>
                <ul class="text-sm text-slate-700 dark:text-slate-700 space-y-1 ml-0">
                    <li>• <strong>Google Analytics (_ga)</strong> - Statistiques d'utilisation du site</li>
                </ul>
            </div>

            <!-- MONITORING -->
            <div class="pb-6">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-900 mb-1">⚙️ Monitoring Client</h3>
                        <p class="text-slate-700 dark:text-slate-700 text-sm">Pour tracker les erreurs JavaScript/frontend. <span class="text-blue-700 dark:text-blue-700 font-semibold">Le monitoring serveur reste toujours actif pour la sécurité.</span></p>
                    </div>
                    <label class="relative inline-block w-12 h-6 bg-slate-300 dark:bg-slate-300 rounded-full cursor-pointer">
                        <input type="checkbox" id="cookie-monitoring" class="sr-only peer" checked>
                        <span class="absolute inset-y-0 left-0 bg-white dark:bg-white rounded-full shadow transition peer-checked:translate-x-6 peer-checked:bg-blue-600 w-5 h-5 m-0.5"></span>
                    </label>
                </div>
                <ul class="text-sm text-slate-700 dark:text-slate-700 space-y-1 ml-0">
                    <li>• <strong>Sentry CLIENT</strong> - Erreurs JavaScript (refusable)</li>
                    <li>• <strong>Sentry SERVER</strong> ✅ - Erreurs PHP/Laravel (toujours actif)</li>
                    <li>• <strong>UptimeRobot</strong> ✅ - Monitoring disponibilité (toujours actif)</li>
                </ul>
            </div>
        </div>

        <!-- FOOTER MODAL -->
        <div class="sticky bottom-0 bg-slate-50 dark:bg-slate-50 border-t border-slate-200 dark:border-slate-200 p-6 md:p-8 flex gap-3 md:gap-4 justify-end">
            <button id="cookie-modal-refuse" class="px-6 py-3 bg-slate-200 dark:bg-slate-200 hover:bg-slate-300 dark:hover:bg-slate-300 text-slate-900 dark:text-slate-900 font-semibold rounded-lg transition-all">
                Refuser
            </button>
            <button id="cookie-modal-accept" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all shadow-lg">
                Sauvegarder
            </button>
        </div>
    </div>
</div>

<script src="{{ asset('js/cookie-consent.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Attendre que cookieConsent soit initialisé
        if (typeof window.cookieConsent === 'undefined') {
            setTimeout(arguments.callee, 100);
            return;
        }

        const banner = document.getElementById('cookie-consent-banner');
        const modal = document.getElementById('cookie-modal');
        const acceptBtn = document.getElementById('cookie-accept');
        const refuseBtn = document.getElementById('cookie-refuse');
        const customizeBtn = document.getElementById('cookie-customize');
        const modalAcceptBtn = document.getElementById('cookie-modal-accept');
        const modalRefuseBtn = document.getElementById('cookie-modal-refuse');
        const modalCloseBtn = document.getElementById('cookie-modal-close');
        const analyticsToggle = document.getElementById('cookie-analytics');
        const monitoringToggle = document.getElementById('cookie-monitoring');

        // AFFICHER LE BANNER SI PAS DE CONSENTEMENT
        if (!window.cookieConsent.getConsent()) {
            banner.style.display = 'block';
        }

        // ACCEPTER TOUT
        acceptBtn.addEventListener('click', function() {
            window.cookieConsent.acceptAll();
            banner.style.display = 'none';
        });

        // REFUSER TOUT
        refuseBtn.addEventListener('click', function() {
            window.cookieConsent.refuseAll();
            banner.style.display = 'none';
        });

        // OUVRIR MODAL PERSONNALISATION
        customizeBtn.addEventListener('click', function() {
            modal.classList.remove('hidden');
            const consent = window.cookieConsent.getConsent();
            if (consent) {
                analyticsToggle.checked = consent.analytics || false;
                monitoringToggle.checked = consent.monitoring || false;
            }
        });

        // FERMER MODAL
        modalCloseBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // SAUVEGARDER DEPUIS MODAL
        modalAcceptBtn.addEventListener('click', function() {
            window.cookieConsent.saveCustom(
                analyticsToggle.checked,
                monitoringToggle.checked
            );
            banner.style.display = 'none';
            modal.classList.add('hidden');
        });

        // REFUSER DEPUIS MODAL
        modalRefuseBtn.addEventListener('click', function() {
            window.cookieConsent.refuseAll();
            banner.style.display = 'none';
            modal.classList.add('hidden');
        });

        // FERMER MODAL QUAND ON CLIQUE DEHORS
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

<style>
    #cookie-modal.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #cookie-modal:not(.hidden) {
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Smooth toggle */
    input[type="checkbox"].sr-only + span {
        transition: all 0.3s ease;
    }

    /* Support du dark mode */
    @media (prefers-color-scheme: dark) {
        #cookie-consent-banner {
            background-color: rgb(15, 23, 42);
            color: rgb(240, 240, 240);
        }
    }
</style>
