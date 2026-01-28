# 🧪 Guide de Test - Cours Particuliers

## Étapes de test rapides

### 1. Vérifier les migrations
```bash
php artisan migrate:status
# Vérifier que les tables private_lessons et private_lesson_enrollments existent
```

### 2. Tester en tant que Professeur

#### A. Créer un compte professeur (si nécessaire)
```sql
-- Dans la base de données
UPDATE users SET role = 'professeur' WHERE id = X;
```

#### B. Accéder à l'interface
1. Se connecter avec le compte professeur
2. Aller sur : `http://localhost/espace-enseignant`
3. Cliquer sur "💼 Cours Particuliers"
4. URL : `http://localhost/enseignant/cours-particuliers`

#### C. Créer un cours test
1. Cliquer "➕ Créer un cours"
2. Remplir :
   - **Titre** : Test Mathématiques L1
   - **Description** : Cours de révision algèbre
   - **Prix** : 5000
   - **Durée** : 60 minutes
   - **Places max** : 1
   - **Disponibilités** :
     - Lundi : 14h-16h
     - Mercredi : 18h-20h
3. Soumettre → Redirection vers liste

#### D. Vérifier la liste
- Le cours doit apparaître avec statut "✓ Actif"
- Badge vert "Disponible"
- Actions : Modifier, Désactiver, Supprimer

### 3. Tester en tant qu'Étudiant

#### A. Accéder à la marketplace
1. Se déconnecter
2. Aller sur : `http://localhost/cours-particuliers`
3. URL alternative : Cliquer "💼 Cours Particuliers" dans la navbar

#### B. Filtrer les cours
- Tester filtre par matière
- Tester filtre par prix (ex: 0-10000)
- Tester filtre par durée (60 min)
- Cliquer "🔍 Rechercher"

#### C. Voir les détails
1. Cliquer sur un cours
2. Vérifier affichage :
   - En-tête avec prof
   - Description complète
   - Disponibilités
   - Prix et durée dans sidebar

#### D. Réserver (TEST PAIEMENT)
1. Se connecter avec un compte étudiant
2. Retourner sur le cours
3. Cliquer "📚 Réserver maintenant"
4. Modal s'ouvre
5. Choisir une date/heure (ex: demain 14:30)
6. Cliquer "💳 Payer"

**⚠️ IMPORTANT** : Le système redirige vers Paytech. En mode test :
- Utiliser carte test Paytech
- Ou vérifier dans la base de données que l'enrollment a bien été créé avec `payment_status = 'pending'`

#### E. Simuler un paiement réussi (pour test rapide)
```sql
-- Dans la base de données
UPDATE private_lesson_enrollments 
SET payment_status = 'paid', payment_reference = 'TEST123' 
WHERE id = X;
```

#### F. Accéder à la salle Jitsi
1. URL : `http://localhost/cours-particuliers/room/{enrollment_id}`
2. Remplacer `{enrollment_id}` par l'ID réel
3. La salle Jitsi doit se charger avec :
   - Info cours en haut
   - Vidéo intégrée
   - Nom d'utilisateur automatique

### 4. Tests du Professeur après Réservation

#### A. Vérifier les stats
1. Retourner sur `/enseignant/cours-particuliers`
2. Vérifier mise à jour :
   - **Étudiants inscrits** : +1
   - **Revenus totaux** : +5000 (si payment_status = paid)

#### B. Voir les inscriptions dans la carte du cours
- Badge "👥 1 / 1" (places remplies)
- Si places_max = 1 → Statut devient "🔒 Complet"

### 5. Tester les Actions Professeur

#### A. Modifier un cours
1. Cliquer "✏️ Modifier"
2. Changer le prix : 7500
3. Enregistrer
4. Vérifier affichage mis à jour

#### B. Désactiver un cours
1. Cliquer "⏸ Désactiver"
2. Statut devient "⏸ Inactif"
3. Le cours disparaît de la marketplace étudiante

#### C. Supprimer un cours
- ⚠️ Possible seulement si aucune inscription payée
- Sinon message d'erreur

