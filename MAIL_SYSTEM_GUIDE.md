# 📧 Guide Complet : Système Mail & Sécurité

## ⚠️ CRITIQUE : Configuration Mail en Production

### 🎯 Problème Actuel
Le système mail ne fonctionnait pas car :
1. ❌ Certaines notifications n'implémentaient pas `ShouldQueue`
2. ❌ EventServiceProvider n'était pas enregistré
3. ⚠️ Config mail peut être incorrecte en production

### ✅ Corrections Apportées

#### 1. Notifications Corrigées (ShouldQueue)
Toutes les notifications critiques utilisent maintenant `implements ShouldQueue` :
- ✅ `AdminLoginAlert` - Alerte connexion admin
- ✅ `PasswordChangedNotification` - Changement mot de passe
- ✅ `NewResourceNotification` - Nouvelle ressource
- ✅ `PrivateLessonReminderNotification` - Rappel cours
- ✅ `PurchaseInvoiceNotification` - Facture achat
- ✅ Toutes les autres notifications

#### 2. EventServiceProvider Enregistré
```php
// bootstrap/providers.php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\EventServiceProvider::class,  // ✅ AJOUTÉ
    App\Providers\Filament\AdminPanelProvider::class,
];
```

#### 3. Commande de Test Créée
```bash
php artisan mail:test votre-email@gmail.com
```

## 🚀 Déploiement en Production

### Étape 1 : Configuration .env
Copiez le fichier `.env.mail.example` et configurez :

```env
# MAIL CONFIGURATION
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mlamine.gueye1@univ-thies.sn
MAIL_PASSWORD=lxbjteljhhsuiijd  # ⚠️ Mot de passe APPLICATION Gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="mlamine.gueye1@univ-thies.sn"
MAIL_FROM_NAME="MIO Ressources"

# QUEUE CONFIGURATION (IMPORTANT!)
QUEUE_CONNECTION=database  # Pas 'sync' en production!
```

### Étape 2 : Créer la Table Jobs
```bash
cd /var/www/app_mio_ressources
php artisan migrate  # Créer la table 'jobs'
```

### Étape 3 : Lancer le Queue Worker
Le worker doit tourner en permanence pour traiter les emails.

**Option A : Avec Supervisor (RECOMMANDÉ)**
```ini
# /etc/supervisor/conf.d/mio-queue-worker.conf
[program:mio-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/app_mio_ressources/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/app_mio_ressources/storage/logs/queue-worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start mio-queue-worker:*
```

**Option B : Avec Screen (Temporaire)**
```bash
screen -dmS queue-worker bash -c "cd /var/www/app_mio_ressources && php artisan queue:work --tries=3"
# Voir: screen -r queue-worker
# Sortir: Ctrl+A puis D
```

**Option C : Avec Systemd**
```ini
# /etc/systemd/system/mio-queue-worker.service
[Unit]
Description=MIO Ressources Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/app_mio_ressources
ExecStart=/usr/bin/php /var/www/app_mio_ressources/artisan queue:work --sleep=3 --tries=3
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
sudo systemctl daemon-reload
sudo systemctl enable mio-queue-worker
sudo systemctl start mio-queue-worker
sudo systemctl status mio-queue-worker
```

### Étape 4 : Tester le Système
```bash
# Test complet
php artisan mail:test mlamine.gueye1@univ-thies.sn

# Vérifier la queue
php artisan queue:monitor

# Voir les logs
tail -f storage/logs/laravel.log
```

## 🔐 Sécurité : Mot de Passe Gmail

⚠️ **IMPORTANT** : N'utilisez JAMAIS votre mot de passe Gmail normal !

### Créer un Mot de Passe d'Application
1. Allez sur https://myaccount.google.com/apppasswords
2. Sélectionnez "Mail" + "Autre (nom personnalisé)"
3. Entrez "MIO Ressources Production"
4. Copiez le mot de passe de 16 caractères généré
5. Utilisez-le dans `MAIL_PASSWORD` du `.env`

## 📊 Monitoring & Debug

### Vérifier que le Worker Tourne
```bash
# Avec supervisor
sudo supervisorctl status mio-queue-worker

# Avec systemd
sudo systemctl status mio-queue-worker

# Avec screen
screen -ls
```

### Voir les Jobs en Queue
```bash
php artisan queue:monitor
```

### Forcer le Traitement Immédiat
```bash
# Traiter tous les jobs maintenant
php artisan queue:work --once
```

### Logs Importants
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Queue Worker
tail -f storage/logs/queue-worker.log

# Logs Nginx/Apache
tail -f /var/log/nginx/error.log
```

## 🎯 Checklist de Déploiement

- [ ] `.env` configuré avec les bons credentials mail
- [ ] `QUEUE_CONNECTION=database` (pas 'sync')
- [ ] Table `jobs` créée (`php artisan migrate`)
- [ ] Queue worker lancé (Supervisor/Systemd/Screen)
- [ ] Test mail effectué (`php artisan mail:test`)
- [ ] Logs vérifiés (pas d'erreurs)
- [ ] Login admin testé (doit recevoir email d'alerte)

## 🚨 Dépannage

### Problème : Pas d'email reçu
```bash
# 1. Vérifier la config
php artisan config:clear
php artisan mail:test votre-email@gmail.com

# 2. Vérifier les logs
tail -100 storage/logs/laravel.log | grep -i error

# 3. Vérifier la queue
php artisan queue:monitor

# 4. Vérifier le worker
sudo supervisorctl status mio-queue-worker
```

### Problème : Erreur "Authentication failed"
- ✅ Utilisez un **mot de passe d'application**, pas votre mot de passe Gmail
- ✅ Activez "Accès moins sécurisé" (si demandé par Google)
- ✅ Vérifiez que le compte Gmail est actif

### Problème : Jobs bloqués en queue
```bash
# Voir les jobs échoués
php artisan queue:failed

# Réessayer
php artisan queue:retry all

# Vider la queue
php artisan queue:flush
```

## 📝 Notifications Critiques pour la Sécurité

1. **AdminLoginAlert** - Connexion admin détectée
2. **PasswordChangedNotification** - Mot de passe modifié
3. **PrivateLessonReminderNotification** - Rappel cours
4. **PurchaseInvoiceNotification** - Facture achat

Toutes ces notifications sont maintenant en queue pour ne pas bloquer l'application.

## 🎓 Tests à Effectuer Après Déploiement

1. ✅ Login admin → Email d'alerte reçu
2. ✅ Changement mot de passe → Email confirmation reçu
3. ✅ Achat ressource → Facture reçue
4. ✅ Création cours → Notification prof reçue
5. ✅ Réservation cours → Notification étudiant reçue

---

**Date de dernière mise à jour** : 29 janvier 2026
**Auteur** : GitHub Copilot (Claude Sonnet 4.5)
