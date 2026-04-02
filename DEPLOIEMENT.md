# Guide de Déploiement — MIO Ressources

## Docker : nécessaire ou pas ?

**Non, Docker n'est pas nécessaire.** Pour une application Laravel comme celle-ci, un simple VPS avec les bons packages suffit largement. Docker ajoute de la complexité sans bénéfice réel à ce stade. Ce guide couvre un déploiement classique sur n'importe quel serveur Ubuntu/Debian.

---

## Prérequis serveur

- VPS Ubuntu 22.04 LTS (ou Debian 12) — minimum **1 vCPU / 1 Go RAM / 20 Go SSD**
- Un nom de domaine pointant vers l'IP du serveur
- Accès SSH root ou sudo

---

## Étape 1 — Connexion et mise à jour du serveur

```bash
ssh root@IP_DU_SERVEUR

apt update && apt upgrade -y
```

---

## Étape 2 — Installer PHP 8.2 et les extensions nécessaires

```bash
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring \
  php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd php8.2-redis \
  php8.2-intl php8.2-tokenizer php8.2-fileinfo

php -v  # Vérifier : PHP 8.2.x
```

---

## Étape 3 — Installer MySQL

```bash
apt install -y mysql-server

mysql_secure_installation  # Suivre les instructions (mot de passe root, supprimer les comptes anonymes, etc.)

# Créer la base de données et l'utilisateur
mysql -u root -p
```

```sql
CREATE DATABASE mio_ressources CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'mio_user'@'localhost' IDENTIFIED BY 'MOT_DE_PASSE_FORT';
GRANT ALL PRIVILEGES ON mio_ressources.* TO 'mio_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## Étape 4 — Installer Redis

```bash
apt install -y redis-server

# Activer Redis au démarrage
systemctl enable redis-server
systemctl start redis-server

# Vérifier
redis-cli ping  # Doit retourner : PONG
```

---

## Étape 5 — Installer Nginx

```bash
apt install -y nginx

systemctl enable nginx
systemctl start nginx
```

---

## Étape 6 — Installer Composer

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version  # Vérifier
```

---

## Étape 7 — Installer Node.js et npm

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
node -v && npm -v  # Vérifier
```

---

## Étape 8 — Déployer le projet

### Option A — Depuis Git (recommandé)

```bash
cd /var/www
git clone https://github.com/TON_COMPTE/mio_ressources.git
cd mio_ressources

# Donner les permissions
chown -R www-data:www-data /var/www/mio_ressources
chmod -R 755 /var/www/mio_ressources
chmod -R 775 /var/www/mio_ressources/storage
chmod -R 775 /var/www/mio_ressources/bootstrap/cache
```

### Option B — Upload manuel (FTP/SCP)

```bash
scp -r /chemin/local/mio_ressources root@IP_SERVEUR:/var/www/mio_ressources
```

---

## Étape 9 — Configurer l'environnement (.env)

```bash
cd /var/www/mio_ressources

cp .env.example .env
nano .env
```

Remplir les valeurs suivantes dans `.env` :

```env
APP_NAME="Mio Ressources"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mio_ressources
DB_USERNAME=mio_user
DB_PASSWORD=MOT_DE_PASSE_FORT

CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

SESSION_DRIVER=database
SESSION_ENCRYPT=true

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot_de_passe_application_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre-email@gmail.com"
MAIL_FROM_NAME="Mio Ressources"

PAYTECH_API_KEY=VOTRE_CLE
PAYTECH_API_SECRET=VOTRE_SECRET
PAYTECH_IPN_SECRET=VOTRE_IPN_SECRET
PAYTECH_ENV=prod

FILESYSTEM_DISK=public
LOG_LEVEL=warning
```

---

## Étape 10 — Installer les dépendances et builder

```bash
cd /var/www/mio_ressources

# Dépendances PHP (sans les packages de dev)
composer install --no-dev --optimize-autoloader

# Générer la clé de l'application
php artisan key:generate

# Dépendances JS et build du frontend
npm install
npm run build

# Migrations et lien storage
php artisan migrate --force
php artisan storage:link

# Optimisations Laravel pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Étape 11 — Configurer Nginx

```bash
nano /etc/nginx/sites-available/mio_ressources
```

Coller cette configuration :

```nginx
server {
    listen 80;
    server_name votre-domaine.com www.votre-domaine.com;
    root /var/www/mio_ressources/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 52M;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
}
```

