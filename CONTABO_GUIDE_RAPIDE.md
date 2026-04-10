# Guide de déploiement rapide sur Contabo

## 🚀 Démarrage rapide

### 1. **Commander votre VPS Contabo**
- Aller sur [contabo.com](https://contabo.com)
- Choisir **VPS Cloud** avec **Ubuntu 24.04 LTS** minimum 1 vCPU / 1 Go RAM / 20 Go SSD
- Recevoir les identifiants SSH par email

### 2. **Préparer votre clé SSH** (recommandé mais optionnel)
Sur votre ordinateur local:
```bash
# Si vous n'avez pas de clé SSH
ssh-keygen -t ed25519 -C "votre-email@example.com"

# Afficher votre clé publique
cat ~/.ssh/id_ed25519.pub
```

Ajouter la clé SSH dans le Control Panel Contabo (Manage SSH Keys)

### 3. **Se connecter au serveur Contabo**
```bash
ssh root@IP_DE_VOTRE_SERVEUR
# ou avec une clé SSH:
ssh -i ~/.ssh/id_ed25519 root@IP_DE_VOTRE_SERVEUR
```

### 4. **Préparer le script de déploiement**
Sur le serveur Contabo:
```bash
# Télécharger le script depuis votre repo
curl -fsSL https://raw.githubusercontent.com/TON_COMPTE/mio_ressources/main/deploy-contabo.sh -o deploy-contabo.sh
chmod +x deploy-contabo.sh

# Ou le copier depuis votre ordinateur
scp deploy-contabo.sh root@IP_SERVEUR:/root/
```

### 5. **Lancer le déploiement**
```bash
sudo ./deploy-contabo.sh votre-domaine.com https://github.com/TON_COMPTE/mio_ressources.git
```

**Exemple :**
```bash
sudo ./deploy-contabo.sh mio-ressources.com https://github.com/moncompte/mio_ressources.git
```

Le script va automatiquement:
- ✅ Installer PHP 8.2, MySQL, Redis, Nginx, Node.js
- ✅ Cloner votre projet depuis Git
- ✅ Configurer la base de données
- ✅ Installer les dépendances (Composer, npm)
- ✅ Builder le frontend
- ✅ Configurer Nginx
- ✅ Obtenir un certificat SSL gratuit (Let's Encrypt)
- ✅ Démarrer les Queue Workers
- ✅ Configurer le Cron Scheduler

Durée estimée: **10-15 minutes** (dépend de la vitesse du serveur)

---

## ⚙️ Après le déploiement

### 1. **Pointer le domaine**
Ajouter ces enregistrements DNS chez votre registrar (ex: OVH, Namecheap, etc.):

| Type | Nom | Valeur |
|------|-----|--------|
| A | @ | IP_DE_VOTRE_SERVEUR |
| A | www | IP_DE_VOTRE_SERVEUR |
| MX | @ | mail.votre-domaine.com (optionnel) |

**Attendre 24h** pour la propagation DNS complète.

### 2. **Compléter le .env**
```bash
ssh root@IP_SERVEUR
nano /var/www/mio_ressources/.env
```

Mettre à jour ces variables **obligatoires**:
```env
# Email (utiliser Gmail avec mot de passe d'application)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot_de_passe_app_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com

# Paiement PayTech
PAYTECH_API_KEY=VOTRE_CLE_API
PAYTECH_API_SECRET=VOTRE_SECRET
PAYTECH_IPN_SECRET=VOTRE_IPN_SECRET
PAYTECH_ENV=prod

# Sentry (alertes - optionnel mais recommandé)
SENTRY_LARAVEL_DSN=https://xxx@oXXX.ingest.sentry.io/XXX
```

Puis redémarrer:
```bash
cd /var/www/mio_ressources
php artisan config:cache
supervisorctl restart mio-worker:*
systemctl reload nginx
```

### 3. **Vérifier que tout fonctionne**
```bash
# État des services
systemctl status nginx
systemctl status php8.2-fpm
systemctl status mysql
systemctl status redis-server

# État des workers
supervisorctl status mio-worker:*

# Logs de Laravel
tail -f /var/www/mio_ressources/storage/logs/laravel.log

# Tester la base de données
cd /var/www/mio_ressources && php artisan tinker
>>> \App\Models\User::count()  # Afficher le nombre d'utilisateurs
>>> exit
```

### 4. **Certificat SSL (si domaine pas reconnu)**
```bash
certbot certonly --nginx -d votre-domaine.com -d www.votre-domaine.com
# Le certificat est auto-renouvelé chaque jour via cron
```

---

## 📋 Configuration Gmail pour l'envoi d'emails

1. Activer l'authentification 2FA sur votre compte Google
2. Aller sur [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
3. Générer un mot de passe d'application (16 caractères)
4. Copier le mot de passe dans `.env` à `MAIL_PASSWORD`

---

## 🔄 Mettre à jour le projet (déploiement continu)

À chaque fois que vous poussez du code:
```bash
ssh root@IP_SERVEUR

cd /var/www/mio_ressources

# Récupérer les changements
git pull origin main

# Mettre à jour les dépendances
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Appliquer les migrations
php artisan migrate --force

# Vider les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrer les workers
supervisorctl restart mio-worker:*

# Vérifier les logs
tail -f storage/logs/laravel.log
```

---

## ⚠️ En cas de problème

### Site ne charge pas
```bash
# Vérifier les permissions
chown -R www-data:www-data /var/www/mio_ressources
chmod -R 755 /var/www/mio_ressources

# Vérifier Nginx
nginx -t
systemctl reload nginx

# Regarder les logs
tail -f /var/log/nginx/error.log
```

### Erreurs dans les logs Laravel
```bash
# Voir les 50 dernières lignes
tail -50 /var/www/mio_ressources/storage/logs/laravel.log

# Ou suivre en temps réel
tail -f /var/www/mio_ressources/storage/logs/laravel.log
```

### Queue workers ne fonctionnent pas
```bash
# Vérifier le statut
supervisorctl status

# Redémarrer
supervisorctl restart mio-worker:*

# Voir les logs
tail -f /var/www/mio_ressources/storage/logs/worker.log
```

### Redis ne répond pas
```bash
systemctl restart redis-server
redis-cli ping  # Doit retourner: PONG
```

---

## 📞 Support Contabo

- **Panneau de contrôle**: [vps.contabo.com](https://vps.contabo.com)
- **Redémarrer le serveur**: Control Panel → VPS → Power On/Off
- **Attribuer une nouvelle IP**: Control Panel → IP Management
- **Support ticket**: tickets.contabo.com

---

## 🔐 Sécurité basique (optionnel mais conseillé)

```bash
# Changer le port SSH (évite les bots)
sed -i 's/#Port 22/Port 2222/' /etc/ssh/sshd_config
systemctl reload ssh

# Ajouter un pare-feu
apt install -y ufw
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp    # SSH (changer 22 par 2222 si modifié)
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw enable

# Créer un utilisateur non-root
adduser deployer
usermod -aG sudo deployer
# Ajouter sa clé SSH
su - deployer
mkdir ~/.ssh
# Coller la clé publique dans ~/.ssh/authorized_keys
```

---

**Besoin d'aide?** Consulter les logs ou relancer: `sudo ./deploy-contabo.sh`
