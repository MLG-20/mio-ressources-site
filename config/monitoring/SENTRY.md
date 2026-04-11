# Configuration Sentry — Alertes d'erreurs

## Qu'est-ce que Sentry ?

Sentry capture **automatiquement** toutes les erreurs PHP et envoie une **alerte email** immédiate. Très utile pour détecter les bugs en production.

## Installation (étapes)

### 1. Créer un compte Sentry

- Aller sur [sentry.io](https://sentry.io)
- Sign up (gratuit)
- Créer un nouveau projet → choisir **Laravel**
- Copier le **DSN** (ex: `https://xxx@oXXX.ingest.sentry.io/XXX`)

### 2. Installer le package Laravel

Sur le serveur, exécuter :

```bash
cd /var/www/mio-ressources-site

composer require sentry/sentry-laravel

php artisan sentry:publish --dsn="VOTRE_DSN_ICI"
```

Remplace `VOTRE_DSN_ICI` par le DSN copié plus haut.

### 3. Ajouter dans `.env`

Ajouter cette ligne dans `/var/www/mio-ressources-site/.env` :

```env
SENTRY_LARAVEL_DSN=https://xxx@oXXX.ingest.sentry.io/XXX
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_ENVIRONMENT=production
```

### 4. Tester

```bash
php artisan sentry:test
```

Une erreur test doit apparaître dans le dashboard Sentry.

## Alertes

Dans Sentry, configurer :
- **Email alerts** → Recevoir une alerte à chaque erreur
- **Slack** (optionnel) → Notifier un canal Slack

## Limites gratuites

- **5000 erreurs/mois** (amplement suffisant pour une petite plateforme)
- Rétention : **30 jours** d'historique