```bash
# Activer le site et tester
ln -s /etc/nginx/sites-available/mio_ressources /etc/nginx/sites-enabled/
nginx -t  # Doit afficher : syntax is ok
systemctl reload nginx
```

---

## Étape 12 — SSL gratuit avec Let's Encrypt (HTTPS)

```bash
apt install -y certbot python3-certbot-nginx

certbot --nginx -d votre-domaine.com -d www.votre-domaine.com

# Renouvellement automatique (déjà configuré par certbot, vérifier)
certbot renew --dry-run
```

---

## Étape 13 — Queue Workers avec Supervisor

```bash
apt install -y supervisor

nano /etc/supervisor/conf.d/mio-worker.conf
```

```ini
[program:mio-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/mio_ressources/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/mio_ressources/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
supervisorctl reread
supervisorctl update
supervisorctl start mio-worker:*

# Vérifier que les workers tournent
supervisorctl status
```

---

## Étape 14 — Scheduler Laravel (cron)

```bash
crontab -e -u www-data
```

Ajouter cette ligne :

```
* * * * * cd /var/www/mio_ressources && php artisan schedule:run >> /dev/null 2>&1
```

---

## Étape 15 — Vérifications finales

```bash
# Tester la config Laravel
php artisan about

# Vérifier les permissions storage
ls -la /var/www/mio_ressources/storage/logs/

# Tester Redis
php artisan tinker
>>> Cache::put('test', 'ok', 10);
>>> Cache::get('test');  // Doit retourner 'ok'

# Vérifier les routes
php artisan route:list | head -20

# Vérifier les workers
supervisorctl status mio-worker:*
```

---

## Mise à jour du projet (déploiement continu)

À chaque nouvelle version, exécuter :

```bash
cd /var/www/mio_ressources

git pull origin main

composer install --no-dev --optimize-autoloader
npm install && npm run build

php artisan migrate --force

php artisan config:clear && php artisan config:cache
php artisan route:clear  && php artisan route:cache
php artisan view:clear   && php artisan view:cache

supervisorctl restart mio-worker:*
```

---

## Résumé des services à maintenir actifs

| Service | Commande de vérification |
|---|---|
| Nginx | `systemctl status nginx` |
| PHP-FPM | `systemctl status php8.2-fpm` |
| MySQL | `systemctl status mysql` |
| Redis | `systemctl status redis-server` |
| Supervisor (workers) | `supervisorctl status` |
| Cron scheduler | `crontab -l -u www-data` |

---

## Surveillance en production

### Sentry — Alertes d'erreurs en temps réel

Sentry capture automatiquement toutes les exceptions PHP et envoie une alerte email immédiate.

**1. Créer un compte et un projet**
- Aller sur [sentry.io](https://sentry.io) → Sign up (gratuit)
- Créer un projet → choisir **Laravel**
- Copier le **DSN** fourni (ex: `https://xxx@oXXX.ingest.sentry.io/XXX`)

**2. Installer le package**
```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=VOTRE_DSN
```

**3. Ajouter dans `.env`**
```env
SENTRY_LARAVEL_DSN=https://xxx@oXXX.ingest.sentry.io/XXX
SENTRY_TRACES_SAMPLE_RATE=0.1
```

**4. Tester**
```bash
php artisan sentry:test
# → Une erreur test doit apparaître dans le dashboard Sentry
```

---

### UptimeRobot — Surveillance de disponibilité

UptimeRobot vérifie `/health` toutes les 5 minutes et envoie un SMS/email si le site tombe.

**1. Créer un compte**
- Aller sur [uptimerobot.com](https://uptimerobot.com) → Sign up (gratuit)

**2. Ajouter un moniteur**
- Type : **HTTP(s)**
- URL : `https://votre-domaine.com/health`
- Intervalle : **5 minutes**
- Alertes : email + SMS

**3. Vérifier que `/health` répond bien**
```bash
curl https://votre-domaine.com/health
# Doit retourner : {"status":"healthy",...}
```

---

### Logs Nginx — Erreurs serveur

```bash
tail -f /var/log/nginx/error.log
tail -f /var/log/nginx/access.log
```

---

## En cas de problème

```bash
# Voir les logs Laravel
tail -f /var/www/mio_ressources/storage/logs/laravel.log

# Voir les logs Nginx
tail -f /var/log/nginx/error.log

# Voir les logs des workers
tail -f /var/www/mio_ressources/storage/logs/worker.log

# Vider tous les caches
php artisan optimize:clear
```
