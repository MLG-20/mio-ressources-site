# MIO Ressources
 
 Plateforme web (Laravel) **installable en PWA** pour consulter et acheter des ressources pédagogiques (cours/TD) et des publications (livres/mémoires), destinée aux étudiants de l’Université Iba Der Thiam de Thiès. Elle propose un espace étudiant, un espace enseignant, un forum, des **cours particuliers**, des **réunions/visioconférences**, des **groupes de travail**, un **abonnement étudiant**, la connexion **Google (OAuth)**, et un back-office d’administration complet (Filament).
 
 ## Sommaire
 
 - **Présentation**
 - **Fonctionnalités**
 - **Stack technique**
 - **Installation (étape par étape)**
 - **Configuration (.env)**
 - **Lancer le projet (dev / prod)**
 - **Structure du projet**
 - **Back-office (Filament)**
 - **PWA (application installable)**
 - **Paiement (PayTech) & Abonnement étudiant**
 - **Connexion Google (OAuth)**
 - **Stockage des fichiers & PDF**
 - **Sécurité (checklist)**
 - **Sauvegardes & monitoring**
 - **CI/CD & déploiement**
 - **Tests & qualité**
 - **Troubleshooting**
 
 ## Présentation
 
 **MIO Ressources** permet :
 
 - L’**installation comme application** (PWA) sur mobile et bureau, avec écran de démarrage (splash) et préchargement.
 - La navigation par **semestres** et **matières**.
 - La consultation d’une **bibliothèque** de publications.
 - L’achat de contenu premium (ressources / publications) via **PayTech**.
 - L’**abonnement étudiant** (mensuel / trimestriel / annuel) débloquant l’accès complet.
 - La connexion / inscription via **Google (OAuth)** en plus de l’email/mot de passe.
 - Les **cours particuliers**, **réunions/visioconférences** et **groupes de travail**.
 - La génération et l’envoi de **factures PDF**.
 - Un **forum** réservé aux utilisateurs connectés.
 - Un espace **étudiant** et un espace **enseignant**.
 - Un back-office **Filament** réservé à l’admin.
 
 ## Fonctionnalités
 
 - **Contenu**
   - Gestion des **semestres**, **matières**, **ressources** (cours/TD) et **publications** (livres/mémoires).
   - Support de documents (PDF) et liens externes (ex: vidéos via URL).
   - **Slider d’accueil** géré depuis l’admin, supportant **image ou vidéo** (mp4/webm muet en boucle, image en poster/fallback).
   - **Bannière d’annonces** et **avis / notations** (ratings) sur les ressources.

 - **PWA (Progressive Web App)**
   - Application **installable** (manifest + service worker `public/js/service-worker.js`).
   - **Splash screen** sombre et **préchargement** (preloader) sur l’accueil.
   - Mode `standalone`, thème personnalisé (`theme_color`, icônes).
 
 - **Paiement**
   - Démarrage d’un paiement via PayTech (achats **et** abonnement étudiant).
   - Webhooks IPN : `POST /api/paytech/callback` (achats) et `POST /api/paytech/subscription-callback` (abonnement).
   - Gestion des achats (`purchases`), transactions financières et historique de téléchargement.

 - **Abonnement étudiant**
   - Formules **mensuelle / trimestrielle / annuelle**.
   - **Paywall** dédié + middleware `student.subscription` protégeant le contenu réservé.
   - **Interrupteur global** « Exiger l’abonnement étudiant » activable depuis l’admin.
 
 - **Facturation**
   - Génération de facture PDF (`barryvdh/laravel-dompdf`).
   - Téléchargement de facture pour l’admin / l’acheteur / le vendeur.
 
 - **Forum & collaboration**
   - Forum : catégories, sujets, messages, modération via Filament.
   - **Cours particuliers** (inscription + suivi), **réunions/visioconférences** (planifiées), **groupes de travail**.
 
 - **Comptes & rôles**
   - Auth Breeze (login/register/reset password) **+ connexion Google (OAuth via Socialite)**.
   - Rôle `admin` pour Filament + système de **permissions**.
   - Types `student` / `teacher` + `student_level` (L1/L2/L3).
   - Suivi des **utilisateurs en ligne** (`last_seen_at`) et des **visites**.
 
 - **Espace enseignant**
   - Publication de documents (PDF) + image de couverture.
   - Suivi revenus (wallet) et ventes (l’enseignant perçoit 70 % du prix).
 
 ## Stack technique

 - **Backend**: Laravel 12, PHP >= 8.2
 - **Admin**: Filament v3
 - **Auth**: Laravel Breeze + **Laravel Socialite** (Google OAuth)
 - **Front**: Vite + TailwindCSS + AlpineJS, **PWA** (manifest + service worker)
 - **PDF**: barryvdh/laravel-dompdf
 - **DB**: MySQL (recommandé en prod)
 - **Cache & Queue**: Redis (via predis/predis)
 - **Sauvegardes**: spatie/laravel-backup
 - **Monitoring**: Sentry (optionnel, voir `config/monitoring/SENTRY.md`)
 - **CDN / Réseau**: Cloudflare (HTTP/2) devant le VPS
 - **CI/CD**: GitHub Actions (tests + déploiement auto sur VPS)
 
 ## Installation (étape par étape)
 
 ### 1) Prérequis

 - PHP **8.2+**
 - Composer
 - Node.js + npm
 - MySQL (ou autre base de données)
 - **Redis** (obligatoire en production pour les queues et le cache)
 
 ### 2) Installer les dépendances
 
 1. Installer les dépendances PHP :
 
    - `composer install`
 
 2. Installer les dépendances front :
 
    - `npm install`
 
 ### 3) Configurer l’environnement
 
 1. Copier le fichier d’environnement :
 
    - Copier `.env.example` vers `.env`
 
 2. Générer la clé Laravel :
 
    - `php artisan key:generate`
 
 3. Vérifier `APP_URL` (important pour les liens de paiement / factures) :
 
    - Exemple local : `APP_URL=http://localhost:8000`
 
 ### 4) Base de données et migrations
 
 1. Lancer les migrations :
 
    - `php artisan migrate`
 
 2. (Optionnel) Ajouter des données initiales :
 
    - Si tu as des seeders: `php artisan db:seed`
 
 ### 5) Stockage (fichiers uploadés)
 
 Les documents PDF et images sont stockés dans `storage/app/public`.
 
 1. Créer le lien symbolique public :
 
    - `php artisan storage:link`
 
 ## Lancer le projet
 
 ### Mode développement (recommandé)
 
 Tu as un script pratique dans `composer.json` :
 
 - `composer run dev`
 
 Cela lance :
 
 - le serveur Laravel
 - la queue (`queue:listen`)
 - les logs (Pail)
 - Vite
 
 ### Mode classique

 - Backend : `php artisan serve`
 - Front : `npm run dev`

 ## Queue workers (production)

 Les emails (notifications, factures) sont envoyés en arrière-plan via Redis.
 En production, tu dois démarrer au minimum **1 worker** :

 ```bash
 # Démarrer un worker (à lancer en production)
 php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600

 # Ou avec Supervisor (recommandé), ajouter dans /etc/supervisor/conf.d/mio-worker.conf :
 # [program:mio-worker]
 # command=php /chemin/vers/projet/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
 # autostart=true
 # autorestart=true
 # numprocs=2
 # stderr_logfile=/var/log/mio-worker.log
 ```

 Le scheduler (rappels de cours) doit aussi tourner en cron :

 ```bash
 # Ajouter dans le crontab du serveur (crontab -e)
 * * * * * cd /chemin/vers/projet && php artisan schedule:run >> /dev/null 2>&1
 ```
 
 ## Structure du projet (repères)
 
 - **Routes**
   - `routes/web.php` : routes publiques + routes `auth` + forum + espace enseignant
   - `routes/auth.php` : routes Breeze (login/register/reset)
 
 - **Contrôleurs principaux**
   - `app/Http/Controllers/HomeController.php` : pages publiques, bibliothèque, téléchargement
   - `app/Http/Controllers/PaymentController.php` : PayTech (initiation + IPN)
   - `app/Http/Controllers/UserSpaceController.php` : espace étudiant
   - `app/Http/Controllers/TeacherSpaceController.php` : espace enseignant
   - `app/Http/Controllers/ForumController.php` : forum
   - `app/Http/Controllers/InvoiceController.php` : factures PDF
 
 - **Modèles (exemples)**
   - `User` : rôles (`role`) + type (`user_type`) + niveau (`student_level`) + wallet
   - `Ressource` : contenus par matière/semestre, premium, prix
   - `Publication` : livres/mémoires + cover + premium/prix
   - `Purchase` : achats (ressource/publication) + `payment_id` + `guest_email`
 
 - **Migrations (domaines couverts)**
   - Utilisateurs / rôles / niveaux
   - Semestres, matières, ressources
   - Publications
   - Achats (`purchases`) + transactions financières
   - Forum (catégories/sujets/messages)
   - Historique de téléchargement
   - Avis / ratings
 
 ## Back-office (Filament)
 
 Les ressources Filament sont dans `app/Filament/Resources`.
 
 Ressources disponibles (extrait) :

 - **Contenu** : `SemestreResource`, `MatiereResource`, `RessourceResource`, `PublicationResource`, `SliderResource`, `PageResource`, `LegalPageResource`, `AnnouncementResource`
 - **Ventes** : `PurchaseResource`, `FinancialTransactionResource`, `DownloadHistoryResource`, `ResourceRatingResource`
 - **Forum** : `ForumCategoryResource`, `ForumSujetResource`, `ForumMessageResource`
 - **Collaboration** : `PrivateLessonResource`, `PrivateLessonEnrollmentResource`, `MeetingResource`, `WorkGroupResource`
 - **Utilisateurs & réglages** : `UserResource`, `VisitResource`, `ContactMessageResource`, `SettingResource`

 Accès admin : `User::canAccessPanel()` autorise uniquement `role === 'admin'` ; les ressources sont en plus filtrées par un système de **permissions** (ex. `hasPermission('sliders')`).
 
 ## Paiement (PayTech)
 
 ### Flux simplifié
 
 1. L’utilisateur clique “Payer” → `GET /payer/{id}/{type}` (ou démarre un **abonnement étudiant**).
 2. L’app appelle l’API PayTech (`env` = `test` ou `prod`) et redirige vers l’URL de paiement.
 3. PayTech notifie l’app via IPN :
    - **Achats** : `POST /api/paytech/callback`
    - **Abonnement** : `POST /api/paytech/subscription-callback`
 4. L’app crée le `Purchase` (ou active l’abonnement), génère la facture PDF, et envoie l’email.

 ### Variables d’environnement à prévoir

 Dans `.env` (noms utilisés dans le code) :

 - `PAYTECH_API_KEY`
 - `PAYTECH_API_SECRET`
 - `PAYTECH_IPN_SECRET` (secret applicatif ajouté à l’URL IPN — **obligatoire**)
 - `PAYTECH_ENV` (`test` en sandbox / `prod` en production)
 - `APP_URL` (doit être l’URL publique en prod)

 Les endpoints IPN sont exemptés CSRF (normal pour un webhook). La vérification est **double** : `PAYTECH_IPN_SECRET` présent dans l’URL + comparaison du SHA256 des clés API PayTech via `hash_equals`.

 > **Passage en production** : il suffit de renseigner les clés de prod, mettre `PAYTECH_ENV=prod`, puis `php artisan config:cache`. Aucun changement de code n’est nécessaire (la vérification SHA256 suit automatiquement les nouvelles clés).

 ## Connexion Google (OAuth)

 Connexion / inscription via Google (Laravel Socialite). Variables `.env` :

 - `GOOGLE_CLIENT_ID`
 - `GOOGLE_CLIENT_SECRET`
 - `GOOGLE_REDIRECT_URI` (par défaut `"${APP_URL}/auth/google/callback"`)

 Pour un **nouveau** compte Google, l’utilisateur choisit son type (étudiant / enseignant) avant la création (`auth/google/choose-type`).
 
 ## Stockage des fichiers & PDF
 
 - Uploads : `storage/app/public/...`
 - URL publiques : via `public/storage` après `php artisan storage:link`
 - Factures : générées avec DomPDF via une vue (`resources/views/invoices/template.blade.php`)
 
 ## Sécurité (checklist)
 
 ### Réglages production
 
 - `APP_DEBUG=false`
 - `APP_ENV=production`
 - `LOG_LEVEL=warning` (ou `error`)
 - `APP_KEY` renseignée
 
 ### Points à vérifier

 - **Webhooks PayTech** : vérification double (secret URL + SHA256 clés API) — déjà en place
 - **Rate limiting** : throttle sur login, register, forum, paiements — déjà en place
 - **Téléchargements** : accès réservé aux acheteurs / admin / propriétaire
 - **TLS** : ne pas désactiver la vérification SSL
 - **Redis** : démarrer les workers queue en production (voir section dédiée)
 - **APP_DEBUG=false** en production obligatoire
 
 ## PWA (application installable)

 L’application est installable sur mobile et bureau (Add to Home Screen).

 - **Manifest** : `public/manifest.json` (nom, icônes, `theme_color`, mode `standalone`, orientation).
 - **Service worker** : `public/js/service-worker.js`.
 - **Splash screen** sombre + **preloader** affiché au chargement de l’accueil.
 - Vérifier que le manifest et le service worker sont bien servis en HTTPS en production.

 ## Sauvegardes & monitoring

 - **Sauvegardes** : `spatie/laravel-backup` (`config/backup.php`). Lancement : `php artisan backup:run`.
   - ⚠️ La commande doit tourner sous l’utilisateur **`www-data`** (et non `root`) pour éviter les erreurs de permissions sur les dossiers de stockage.
 - **Monitoring** : intégration **Sentry** optionnelle, documentée dans `config/monitoring/SENTRY.md`.

 ## CI/CD & déploiement

 Le pipeline **GitHub Actions** (`.github/workflows/ci-cd.yml`) se déclenche à chaque push sur `main` :

 1. **Tests** : installation des dépendances, migrations sur une base MySQL de test, puis `phpunit`.
 2. **Déploiement** (si les tests passent) : connexion SSH au VPS et exécution de `deploy.sh`.

 `deploy.sh` effectue sur le serveur : `git pull origin main`, `php artisan migrate --force`, puis reconstruit les caches (`config:cache`, `route:cache`, `view:cache`). Les migrations s’appliquent donc **automatiquement** en production.

 ## Tests & qualité
 
 - Lancer les tests : `composer test`
 - Format code (si Pint) : `./vendor/bin/pint`
 
 ## Troubleshooting
 
 - **Erreur 500 après clone**
   - Vérifie `.env`, puis `php artisan key:generate`, puis `php artisan migrate`
 
 - **Les fichiers ne se téléchargent pas / 404 sur /storage**
   - Lance `php artisan storage:link`
   - Vérifie que les fichiers existent bien dans `storage/app/public`
 
 - **Paiement PayTech ne redirige pas**
   - Vérifie `PAYTECH_API_KEY` / `PAYTECH_API_SECRET`
   - Vérifie `APP_URL` (doit être accessible publiquement en prod)
 
 - **Emails non envoyés**
   - Configure `MAIL_*` dans `.env`
   - Vérifie le driver queue si tu en utilises un

 - **L’app ne s’installe pas (PWA)**
   - Le site doit être servi en **HTTPS**
   - Vérifie que `public/manifest.json` et `public/js/service-worker.js` sont bien accessibles

 - **L’abonnement n’est pas activé après paiement**
   - Vérifie que `PAYTECH_IPN_SECRET` est configuré (les IPN sont sinon rejetés avec `invalid_secret`)
   - Vérifie qu’aucune règle CDN/WAF ne bloque les `POST` vers `/api/paytech/subscription-callback`
