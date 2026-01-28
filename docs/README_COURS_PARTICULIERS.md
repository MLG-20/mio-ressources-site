# 🎓 Système de Cours Particuliers Payants - Documentation

## 📋 Vue d'ensemble

Ce système permet aux professeurs de créer et vendre des cours particuliers en ligne avec paiement sécurisé via Paytech et visioconférence via Jitsi.

---

## 🚀 Fonctionnalités Principales

### Pour les Professeurs 👨‍🏫

1. **Créer des cours particuliers**
   - Titre, description
   - Prix personnalisé (FCFA)
   - Durée (30min, 60min, 90min, 120min)
   - Disponibilités par jour/horaires
   - Nombre de places maximum
   - Matière associée (optionnel)

2. **Gérer les cours**
   - Liste de tous les cours créés
   - Statistiques : cours actifs, revenus totaux, étudiants inscrits
   - Modifier un cours
   - Activer/Désactiver un cours
   - Supprimer un cours (si aucune inscription payée)

3. **Accéder aux sessions**
   - Rejoindre automatiquement la salle Jitsi
   - Démarrage de session avec tracking du temps
   - Historique des sessions

### Pour les Étudiants 👨‍🎓

1. **Marketplace de cours**
   - Parcourir tous les cours disponibles
   - Filtrer par :
     - Matière
     - Prix (min/max)
     - Durée
   - Pagination des résultats

2. **Réserver un cours**
   - Consulter les détails (prof, description, disponibilités)
   - Choisir une date/heure
   - Paiement sécurisé via Paytech
   - Confirmation par email

3. **Suivre le cours**
   - Rejoindre la salle Jitsi au moment prévu
   - Interface vidéo complète
   - Chat intégré

---

## 🗂️ Structure de la Base de Données

### Table `private_lessons`
```sql
- id
- titre (string)
- description (text)
- prix (decimal 10,2)
- duree_minutes (int: 30/60/90/120)
- teacher_id (foreign key → users)
- matiere_id (foreign key → matieres, nullable)
- disponibilites (JSON: [{jour: 'Lundi', horaires: '14h-16h'}])
- places_max (int)
- statut (enum: actif/inactif/complet)
- timestamps
```

### Table `private_lesson_enrollments`
```sql
- id
- private_lesson_id (foreign key)
- student_id (foreign key → users)
- scheduled_at (datetime)
- payment_status (enum: pending/paid/cancelled)
- payment_reference (string - ID Paytech)
- amount_paid (decimal 10,2)
- jitsi_room_name (string - auto-généré)
- session_status (enum: scheduled/active/completed/cancelled)
- started_at (datetime nullable)
- completed_at (datetime nullable)
- timestamps
- UNIQUE (private_lesson_id, student_id, scheduled_at)
```

---

## 🛣️ Routes Disponibles

### Routes Étudiants
```
GET  /cours-particuliers                    → Marketplace (liste)
GET  /cours-particuliers/{id}              → Détails d'un cours
POST /cours-particuliers/{id}/reserver     → Réserver + payer
GET  /cours-particuliers/room/{enrollmentId} → Rejoindre la salle Jitsi
GET  /cours-particuliers/payment/success   → Page succès paiement
GET  /cours-particuliers/payment/cancel    → Page annulation
POST /cours-particuliers/payment/callback  → Webhook Paytech (IPN)
```

### Routes Professeurs (middleware: role:professeur)
```
GET    /enseignant/cours-particuliers           → Liste des cours
GET    /enseignant/cours-particuliers/creer     → Formulaire création
POST   /enseignant/cours-particuliers/creer     → Enregistrer cours
GET    /enseignant/cours-particuliers/{id}/modifier → Formulaire édition
PUT    /enseignant/cours-particuliers/{id}      → Mettre à jour
POST   /enseignant/cours-particuliers/{id}/toggle → Activer/Désactiver
DELETE /enseignant/cours-particuliers/{id}      → Supprimer
```

---

## 🎨 Vues Créées

### Professeur
- `resources/views/teacher/private-lessons/index.blade.php` - Liste des cours
- `resources/views/teacher/private-lessons/create.blade.php` - Créer un cours
- `resources/views/teacher/private-lessons/edit.blade.php` - Modifier un cours

### Étudiant
- `resources/views/private-lessons/browse.blade.php` - Marketplace
- `resources/views/private-lessons/show.blade.php` - Détails + réservation
- `resources/views/private-lessons/room.blade.php` - Salle Jitsi
- `resources/views/private-lessons/payment-success.blade.php` - Succès paiement
- `resources/views/private-lessons/payment-cancel.blade.php` - Annulation

### Layouts
- `resources/views/layouts/navbar.blade.php` - Navbar publique
- `resources/views/layouts/footer.blade.php` - Footer

---

## 💳 Intégration Paytech

### Configuration (.env)
```env
PAYTECH_API_KEY=votre_clé_api
PAYTECH_SECRET=votre_secret
PAYTECH_ENV=test  # ou 'prod' en production
```

### Flux de paiement
1. Étudiant clique "Réserver"
2. Système crée une `enrollment` avec `payment_status = 'pending'`
3. Redirection vers Paytech avec :
   - Prix du cours
   - Référence unique
   - URLs de retour (success/cancel)
   - URL IPN (callback)
