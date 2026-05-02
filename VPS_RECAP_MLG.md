# 🖥️ Récap VPS Contabo — MIO Ressources
**Ahmed (MLG-20) | mio-ressources.me**

---

## 🔐 Connexion SSH
```bash
ssh root@167.86.94.135
Mamadou2098
```

---

## 📁 Dossier du projet
```bash
cd /var/www/mio-ressources-site
```

---

## 🔄 Déployer une mise à jour
```bash
cd /var/www/mio-ressources-site
git pull origin main
composer install --no-dev --optimize-autoloader
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## ⚙️ Services essentiels

### Nginx
```bash
systemctl status nginx
systemctl restart nginx
systemctl reload nginx
nginx -t  # tester la config
```

### PHP 8.4 FPM
```bash
systemctl status php8.4-fpm
systemctl restart php8.4-fpm
```

### MySQL
```bash
systemctl status mysql
systemctl restart mysql
mysql -u root -pMioRessources@2026!
```

---

## 🗂️ Fichiers importants

| Fichier | Chemin |
|---|---|
| `.env` production | `/var/www/mio-ressources-site/.env` |
| Config Nginx | `/etc/nginx/sites-available/mio-ressources` |
| Logs Laravel | `/var/www/mio-ressources-site/storage/logs/laravel.log` |
| Logs Nginx | `/var/log/nginx/error.log` |

---

## 🐛 Déboguer une erreur

### Voir les logs Laravel
```bash
tail -50 /var/www/mio-ressources-site/storage/logs/laravel.log
```

### Voir les logs Nginx
```bash
tail -50 /var/log/nginx/error.log
```

### Vider tous les caches
```bash
cd /var/www/mio-ressources-site
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

---

## 🔒 SSL (Let's Encrypt)
```bash
# Renouveler manuellement
certbot renew

# Vérifier expiration
certbot certificates
```
SSL expire le **10 juillet 2026** — renouvellement automatique configuré.

---

## 📊 Surveiller le serveur
```bash
# CPU / RAM
htop

# Espace disque
df -h

# Processus PHP
ps aux | grep php
```

---

## 🚨 En cas de panne

| Erreur | Solution |
|---|---|
| 502 Bad Gateway | `systemctl restart php8.4-fpm` |
| 504 Gateway Timeout | `systemctl restart php8.4-fpm nginx` |
| Site inaccessible | `systemctl restart nginx` |
| Erreur DB | `systemctl restart mysql` |

---

## 🌐 Infos importantes

| Info | Valeur |
|---|---|
| IP VPS | `167.86.94.135` |
| Domaine | `mio-ressources.me` |
| PHP | 8.4 |
| Laravel | 12 |
| OS | Ubuntu 24.04 LTS |
| Hébergeur | Contabo |

---

*Généré le 11 avril 2026 — MLG-20*
