# MIO Ressources - Instructions pour Agents IA

## Aperçu du Projet
**MIO Ressources** est une plateforme pédagogique Laravel 12 avec plusieurs rôles d'utilisateurs (admin, professeur, étudiant) permettant la distribution de contenu, les paiements et les réunions en direct. Les fonctionnalités clés incluent la vente de cours/publications via PayTech, les discussions forum, les tableaux de bord enseignants et les sessions de révision instantanées.

## Architecture & Modèles de Données

### Relations d'Entités Principales
- **Users** : Polymorphe - `role` (admin|professeur|étudiant), `user_type` (student|teacher), `student_level` (L1-L3), `wallet_balance`
- **Ressources** : Matériaux de cours (PDF/URL) liés aux matières ; les articles premium nécessitent un achat via la table `Purchases`
- **Publications** : Livres/mémoires rédigés par les enseignants avec images de couverture ; modèle de tarification premium
- **PrivateLessons** : Cours définis par les enseignants avec `start_date`, `prix`, `places_max` ; les étudiants s'inscrivent via `PrivateLessonEnrollments`
- **Meetings** : Sessions vidéo planifiées (Jitsi) ; relation plusieurs-à-plusieurs avec les étudiants via `meeting_enrollments`
- **Purchase** : Polymorphe (`item_type` : ressource|publication) ; suit l'état du paiement et `guest_email`
- **DownloadHistory** : Enregistre les téléchargements des utilisateurs pour l'analyse

**Hiérarchie des sujets** : Semestre → Matière → Ressource/Publication

## Flux des Cours Particuliers

### Architecture du Système
**Cours Particuliers** (`PrivateLesson` + `PrivateLessonEnrollment`) est un système complet où :
1. **Le professeur crée un cours** avec date/heure, prix, matière, durée et nombre de places
2. **Les étudiants voient les cours disponibles** dans la marketplace (`/cours-particuliers`)
3. **Un étudiant réserve et paie** → création d'une `PrivateLessonEnrollment` avec `payment_status='pending'`
4. **Après paiement PayTech** → `payment_status='paid'` et le cours apparaît dans l'espace étudiant
5. **À l'heure du cours** → l'étudiant peut rejoindre la salle Jitsi

### Routes et Contrôleurs
- **Marketplace étudiants** : `GET /cours-particuliers` → `PrivateLessonController::browse()`
- **Page du cours** : `GET /cours-particuliers/{id}` → `PrivateLessonController::show()`
- **Réservation** : `POST /cours-particuliers/{id}/reserver` → `PrivateLessonController::book()`
- **Rejoindre le cours** : `GET /cours-particuliers/room/{enrollmentId}` → `PrivateLessonController::joinRoom()`
- **Dashboard professeur** : `GET /enseignant/cours-particuliers` → `PrivateLessonController::teacherIndex()`
- **Créer un cours** : `GET/POST /enseignant/cours-particuliers/creer` → `PrivateLessonController::create()/store()`

### Affichage dans l'Espace Étudiant
L'onglet "Cours" de l'espace étudiant (`resources/views/user/dashboard.blade.php`) affiche :
- **Mes Cours Particuliers** : Tous les cours où `student_id = Auth::id()` ET `payment_status='paid'`
- **Bouton Rejoindre** : Actif uniquement si la date du cours est passée ou cours en cours
- **Statuts** : scheduled, active, completed, cancelled

### Modèles Clés
- [PrivateLesson](app/Models/PrivateLesson.php) : Cours créés par les profs avec `teacher_id`, `matiere_id`, `start_date`, `prix`, `places_max`
- [PrivateLessonEnrollment](app/Models/PrivateLessonEnrollment.php) : Inscriptions d'étudiants avec `scheduled_at`, `payment_status`, `session_status`, `jitsi_room_name`

### Flux de Paiement pour Cours Particuliers
1. Professeur crée un cours avec type `payant` ou `tutoriel` (gratuit)
2. Étudiant clique "Réserver" → `POST /cours-particuliers/{id}/reserver`
3. Création d'une `PrivateLessonEnrollment` avec `payment_status='pending'`
4. Si tutoriel → `payment_status='paid'` automatiquement
5. Si payant → redirection vers PayTech pour paiement
6. Après paiement → Webhook update `payment_status='paid'`



### Lancer l'Application
- **Développement** : `npm run dev` (frontend Vite) associé à `php artisan serve` + écoute de la file d'attente
- **Installation Composer** : `composer run setup` gère l'initialisation complète du projet incluant les migrations
- **Tests** : `phpunit` pour les tests unitaires/fonctionnels dans `tests/Feature` et `tests/Unit`
- **Build** : `npm run build` pour les assets de production ; `php artisan optimize:clear` pour la gestion du cache

### Migrations de Base de Données
Les migrations se trouvent dans `database/migrations/` avec des préfixes d'horodatage. Tables clés :
- `users` : Auth + rôles + portefeuille enseignant
- `private_lessons` + `private_lesson_enrollments` : Système de réservation de cours
- `purchases` : Suivi des paiements (polymorphe avec `item_type`)
- `meetings` + `meeting_enrollments` : Gestion des sessions vidéo