4. Étudiant paie sur Paytech
5. Paytech envoie IPN au serveur
6. Serveur vérifie signature HMAC
7. Mise à jour : `payment_status = 'paid'`, génération room Jitsi
8. Email de confirmation envoyé
9. Étudiant peut rejoindre la salle

---

## 🎥 Intégration Jitsi

### Configuration
- Domaine : `8x8.vc`
- JWT : `vpaas-magic-cookie-91fa1fc0a0a3479c8d8e825bf68c4be7`
- Noms de salles : `PL-XXXXXXXX` (8 caractères aléatoires)

### Génération automatique
- Lors de la création d'une `enrollment`, un nom unique est généré
- Format : `PrivateLessonEnrollment::boot()` utilise `Str::random(8)`
- Une salle par réservation (privacité garantie)

### Interface
- API Jitsi Meet External intégrée
- Nom d'utilisateur automatique (Auth::user()->name)
- Événements trackés (join, leave)
- Redirection après déconnexion

---

## 📍 Navigation

### Ajouts dans la navbar principale (welcome.blade.php)
- Lien "💼 Cours Particuliers" dans le menu desktop

### Dashboard professeur (teacher/dashboard.blade.php)
- Bouton "💼 Cours Particuliers" (amber-600) dans les onglets

---

## 🔐 Sécurité

### Middleware
- Routes professeurs : `middleware('role:professeur')`
- Routes étudiants : `middleware('auth')` (optionnel pour browse)

### Validations
- Vérification paiement avant accès salle Jitsi
- Vérification ownership (prof = créateur, étudiant = inscrit)
- Signature HMAC pour callback Paytech
- Unique constraint pour empêcher double-réservation

---

## 📊 Statistiques Professeur

Dans `teacher.private-lessons.index` :
- **Cours actifs** : Nombre de cours avec statut `actif`
- **Revenus totaux** : Somme des `amount_paid` de toutes les enrollments payées
- **Étudiants inscrits** : Count des enrollments payées pour tous les cours

---

## 🧪 Tester le Système

### Côté Professeur
1. Se connecter avec un compte professeur
2. Aller dans "Cours Particuliers"
3. Cliquer "Créer un cours"
4. Remplir le formulaire :
   - Titre : "Cours de Mathématiques L1"
   - Description : "Révision algèbre linéaire"
   - Prix : 5000 FCFA
   - Durée : 60 minutes
   - Places : 1
   - Disponibilités : Lundi 14h-16h, Mercredi 18h-20h
5. Soumettre → Cours créé !

### Côté Étudiant
1. Aller sur `/cours-particuliers`
2. Parcourir les cours disponibles
3. Cliquer sur un cours → Voir détails
4. Cliquer "Réserver maintenant"
5. Choisir date/heure (ex: 2024-03-15 14:30)
6. Cliquer "Payer" → Redirection Paytech
7. Payer avec carte test Paytech
8. Retour sur page succès
9. Aller dans son espace étudiant (à implémenter : liste des cours réservés)
10. Rejoindre la salle à l'heure prévue

---

## 🚧 Points d'Amélioration Futurs

### Fonctionnalités à ajouter
- [ ] Onglet "Mes cours réservés" dans le dashboard étudiant
- [ ] Système de notation/avis après cours
- [ ] Notifications email avant début du cours (rappel)
- [ ] Calendrier visuel pour choisir créneaux
- [ ] Historique des sessions terminées
- [ ] Statistiques détaillées professeur (graphiques)
- [ ] Système de remboursement si annulation
- [ ] Chat avant cours (questions pré-session)
- [ ] Upload de documents par le prof (support de cours)
- [ ] Enregistrement automatique des sessions

### Optimisations techniques
- [ ] Cache des requêtes lourdes
- [ ] Queue pour les emails de confirmation
- [ ] Compression des images prof/cover
- [ ] Tests unitaires et d'intégration
- [ ] Logs détaillés des transactions Paytech
- [ ] Système de backup des enrollments
- [ ] Rate limiting sur les réservations

---

## 📞 Support

En cas de problème :
1. Vérifier les logs : `storage/logs/laravel.log`
2. Vérifier la configuration Paytech dans `.env`
3. Tester le callback Paytech avec Postman
4. Vérifier les migrations : `php artisan migrate:status`
5. Clear cache : `php artisan cache:clear && php artisan config:clear`

---

## ✅ Checklist de Déploiement

- [ ] Migrations exécutées : `php artisan migrate`
- [ ] Cache vidé : `php artisan optimize:clear`
- [ ] Environnement Paytech configuré (test → prod)
- [ ] URLs de callback Paytech en HTTPS
- [ ] Jitsi JWT configuré (production)
- [ ] Tests de paiement effectués
- [ ] Tests d'accès salle Jitsi
- [ ] Emails de confirmation fonctionnels
- [ ] Permissions fichiers correctes (storage/)
- [ ] SSL activé (HTTPS obligatoire pour Paytech)

---

**Date de création** : 28 Janvier 2026
**Version** : 1.0
**Développeur** : MIO Team
