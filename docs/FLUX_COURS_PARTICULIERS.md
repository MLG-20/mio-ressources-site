# Flux Complet des Cours Particuliers

## Vue d'ensemble
Le système des cours particuliers permet aux **professeurs de créer des cours** avec une date/heure fixe, et aux **étudiants de réserver** ces cours. Une fois réservés et payés, les cours s'affichent dans l'espace étudiant.

---

## 1. Flux du Professeur : Créer un Cours

### Route
- URL: `/enseignant/cours-particuliers/creer`
- Contrôleur: `PrivateLessonController::create()` (GET) / `store()` (POST)
- Middleware: `role:professeur`

### Données requises
```php
'titre'           => string (max 255)
'description'     => text
'prix'            => numeric (0 si tutoriel)
'duree_minutes'   => integer (30, 60, 90, ou 120)
'matiere_id'      => nullable|exists:matieres,id
'places_max'      => integer (min 1)
'type'            => enum (payant, tutoriel)
'disponibilites'  => array (créneaux horaires)
```

### Processus
```
1. Prof accède à /enseignant/cours-particuliers/creer
2. Remplit le formulaire (titre, desc, prix, durée, matière, places)
3. Valide et crée la PrivateLesson
   - teacher_id = Auth::id()
   - statut = 'actif'
   - Si type='tutoriel' → prix=0
4. Redirection vers le dashboard prof avec succès
```

### Modèle créé
```php
PrivateLesson::create([
    'titre'          => $request->titre,
    'description'    => $request->description,
    'prix'           => $request->type === 'tutoriel' ? 0 : $request->prix,
    'duree_minutes'  => $request->duree_minutes,
    'teacher_id'     => Auth::id(),
    'matiere_id'     => $request->matiere_id,
    'disponibilites' => $request->disponibilites,
    'places_max'     => $request->places_max,
    'type'           => $request->type,
    'statut'         => 'actif',
]);
```

---

## 2. Flux de l'Étudiant : Découvrir et Réserver

### 2.1 Page Marketplace (Tous les Cours)

**Route**: `/cours-particuliers`  
**Contrôleur**: `PrivateLessonController::browse()`  
**Vue**: `private-lessons/browse.blade.php`

**Affichage**:
- Tous les `PrivateLesson` où `statut='actif'`
- Filtres: matière, type (payant/tutoriel), prix, durée
- Pagination: 12 cours par page
- Chaque cours affiche:
  - Titre, description, prix
  - Professeur avec avatar
  - Matière
  - Durée, places restantes
  - Badge "Payant" ou "Tutoriel"

### 2.2 Page Détail du Cours

**Route**: `/cours-particuliers/{id}`  
**Contrôleur**: `PrivateLessonController::show($id)`  
**Vue**: `private-lessons/show.blade.php`

**Affichage**:
- Détails complets du cours
- Profil du professeur
- Places restantes
- Bouton "Réserver"

### 2.3 Réservation du Cours

**Route**: `POST /cours-particuliers/{id}/reserver`  
**Contrôleur**: `PrivateLessonController::book($id)`

**Données requises**:
```php
'scheduled_at' => date (after:now)  // Date/heure de la réservation
```

**Processus**:
```
1. Étudiant clique "Réserver" sur la page du cours
2. Validation de la date et des places disponibles
3. Création d'une PrivateLessonEnrollment:
   - private_lesson_id = $lesson->id
   - student_id = Auth::id()
   - scheduled_at = $request->scheduled_at
   - payment_status = 'pending' (ou 'paid' si tutoriel)
   - amount_paid = $lesson->prix
   - session_status = 'scheduled'
   - jitsi_room_name = AUTO-GÉNÉRÉ (PL-XXXXXXXX)

4. Si tutoriel (prix=0):
   → Redirection vers /cours-particuliers/{id}
   → Message de succès

5. Si payant:
   → Redirection vers PayTech pour paiement
   → Custom field contient enrollment_id
```

### Code de Réservation
```php
$enrollment = PrivateLessonEnrollment::create([
    'private_lesson_id' => $lesson->id,
    'student_id'        => Auth::id(),
    'scheduled_at'      => $request->scheduled_at,
    'payment_status'    => $lesson->type === 'tutoriel' ? 'paid' : 'pending',
    'amount_paid'       => $lesson->prix,
    'session_status'    => 'scheduled',
]);

if ($lesson->type === 'tutoriel') {
    return redirect()->route('private-lessons.show', $lesson->id)
        ->with('success', '✅ Inscrit au tutoriel !');
} else {
    return $this->initiatePayment($enrollment, $lesson);
}
```

---

## 3. Affichage dans l'Espace Étudiant

### Route
`GET /mon-espace` → `UserSpaceController::index()`

### Requête des Données
```php
$mesCoursParticuliers = PrivateLessonEnrollment::with([
    'privateLesson' => function($query) {
        $query->with(['teacher', 'matiere']);
    }
])
->where('student_id', Auth::id())
->where('payment_status', 'paid')  // ← IMPORTANT: Seulement les cours payés
->orderBy('scheduled_at', 'asc')
->get();
```

### Vue
`resources/views/user/dashboard.blade.php` - Onglet "🎓 Cours"

### Affichage de chaque cours réservé
```
📖 [Titre du Cours]
👨‍🏫 [Nom du Professeur]
📅 Date: 25/01/2026 | ⏰ Heure: 14:30 | ⏱️ Durée: 60 min | 📚 Matière

Statut: ✅ Programmé / 🔴 En cours / ✔️ Complété / ❌ Annulé

[Bouton Rejoindre] (actif si l'heure du cours est proche ou passée)
```

