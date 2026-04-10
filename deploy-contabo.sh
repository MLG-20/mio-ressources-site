#!/bin/bash

###############################################################################
# Script de déploiement automatisé pour MIO Ressources sur Contabo
# OS: Ubuntu 24.04 LTS
# 
# Usage:
#   chmod +x deploy-contabo.sh
#   sudo ./deploy-contabo.sh
###############################################################################

set -e  # Arrêter à la première erreur

# Couleurs pour l'output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="${1:-mio-ressources.local}"
GIT_REPO="${2:-https://github.com/TON_COMPTE/mio_ressources.git}"
PROJECT_PATH="/var/www/mio_ressources"
DB_NAME="mio_ressources"
DB_USER="mio_user"
DB_PASS=$(openssl rand -base64 16)  # Générer un mot de passe sécurisé

function print_header() {
    echo -e "\n${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}\n"
}

function print_success() {
    echo -e "${GREEN}✓ $1${NC}\n"
}

function print_error() {
    echo -e "${RED}✗ $1${NC}\n"
    exit 1
}

function print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}\n"
}

# Vérifier qu'on est root
if [[ $EUID -ne 0 ]]; then
    print_error "Ce script doit être lancé avec sudo"
fi

print_header "DÉPLOIEMENT MIO RESSOURCES SUR CONTABO"
echo "Domaine: $DOMAIN"
echo "Repo Git: $GIT_REPO"
echo "Chemin: $PROJECT_PATH"
echo "Base de données: $DB_NAME"
echo "Utilisateur DB: $DB_USER"
echo "Mot de passe DB: $DB_PASS (sauvegardez-le!)"
echo ""
read -p "Continuer? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    exit 1
fi

###############################################################################
# 1. Mise à jour du système
###############################################################################
print_header "1️⃣  Mise à jour du système"
apt update
apt upgrade -y
print_success "Système mis à jour"

###############################################################################
# 2. Installation de PHP 8.2 et extensions
###############################################################################
print_header "2️⃣  Installation de PHP 8.2"
apt install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt update

apt install -y \
    php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd \
    php8.2-redis php8.2-intl php8.2-tokenizer php8.2-fileinfo

print_success "PHP 8.2 installé (version: $(php -v | head -n 1))"

###############################################################################
# 3. Installation de MySQL
###############################################################################
print_header "3️⃣  Installation de MySQL"
apt install -y mysql-server

# Démarrer MySQL
systemctl enable mysql
systemctl start mysql

# Créer la base de données et l'utilisateur
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

print_success "MySQL configuré - Base: $DB_NAME, Utilisateur: $DB_USER"

###############################################################################
# 4. Installation de Redis
###############################################################################
print_header "4️⃣  Installation de Redis"
apt install -y redis-server

systemctl enable redis-server
systemctl start redis-server

# Vérifier Redis
if redis-cli ping | grep -q PONG; then
    print_success "Redis est opérationnel"
else
    print_error "Redis ne répond pas"
fi

###############################################################################
# 5. Installation de Nginx
###############################################################################
print_header "5️⃣  Installation de Nginx"
apt install -y nginx

systemctl enable nginx
systemctl start nginx

print_success "Nginx installé et démarré"

###############################################################################
# 6. Installation de Composer
###############################################################################
print_header "6️⃣  Installation de Composer"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

print_success "Composer installé (version: $(composer --version))"

###############################################################################
# 7. Installation de Node.js 20
###############################################################################
print_header "7️⃣  Installation de Node.js"
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs

print_success "Node.js installé ($(node -v)), npm $(npm -v)"

###############################################################################
# 8. Déployer le projet
###############################################################################
print_header "8️⃣  Déploiement du projet"

# Créer le répertoire
mkdir -p /var/www
cd /var/www

# Cloner le repo (si pas déjà présent)
if [ ! -d "$PROJECT_PATH/.git" ]; then
    git clone "$GIT_REPO" mio_ressources
    print_success "Projet cloné depuis Git"
else
    cd "$PROJECT_PATH"
    git pull origin main
    print_success "Projet à jour (git pull)"
fi

# Permissions
chown -R www-data:www-data "$PROJECT_PATH"
chmod -R 755 "$PROJECT_PATH"
chmod -R 775 "$PROJECT_PATH/storage"
chmod -R 775 "$PROJECT_PATH/bootstrap/cache"

print_success "Permissions configurées"

###############################################################################
# 9. Configuration .env
###############################################################################
print_header "9️⃣  Configuration de l'environnement"

cd "$PROJECT_PATH"

if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Fichier .env créé"
else
    print_warning ".env existe déjà, mise à jour sélective..."
fi

# Mettre à jour le .env avec les valeurs
php_app_key=$(php artisan key:generate --show 2>/dev/null || echo "base64:placeholder")

# Utiliser sed pour mettre à jour les valeurs clés
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|^APP_URL=.*|APP_URL=https://$DOMAIN|" .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env
sed -i "s|^CACHE_STORE=.*|CACHE_STORE=redis|" .env
sed -i "s|^QUEUE_CONNECTION=.*|QUEUE_CONNECTION=redis|" .env
sed -i "s|^SESSION_DRIVER=.*|SESSION_DRIVER=database|" .env
sed -i "s|^FILESYSTEM_DISK=.*|FILESYSTEM_DISK=public|" .env

