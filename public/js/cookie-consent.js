/**
 * Gestion complète du consentement aux cookies
 *
 * IMPORTANT SÉCURITÉ :
 * - Sentry SERVER (PHP) = TOUJOURS ACTIF (obligation légale + sécurité)
 * - Sentry CLIENT (JS) = REFUSABLE selon consentement
 * - UptimeRobot = TOUJOURS ACTIF (ping externe, pas de cookies);
 * - Google Analytics = REFUSABLE selon consentement
 */

class CookieConsent {
    constructor() {
        this.STORAGE_KEY = 'cookieConsent';
        this.DEFAULTS = {
            analytics: false,
            monitoring: false,
            timestamp: null
        };
        this.init();
    }

    /**
     * Initialise le système
     */
    init() {
        const consent = this.getConsent();

        if (consent) {
            // Applique le consentement
            this.applyConsent(consent);
        }
    }

    /**
     * Récupère le consentement stocké
     */
    getConsent() {
        try {
            const stored = localStorage.getItem(this.STORAGE_KEY);
            return stored ? JSON.parse(stored) : null;
        } catch (e) {
            console.warn('Erreur lecture localStorage:', e);
            return null;
        }
    }

    /**
     * Sauvegarde le consentement
     */
    setConsent(consent) {
        try {
            const data = {
                ...this.DEFAULTS,
                ...consent,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(data));
            this.applyConsent(data);
            return true;
        } catch (e) {
            console.error('Erreur sauvegarde localStorage:', e);
            return false;
        }
    }

    /**
     * Applique réellement le consentement
     */
    applyConsent(consent) {
        console.log('🔐 SÉCURITÉ: Sentry SERVER (PHP) reste TOUJOURS ACTIF');
        console.log('⚠️  SÉCURITÉ: UptimeRobot reste TOUJOURS ACTIF');

        // GOOGLE ANALYTICS
        if (consent.analytics === true) {
            this.enableGoogleAnalytics();
        } else {
            this.disableGoogleAnalytics();
        }

        // SENTRY CLIENT (JavaScript)
        if (consent.monitoring === true) {
            this.enableSentryClient();
        } else {
            this.disableSentryClient();
        }

        console.log('✅ Consentement appliqué:', consent);
    }

    /**
     * Active Google Analytics
     */
    enableGoogleAnalytics() {
        if (window.gtag) {
            window.gtag('consent', 'update', {
                'analytics_storage': 'granted'
            });
            console.log('✅ Google Analytics ACTIVÉ');
        }
    }

    /**
     * Désactive Google Analytics
     */
    disableGoogleAnalytics() {
        if (window.gtag) {
            window.gtag('consent', 'update', {
                'analytics_storage': 'denied'
            });
            console.log('⛔ Google Analytics DÉSACTIVÉ');
        }
    }

    /**
     * Active Sentry CLIENT (JavaScript errors)
     * NOTE: Sentry SERVER reste toujours actif côté serveur
     */
    enableSentryClient() {
        if (window.Sentry) {
            window.Sentry.captureMessage('User accepted Sentry CLIENT monitoring', 'info');
            console.log('✅ Sentry CLIENT (JS errors) ACTIVÉ');
        } else {
            console.log('ℹ️  Sentry CLIENT (JS errors) ACTIVÉ (SDK n\'est pas chargé)');
        }
    }

    /**
     * Désactive Sentry CLIENT (JavaScript errors)
     * NOTE: Sentry SERVER continue de fonctionner côté serveur
     */
    disableSentryClient() {
        if (window.Sentry) {
            // On notifie Sentry que l'utilisateur a refusé le monitoring CLIENT
            window.Sentry.captureMessage('User refused Sentry CLIENT monitoring', 'info');
            // Optionnel: désactiver la capture d'erreurs JS
            window.Sentry.setTag('client_monitoring_enabled', false);
        }
        console.log('⛔ Sentry CLIENT (JS errors) DÉSACTIVÉ');
        console.log('🔐 Sentry SERVER (PHP errors) reste ACTIF pour la sécurité');
    }

    /**
     * UptimeRobot continue indépendamment
     * C'est juste un robot qui ping l'URL périodiquement
     * Pas de cookies, pas d'impact utilisateur
     */

    /**
     * Refuse tous les cookies
     */
    refuseAll() {
        this.setConsent({
            analytics: false,
            monitoring: false
        });
    }

    /**
     * Accepte tous les cookies
     */
    acceptAll() {
        this.setConsent({
            analytics: true,
            monitoring: true
        });
    }

    /**
     * Personnalisé
     */
    saveCustom(analytics, monitoring) {
        this.setConsent({
            analytics: analytics,
            monitoring: monitoring
        });
    }

    /**
     * Réinitialise (pour test)
     */
    reset() {
        localStorage.removeItem(this.STORAGE_KEY);
        console.log('🔄 Consentement réinitialisé');
    }

    /**
     * Affiche le banner à nouveau
     */
    showBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.style.display = 'block';
        }
    }
}

// Initialise dès que le DOM est prêt
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.cookieConsent = new CookieConsent();
    });
} else {
    window.cookieConsent = new CookieConsent();
}