### Conditions d'activation du bouton "Rejoindre"
```php
@if($enrollment->session_status === 'active' || 
    ($enrollment->scheduled_at && now() >= $enrollment->scheduled_at->subMinutes(5)))
    <!-- Bouton Rejoindre actif -->
@else
    <!-- Bouton désactivé -->
@endif
```

---

## 4. Paiement PayTech (Cours Payant)

### Initiation du Paiement
```php
Http::asForm()->post('https://paytech.sn/api/payment/request-payment', [
    'item_name'    => $lesson->titre,
    'item_price'   => $lesson->prix,
    'currency'     => 'XOF',
    'ref_command'  => 'PL-' . $enrollment->id,
    'command_name' => "Cours particulier: {$lesson->titre}",
    'env'          => config('services.paytech.env'),
    'ipn_url'      => route('private-lessons.payment.callback'),
    'success_url'  => route('private-lessons.payment.success'),
    'cancel_url'   => route('private-lessons.payment.cancel'),
    'custom_field' => json_encode(['enrollment_id' => $enrollment->id]),
]);
```

### Callback IPN (Webhook)
**Route**: `POST /cours-particuliers/payment/callback`  
**Contrôleur**: `PrivateLessonController::paymentCallback()`

**Processus**:
```
1. PayTech envoie les données du paiement
2. Vérification de la signature
3. Récupération de l'enrollment_id depuis custom_field
4. Update: enrollment->payment_status = 'paid'
5. Email de confirmation
```

### Success Page
**Route**: `GET /cours-particuliers/payment/success`  
**Affichage**: Confirmation du paiement et message de succès

---

## 5. Salle de Cours (Jitsi)

### Rejoindre le Cours
**Route**: `GET /cours-particuliers/room/{enrollmentId}`  
**Contrôleur**: `PrivateLessonController::joinRoom($enrollmentId)`

**Processus**:
```
1. Récupération de l'enrollment
2. Vérification: L'utilisateur est bien l'étudiant inscrit
3. Salle Jitsi: {enrollment->jitsi_room_name}
4. Embed Jitsi avec les paramètres:
   - userInfo.displayName = student name
   - userInfo.email = student email
   - roomName = enrollment->jitsi_room_name
```

**Nom de la salle**: `PL-` + 8 caractères aléatoires  
Généré automatiquement lors de la création de l'enrollment par un Boot du modèle

---

## 6. Structure des Données

### Table: `private_lessons`
```sql
id
teacher_id (FK users)
titre
description
prix (decimal)
duree_minutes
matiere_id (FK matieres)
disponibilites (JSON)
places_max
type (enum: payant, tutoriel)
statut (enum: actif, inactif, complet)
created_at
updated_at
```

### Table: `private_lesson_enrollments`
```sql
id
private_lesson_id (FK private_lessons)
student_id (FK users)
scheduled_at (datetime)
payment_status (enum: pending, paid, cancelled)
payment_reference
amount_paid (decimal)
jitsi_room_name
session_status (enum: scheduled, active, completed, cancelled)
started_at
completed_at
created_at
updated_at

Unique: (private_lesson_id, student_id, scheduled_at)
```

---

## 7. États et Transitions

### `payment_status` (PrivateLessonEnrollment)
- **pending** → En attente de paiement (payant uniquement)
- **paid** → Paiement confirmé ou tutoriel gratuit
- **cancelled** → Annulé par l'étudiant

### `session_status` (PrivateLessonEnrollment)
- **scheduled** → Programmé, pas encore commencé
- **active** → Cours en cours
- **completed** → Cours terminé
- **cancelled** → Annulé

### `statut` (PrivateLesson)
- **actif** → Disponible à la réservation
- **inactif** → Masqué de la marketplace
- **complet** → Toutes les places réservées

---

## 8. Checklist d'Implémentation

- ✅ Modèle PrivateLesson avec relations
- ✅ Modèle PrivateLessonEnrollment avec auto-génération jitsi_room_name
- ✅ Routes pour créer/voir/réserver des cours
- ✅ Vue marketplace des cours particuliers
- ✅ Récupération des cours réservés dans UserSpaceController
- ✅ Affichage des cours dans l'espace étudiant
- ✅ Intégration PayTech pour les cours payants
- ✅ Salle Jitsi pour les étudiants inscrits
- ✅ Email de confirmation après paiement

---

## 9. Tests Recommandés

### Test unitaire
- Vérifier la création d'une PrivateLesson
- Vérifier la création d'une PrivateLessonEnrollment
- Vérifier la génération du jitsi_room_name

### Test fonctionnel
- Professeur crée un cours → vérifie l'affichage en marketplace
- Étudiant réserve un tutoriel → doit apparaître immédiatement dans son espace
- Étudiant réserve un cours payant → doit être en "pending" avant paiement
- Après paiement PayTech → doit être en "paid" et visible dans l'espace
- Bouton Rejoindre → doit être actif seulement à l'heure du cours

---

## 10. Exemples d'Utilisation

### Créer un cours (Prof)
```bash
POST /enseignant/cours-particuliers/creer
{
    "titre": "Révision Mathématiques L3",
    "description": "Préparation à l'examen",
    "prix": "5000",
    "duree_minutes": "60",
    "matiere_id": "2",
    "places_max": "5",
    "type": "payant",
    "disponibilites": ["lundi 14:00", "mercredi 16:00"]
}
```

### Réserver un cours (Étudiant)
```bash
POST /cours-particuliers/1/reserver
{
    "scheduled_at": "2026-01-29 14:00:00"
}
```

### Affichage dans l'espace étudiant
Les cours avec `payment_status='paid'` s'affichent automatiquement dans l'onglet "Cours" avec un bouton "Rejoindre" activable à l'heure prévue.
