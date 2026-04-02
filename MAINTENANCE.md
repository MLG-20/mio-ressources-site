# Guide de Maintenance — MIO Ressources

## Commandes du quotidien

### Démarrer / arrêter le site

```bash
# Mettre le site en maintenance (les visiteurs voient une page propre)
php artisan down --message="Mise à jour en cours, revenez dans 5 minutes." --retry=60

# Remettre le site en ligne
php artisan up
```

---

### Déployer une mise à jour

À chaque fois que tu pousses du nouveau code sur le serveur :

```bash
# 1. Récupérer le nouveau code
git pull origin main

# 2. Mettre à jour les dépendances PHP
composer install --no-dev --optimize-autoloader

# 3. Mettre à jour le frontend
npm install && npm run build

# 4. Appliquer les nouvelles migrations de base de données
php artisan migrate --force

# 5. Vider et reconstruire tous les caches Laravel
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Redémarrer les workers (pour prendre en compte le nouveau code)
supervisorctl restart mio-worker:*
```

---

### Gérer les backups

```bash
# Lancer un backup manuellement (DB + fichiers uploadés)
php artisan backup:run

# Voir la liste des backups existants
php artisan backup:list

# Nettoyer les vieux backups (supprime ceux > 7 jours)
php artisan backup:clean
```

Les backups se trouvent dans : `storage/app/mio-ressources/`
Ils tournent automatiquement chaque nuit :
- **2h00** → backup complet
- **3h00** → nettoyage des anciens backups

---

### Surveiller les logs

Les logs sont créés un fichier par jour et conservés 30 jours.

```bash
# Voir les erreurs du jour en temps réel
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log

# Chercher toutes les erreurs critiques du jour
grep "ERROR\|CRITICAL" storage/logs/laravel-$(date +%Y-%m-%d).log

# Voir les 50 dernières lignes de log
tail -n 50 storage/logs/laravel-$(date +%Y-%m-%d).log

# Voir les logs d'un jour précis (ex: 2025-03-28)
cat storage/logs/laravel-2025-03-28.log

# Voir les logs des workers (queue)
tail -f storage/logs/worker.log
```

---

### Vérifier la santé du site

```bash
# Depuis le terminal du serveur
curl https://ton-domaine.com/health

# Réponse attendue si tout va bien :
# { "status": "healthy", "checks": { "database": "ok", "cache": "ok" } }
```

> Astuce : configure **UptimeRobot** (gratuit) pour surveiller `/health` automatiquement.
> Il t'envoie un email dès que le site tombe.

---

### Gérer les workers (emails & notifications)

Les workers traitent les emails en arrière-plan via Redis.

```bash
# Voir l'état des workers
supervisorctl status

# Redémarrer les workers (après une mise à jour du code)
supervisorctl restart mio-worker:*

# Voir les jobs en attente dans la queue
php artisan queue:monitor

# Vider la queue (annule tous les jobs en attente — à utiliser avec précaution)
php artisan queue:clear redis
```

---

### Base de données

```bash
# Appliquer les migrations
php artisan migrate --force

# Voir l'état des migrations (quelles tables existent)
php artisan migrate:status

# Peupler les matières et semestres (sans supprimer les données existantes)
php artisan db:seed --class=AcademicDataSeeder

# Créer ou mettre à jour le compte super admin
php artisan db:seed --class=AdminSeeder

# Accéder à la console MySQL directement
mysql -u mio_user -p mio_ressources
```

---

### Vider les caches

```bash
# Tout vider d'un coup (config + routes + vues + cache app)
php artisan optimize:clear

# Ou vider individuellement
php artisan config:clear   # Config .env
php artisan route:clear    # Routes
php artisan view:clear     # Templates Blade
php artisan cache:clear    # Cache Redis
```

---

### Redis

```bash
# Vérifier que Redis fonctionne
redis-cli ping
# Réponse attendue : PONG

# Voir les clés en cache (pour déboguer)
redis-cli keys "*"

# Vider tout le cache Redis (attention en prod)
redis-cli flushall
```

---

### Vérifier les services actifs

```bash
# Nginx (serveur web)
systemctl status nginx

# PHP-FPM
systemctl status php8.2-fpm

# MySQL
systemctl status mysql

# Redis
systemctl status redis-server

# Workers (Supervisor)
supervisorctl status
```

---

## En cas de bug en production

### Étape 1 — Identifier l'erreur

```bash
# Voir les dernières erreurs
tail -n 100 storage/logs/laravel-$(date +%Y-%m-%d).log | grep "ERROR\|CRITICAL\|exception"
```

### Étape 2 — Corriger et déployer

```bash
# Mettre en maintenance le temps de corriger
php artisan down

# Appliquer le correctif (git pull ou édition directe)
git pull origin main

# Vider les caches
php artisan optimize:clear

# Remettre en ligne
php artisan up
```

### Étape 3 — Vérifier

```bash
# Contrôler que tout est ok
curl https://ton-domaine.com/health

# Surveiller les logs 2-3 minutes
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log
```

---

## Intégrer Sentry (alertes d'erreurs par email)

Sentry t'envoie un email automatiquement à chaque erreur en prod.

```bash
# 1. Installer le package
composer require sentry/sentry-laravel

# 2. Publier la configuration
php artisan sentry:publish

# 3. Ajouter dans .env
SENTRY_LARAVEL_DSN=https://xxxxxxx@sentry.io/xxxxxxx

# 4. Tester que Sentry reçoit bien les erreurs
php artisan sentry:test
```

> Créer un compte gratuit sur **sentry.io** (10 000 erreurs/mois gratuites).


On reprend les tests locaux de MIO Ressources