### 6. Tests de Sécurité

#### A. Accès non autorisé
```
Tester avec compte étudiant :
http://localhost/enseignant/cours-particuliers
→ Doit rediriger (middleware role:professeur)
```

#### B. Accès salle Jitsi sans paiement
```
Dans la base, mettre payment_status = 'pending'
Essayer d'accéder à /cours-particuliers/room/{id}
→ Message d'erreur "Le paiement n'a pas été effectué"
```

### 7. Tests Callback Paytech (Manuel avec Postman)

#### A. URL de test
```
POST http://localhost/cours-particuliers/payment/callback
```

#### B. Headers
```json
{
  "Paytech-Signature": "signature_calculée",
  "Content-Type": "application/json"
}
```

#### C. Body
```json
{
  "type_event": "sale_complete",
  "ref_command": "PL-123",
  "custom_field": "{\"enrollment_id\": 1}",
  "amount": "5000"
}
```

#### D. Calcul signature
```php
$payload = json_encode($body);
$signature = hash_hmac('sha256', $payload, env('PAYTECH_SECRET'));
```

### 8. Checklist de Validation

#### Base de données
- [x] Table `private_lessons` existe
- [x] Table `private_lesson_enrollments` existe
- [x] Foreign keys configurées
- [x] Unique constraint sur enrollments

#### Routes
- [x] `/cours-particuliers` accessible (public)
- [x] `/cours-particuliers/{id}` accessible
- [x] `/enseignant/cours-particuliers` protégé (prof only)
- [x] Callback Paytech configuré

#### Vues
- [x] Marketplace affiche les cours
- [x] Filtres fonctionnent
- [x] Détails cours affichent tout
- [x] Modal réservation fonctionne
- [x] Dashboard prof affiche stats
- [x] Formulaire création fonctionne
- [x] Salle Jitsi se charge

#### Fonctionnalités
- [x] Création cours par prof
- [x] Modification cours
- [x] Activation/Désactivation
- [x] Suppression (si conditions OK)
- [x] Réservation par étudiant
- [x] Génération room Jitsi automatique
- [x] Accès salle après paiement

### 9. Erreurs Courantes

#### "Class 'Matiere' not found"
- Vérifier import dans PrivateLessonController : `use App\Models\Matiere;`

#### "Route private-lessons.browse not defined"
- Vérifier web.php : routes bien enregistrées
- Cache clear : `php artisan route:clear`

#### Jitsi ne se charge pas
- Vérifier console navigateur (F12)
- Vérifier script : `https://8x8.vc/vpaas-magic-cookie-[...]/external_api.js`
- Vérifier roomName format

#### Erreur lors du paiement
- Vérifier .env : PAYTECH_API_KEY, PAYTECH_SECRET, PAYTECH_ENV
- Vérifier logs : `storage/logs/laravel.log`

### 10. Commandes Utiles

```bash
# Voir les routes
php artisan route:list --name=private-lessons

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Voir les migrations
php artisan migrate:status

# Rollback si problème
php artisan migrate:rollback

# Re-migrer
php artisan migrate

# Voir les logs en temps réel
tail -f storage/logs/laravel.log
```

---

## 🎯 Scénario de Test Complet (5 minutes)

1. **Créer compte prof** → Créer 2 cours différents
2. **Aller sur marketplace** → Vérifier affichage
3. **Filtrer par prix** → Vérifier résultats
4. **Voir détails cours 1** → Vérifier infos complètes
5. **Se connecter étudiant** → Réserver cours 1
6. **Simuler paiement OK** (UPDATE SQL)
7. **Accéder salle Jitsi** → Vérifier chargement
8. **Retour dashboard prof** → Vérifier stats mises à jour
9. **Désactiver cours 2** → Vérifier disparition marketplace
10. **Tenter supprimer cours 1** → Erreur (inscriptions payées)

**Résultat attendu** : Toutes les étapes passent ✅

---

**Bon test !** 🚀