print_success ".env configuré (à compléter: MAIL_*, PAYTECH_*, etc.)"

###############################################################################
# 10. Installer dépendances et builder
###############################################################################
print_header "🔟 Installation des dépendances"

cd "$PROJECT_PATH"

# Dépendances PHP
composer install --no-dev --optimize-autoloader
print_success "Dépendances PHP installées"

# Générer la clé (si pas encore fait)
php artisan key:generate 2>/dev/null || true

# Dépendances JS et build
npm install --legacy-peer-deps
npm run build
print_success "Frontend buildé (npm build)"

# Migrations
php artisan migrate --force 2>/dev/null || print_warning "Migrations: vérifier les erreurs"

# Lien storage
php artisan storage:link 2>/dev/null || true

# Optimisations
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "Laravel optimisé pour la production"

###############################################################################
# 11. Configurer Nginx
###############################################################################
print_header "1️⃣1️⃣  Configuration de Nginx"

cat > /etc/nginx/sites-available/mio_ressources <<EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_PATH/public;

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 52M;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
}
EOF

# Activer le site
ln -sf /etc/nginx/sites-available/mio_ressources /etc/nginx/sites-enabled/

# Tester la configuration
if nginx -t > /dev/null 2>&1; then
    systemctl reload nginx
    print_success "Configuration Nginx vérifiée et rechargée"
else
    print_error "Erreur dans la configuration Nginx"
fi

###############################################################################
# 12. SSL avec Let's Encrypt
###############################################################################
print_header "1️⃣2️⃣  Configuration SSL (Let's Encrypt)"

apt install -y certbot python3-certbot-nginx

# Attendre 30 secondes pour que Nginx soit prêt
sleep 5

if certbot certonly --nginx -d "$DOMAIN" -d "www.$DOMAIN" --non-interactive --agree-tos --email admin@$DOMAIN 2>/dev/null; then
    print_success "Certificat SSL obtenu"
    
    # Mettre à jour la config Nginx pour HTTPS
    cat > /etc/nginx/sites-available/mio_ressources <<EOF
server {
    listen 80;
    server_name $DOMAIN www.$DOMAIN;
    return 301 https://\$server_name\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name $DOMAIN www.$DOMAIN;
    root $PROJECT_PATH/public;

    ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 52M;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
}
EOF

    nginx -t > /dev/null && systemctl reload nginx
    print_success "Nginx configuré pour HTTPS"
else
    print_warning "SSL: certbot n'a pas pu valider le domaine (DNS peut-être pas pointé?)"
    echo "Conseil: pointez votre domaine vers $DOMAIN, puis relancez: certbot certonly --nginx -d $DOMAIN"
fi

###############################################################################
# 13. Supervisor pour les Queue Workers
###############################################################################
print_header "1️⃣3️⃣  Configuration des Queue Workers"

apt install -y supervisor

cat > /etc/supervisor/conf.d/mio-worker.conf <<EOF
[program:mio-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=$PROJECT_PATH/storage/logs/worker.log
stopwaitsecs=3600
EOF

supervisorctl reread
supervisorctl update
supervisorctl start mio-worker:*

if supervisorctl status mio-worker:* | grep -q "RUNNING"; then
    print_success "Queue Workers démarrés (2 processes)"
else
    print_warning "Queue Workers: vérifier avec 'supervisorctl status'"
fi

###############################################################################
# 14. Cron Scheduler
###############################################################################
print_header "1️⃣4️⃣  Configuration du Cron Scheduler"

# Ajouter le cron pour www-data si pas déjà présent
crontab_line="* * * * * cd $PROJECT_PATH && php artisan schedule:run >> /dev/null 2>&1"

if ! (crontab -u www-data -l 2>/dev/null | grep -q "artisan schedule:run"); then
    (crontab -u www-data -l 2>/dev/null; echo "$crontab_line") | crontab -u www-data -
    print_success "Cron scheduler configuré"
else
    print_success "Cron scheduler déjà configuré"
fi

###############################################################################
# 15. Résumé et vérifications finales
###############################################################################
print_header "✅ DÉPLOIEMENT TERMINÉ"

echo "Configuration:"
echo "  Domaine: $DOMAIN"
echo "  Chemin: $PROJECT_PATH"
echo ""
echo "Base de données:"
echo "  Nom: $DB_NAME"
echo "  Utilisateur: $DB_USER"
echo "  Mot de passe: $DB_PASS"
echo ""
echo "Services actifs:"
systemctl status nginx | grep Active
systemctl status php8.2-fpm | grep Active
systemctl status mysql | grep Active
systemctl status redis-server | grep Active

echo ""
print_warning "⚠️  ACTIONS À FAIRE MAINTENANT:"
echo "1. Pointer votre domaine vers: $(hostname -I | awk '{print $1}')"
echo "2. Compléter .env avec vos données MAIL et PAYTECH"
echo "   nano $PROJECT_PATH/.env"
echo "3. Si domaine non pointé: relancer certbot"
echo "   certbot certonly --nginx -d $DOMAIN"
echo "4. Vérifier les services:"
echo "   - supervisorctl status"
echo "   - systemctl status nginx"
echo "5. Consulter les logs:"
echo "   tail -f $PROJECT_PATH/storage/logs/laravel.log"
echo ""
print_success "Déploiement réussi ! 🎉"
