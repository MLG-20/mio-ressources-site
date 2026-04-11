#!/bin/bash
echo "🚀 Déploiement MIO Ressources..."

cd /var/www/mio-ressources-site

git pull origin main

composer install --no-dev --optimize-autoloader

npm run build

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage
chmod -R 775 storage bootstrap/cache

echo "✅ Déploiement terminé !"