## Intégration des Paiements (PayTech)

### Flux
1. **Initiation** : `PaymentController::initiatePayment()` génère une demande PayTech
2. **Champ personnalisé** : Encode `user_id`, `guest_email`, `item_id`, `item_type` en JSON pour le rappel webhook
3. **Webhook IPN** : `POST /api/paytech/callback` valide le paiement via un secret dans la chaîne de requête (`PAYTECH_IPN_SECRET`)
4. **Post-paiement** : `handleIPN()` crée un enregistrement `Purchase`, met à jour `wallet_balance` pour les vendeurs, enregistre `FinancialTransaction`

**Variables d'environnement requises** : `PAYTECH_API_KEY`, `PAYTECH_API_SECRET`, `PAYTECH_ENV` (test|production), `PAYTECH_IPN_SECRET`

## Contrôle d'Accès Basé sur les Rôles

- **Middleware** : `App\Http\Middleware\EnsureRole` vérifie le champ `user->role` (comparaison stricte)
- **Utilisation** : `->middleware('role:professeur')` sur les routes (ex: routes de l'espace enseignant)
- **Filament** : Panneau d'administration restreint à `role='admin'` via `User::canAccessPanel()`
- **Vérification** : Vérifiez toujours la propriété (ex: `$publication->user_id !== Auth::id()`) avant les opérations de suppression/mise à jour

## Patterns Spécifiques au Projet

### Stockage de Fichiers
- PDFs Ressources/Publications : `storage('publications', 'public')`
- Avatars utilisateurs : `storage('avatars', 'public')`
- Factures (dompdf) : Générées à la volée, non stockées
- Paiement invité basé sur la session : `session(['guest_email' => $email])`

### Email & Notifications
- **Listeners** : `App\Listeners\SendAdminLoginAlert`, `SendPasswordChangedEmail`
- **Configuration mail** : Mailer par défaut dans `config/mail.php` (par défaut 'log' pour dev)
- **Génération PDF** : `Barryvdh\DomPDF\Facade\Pdf` pour les factures dans `InvoiceController`

### Resources Filament Admin
Situées dans `app/Filament/Resources/`. Chaque ressource (ex: `RessourceResource`) inclut :
- Définitions Form/Table pour les opérations CRUD
- Pages personnalisées (ex: `PrivateLessonResource/Pages/CreatePrivateLesson.php`)
- Politiques appliquées via méthodes `canCreate()`, `canEdit()`, `canDelete()`

### Documentation API
Annotations Swagger dans les contrôleurs ; docs générées via `darkaonline/l5-swagger` et servies sur `/api/documentation`

## Patterns de Tâches Courantes

### Ajouter un Nouveau Type d'Article Premium
1. Créer un modèle avec champ `price` et relation `User`
2. Mettre à jour `PaymentController::initiatePayment()` pour gérer le nouvel `item_type`
3. Étendre le polymorphisme `Purchase` : ajouter une vérification de type dans `handleIPN()`
4. Créer une ressource Filament dans `app/Filament/Resources/`

### Créer des Fonctionnalités Réservées aux Enseignants
1. Ajouter une route avec `->middleware('role:professeur')` dans `routes/web.php`
2. Vérifier `Auth::user()->user_type === 'teacher'` dans le contrôleur pour la sécurité
3. Vérifier que `Auth::user()->id` correspond au propriétaire de la ressource avant les mutations
4. Mettre à jour la vue du tableau de bord enseignant à `resources/views/teacher/dashboard.blade.php`

### Modifier les Champs Utilisateur
Mettez à jour `protected $fillable` dans le modèle `User` et la définition du formulaire Filament `UserResource` correspondant

## Checklist de Tests
- Tests unitaires : Relations de modèles, logique de validation (`tests/Unit/`)
- Tests fonctionnels : Flux d'authentification, flux de paiement (`tests/Feature/`)
- Base de données SQLite en mémoire pour les tests (configurée dans `phpunit.xml`)
- Toujours ensemencer les données de test : Utilisez les factories dans `database/factories/`

## Notes de Sécurité
- Le webhook IPN (`/api/paytech/callback`) est public mais valide via le paramètre de chaîne de requête `PAYTECH_IPN_SECRET`
- Le paiement invité stocke l'email en session ; validez avant de créer des achats
- Le middleware des rôles utilise la **comparaison stricte** pour la sécurité
- Tous les téléchargements de fichiers doivent valider le type MIME (ex: `mimes:pdf` pour les documents)

## Références des Fichiers Clés
- [app/Models/User.php](app/Models/User.php) - Modèle utilisateur avec rôles
- [app/Http/Controllers/PaymentController.php](app/Http/Controllers/PaymentController.php) - Flux de paiement
- [app/Http/Middleware/EnsureRole.php](app/Http/Middleware/EnsureRole.php) - Middleware d'autorisation
- [routes/web.php](routes/web.php) - Définitions des routes avec groupes middleware
- [app/Filament/Resources/](app/Filament/Resources/) - Définitions CRUD du panneau d'administration
