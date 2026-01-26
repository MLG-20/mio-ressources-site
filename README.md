# MIO Ressources
 
 Plateforme web (Laravel) pour consulter et acheter des ressources pédagogiques (cours/TD) et des publications (livres/mémoires), avec un espace étudiant, un espace enseignant, un forum, et un back-office d’administration (Filament).
 
 ## Sommaire
 
 - **Présentation**
 - **Fonctionnalités**
 - **Stack technique**
 - **Installation (étape par étape)**
 - **Configuration (.env)**
 - **Lancer le projet (dev / prod)**
 - **Structure du projet**
 - **Back-office (Filament)**
 - **Paiement (PayTech)**
 - **Stockage des fichiers & PDF**
 - **Sécurité (checklist)**
 - **Tests & qualité**
 - **Troubleshooting**
 
 ## Présentation
 
 **MIO Ressources** permet :
 
 - La navigation par **semestres** et **matières**.
 - La consultation d’une **bibliothèque** de publications.
 - L’achat de contenu premium (ressources / publications) via **PayTech**.
 - La génération et l’envoi de **factures PDF**.
 - Un **forum** réservé aux utilisateurs connectés.
 - Un espace **étudiant** et un espace **enseignant**.
 - Un back-office **Filament** réservé à l’admin.
 
 ## Fonctionnalités
 
 - **Contenu**
   - Gestion des **semestres**, **matières**, **ressources** (cours/TD) et **publications** (livres/mémoires).
   - Support de documents (PDF) et liens externes (ex: vidéos via URL).
 
 - **Paiement**
   - Démarrage d’un paiement via PayTech.
   - Webhook IPN (`/paiement/ipn`) pour valider la commande côté serveur.
   - Gestion des achats (`purchases`) et historique de téléchargement.
 
 - **Facturation**
   - Génération de facture PDF (`barryvdh/laravel-dompdf`).
   - Téléchargement de facture pour l’admin / l’acheteur / le vendeur.
 
 - **Forum**
   - Catégories, sujets, messages.
   - Modération via Filament.
 
 - **Comptes & rôles**
   - Auth Breeze (login/register/reset password).
   - Rôle `admin` pour Filament.
   - Types `student` / `teacher` + `student_level` (L1/L2/L3).
 
 - **Espace enseignant**
   - Publication de documents (PDF) + image de couverture.
   - Suivi revenus (wallet) et ventes.
 
 ## Stack technique
 
 - **Backend**: Laravel 12, PHP >= 8.2
 - **Admin**: Filament v3
 - **Auth**: Laravel Breeze
 - **Front**: Vite + TailwindCSS + AlpineJS
 - **PDF**: barryvdh/laravel-dompdf
 - **DB**: SQLite par défaut (configurable MySQL/Postgres)
 
 ## Installation (étape par étape)
 
 ### 1) Prérequis
 
 - PHP **8.2+**
 - Composer
 - Node.js + npm
 - Une base de données (SQLite ok pour dev)
 
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
 
 Exemples :
 
 - `UserResource`
 - `RessourceResource`
 - `PublicationResource`
 - `PurchaseResource`
 - `ForumCategoryResource`, `ForumSujetResource`
 
 Accès admin : `User::canAccessPanel()` autorise uniquement `role === 'admin'`.
 
 ## Paiement (PayTech)
 
 ### Flux simplifié
 
 1. L’utilisateur clique “Payer” → `GET /payer/{id}/{type}`
 2. L’app appelle l’API PayTech et redirige vers l’URL de paiement.
 3. PayTech notifie l’app via IPN : `POST /paiement/ipn`
 4. L’app crée un `Purchase`, génère la facture PDF, et envoie l’email.
 
 ### Variables d’environnement à prévoir
 
 Dans `.env` (noms utilisés dans le code) :
 
 - `PAYTECH_API_KEY`
 - `PAYTECH_API_SECRET`
 - `PAYTECH_ENV` (ex: `test` / `prod`)
 - `APP_URL` (doit être l’URL publique en prod)
 
 Important : l’endpoint IPN est exempté CSRF (normal pour un webhook). En production, il est recommandé d’ajouter une **vérification de signature** (HMAC / token) fournie par PayTech.
 
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
 
 - **Webhooks PayTech** : vérifier la signature / secret (anti-fraude)
 - **Téléchargements** : accès réservé aux acheteurs / admin / propriétaire
 - **Rate limiting** : ajouter `throttle` sur login, forum, et IPN
 - **TLS** : ne pas désactiver la vérification SSL
 
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
