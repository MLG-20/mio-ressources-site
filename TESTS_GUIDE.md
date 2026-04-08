# 🧪 Guide des Tests — MIO Ressources

## Vue d'ensemble

J'ai créé une suite de tests complète pour couvrir les parties critiques du projet avant déploiement :

### 📁 Fichiers créés:

#### Feature Tests (tests d'intégration):
1. **`tests/Feature/PaymentTest.php`** - 9 tests
   - Accès page paiement
   - Validation des données
   - Webhook PayTech IPN ✅ signature valide
   - Webhook IPN ❌ signature invalide
   - Paiement échoué
   - Déduplication paiement
   - Accès après paiement
   - Blocage accès sans paiement

2. **`tests/Feature/AuthorizationTest.php`** - 17 tests
   - Accès admin panel (rôles)
   - Utilisateurs bloqués
   - Permissions RBAC (admin/prof/étudiant)
   - Modification ressources (own only)
   - Achat ressources
   - Accès contenu acheté
   - Création courses particulières
   - Blocage/déblocage utilisateurs

3. **`tests/Feature/PrivateLessonTest.php`** - 15 tests
   - Création courses particulières
   - Validation données
   - Dates passées (rejetées)
   - Inscription étudiants
   - Éviter doublons inscription
   - Annulation cours
   - Notifications
   - Liens temporaires téléchargement
   - Rappels avant cours

#### Unit Tests (tests isolés):
4. **`tests/Unit/Models/UserModelTest.php`** - 7 tests
   - Relations modèle
   - Hashage passwords
   - Utilisateurs bloqués
   - Méthodes d'accès (isAdmin, isTeacher)

5. **`tests/Unit/Models/PurchaseModelTest.php`** - 8 tests
   - Relations polymorphes
   - Validations status
   - Unicité payment_ref
   - Timestamps

6. **`tests/Unit/Models/RessourceModelTest.php`** - 6 tests
   - Relations
   - Notes/ratings
   - Prix (gratuit/payant)
   - Images couverture

**Total: 62 nouveaux tests** ✅

---

## 🚀 Comment exécuter les tests

### 1️⃣ Configuration initiale

```bash
cd c:\Herd\mio_ressources

# Installer les dépendances
composer install --dev

# Configurer .env test (optionnel, phpunit.xml gère)
# Les tests utilisent SQLite en mémoire
```

### 2️⃣ Exécuter TOUS les tests

```bash
# Tous les tests
composer test

# Ou avec php directement
php artisan test

# Ou avec phpunit
.\vendor\bin\phpunit
```

### 3️⃣ Exécuter tests spécifiques

```bash
# Seulement les tests Feature
php artisan test tests/Feature

# Seulement les tests Unit
php artisan test tests/Unit

# Un fichier spécifique
php artisan test tests/Feature/PaymentTest.php

# Un test spécifique
php artisan test tests/Feature/PaymentTest.php --filter test_student_can_access_payment_page

# Avec details
php artisan test -v

# Avec verbosité maximum
php artisan test tests/Feature/PaymentTest.php -vvv
```

### 4️⃣ Voir la couverture de code (optionnel)

```bash
# Installation PCOV (plus rapide)
composer require --dev pcov/cev

# Générer le rapport de couverture
php artisan test --coverage

# HTML coverage report
php artisan test --coverage --coverage-html coverage/
```

---

## 📊 Structure des tests

### Format standard de chaque test:

```php
public function test_description_du_test(): void
{
    // ARRANGE - Préparer les données
    $user = User::factory()->create();
    
    // ACT - Exécuter l'action
    $response = $this->actingAs($user)->post(route('action'));
    
    // ASSERT - Vérifier le résultat
    $response->assertStatus(200);
}
```

### Points clés:

✅ **RefreshDatabase** - Les tests utilisent une DB vierge à chaque test  
✅ **Factories** - Création rapide de données de test  
✅ **Assertions** - Vérifications: status, DB, JSON, erreurs  
✅ **Isolation** - Chaque test est indépendant  

---

## 🔍 Que testent les tests?

### **PaymentTest**
- [ ] Flux paiement complet (accès → validation → webhook → achat)
- [ ] Sécurité IPN (signature HMAC-SHA256)
- [ ] Dedupe (même paiement deux fois = une seule Purchase)
- [ ] Accès aux ressources après paiement

### **AuthorizationTest**
- [ ] RBAC - chaque rôle peut/ne peut pas accéder aux bonnes routes
- [ ] Propriété ressources - prof ne peut modifier que les siennes
- [ ] Blocage utilisateurs - pas de connexion/action si bloqué

### **PrivateLessonTest**
- [ ] Cycles de vie complets (création → inscription → annulation)
- [ ] Notifications (email avant cours, annulation)
- [ ] Liens temporaires (30 min expiration)
- [ ] Rappels automatiques

### **Model Tests (Unit)**
- [ ] Relations (belongs-to, has-many, polymorphe)
- [ ] Validations
- [ ] Calculs (notes moyennes)
- [ ] Intégrité (unicité, cascades)

---

## 🛠️ Quand ajouter des tests?

### ✅ TOUJOURS tester:
- Routes protégées (authentification, autorisation)
- Logique métier (paiements, calculs)
- Webhooks/intégrations externes (PayTech)
- Notifications importantes
- Relations critiques (purchases, enrollments)

### ❌ PAS besoin de tester:
- Migrations (Laravel les gère)
- Routes basiques sans logique
- Affichage (views, sauf cas critiques)
- Dépendances tierces bien testées

---

## 🐛 Debugging des tests

### Test échoue?

```bash
# 1. Voir le détail de l'erreur
php artisan test tests/Feature/PaymentTest.php::test_student_can_access_payment_page -v

# 2. Arrêter au premier échec
php artisan test --stop-on-failure

# 3. Relancer seulement les tests échoués
php artisan test --failed

# 4. Debug avec dd()
// Dans le test
$response->dump(); // Print réponse
$response->dumpAll(); // Plus de détails
```

---

## 📈 Progression tests couverts

| Domaine | Avant | Après | Couverture |
|---------|-------|-------|-----------|
| Paiement | 0 | 9 | 🟢 Bon |
| Autorisation | 0 | 17 | 🟢 Bon |
| Courses particulières | 0 | 15 | 🟢 Bon |
| Models Users | 0 | 7 | 🟡 Basique |
| Models Purchases | 0 | 8 | 🟡 Basique |
| Models Ressources | 0 | 6 | 🟡 Basique |
| **TOTAL** | **1** | **62** | 🟢 62x amélioration! |

---

## 🎯 Prochains pas (après tests fondamentaux)

1. **Tests Forum**
   - `tests/Feature/ForumTest.php` - Validation messages, modération
   
2. **Tests Notifications**
   - Vérifier emails envoyés (Mail facade)
   - Vérifier SMS (Vonage/Twilio)

3. **Tests API (si endpoints publics)**
   - Rate limiting
   - Pagination
   - Filtres/recherche

4. **Tests Performance** (si besoin)
   - Query count (N+1)
   - Temps réponse

---

## ✅ Checklist avant déploiement

- [ ] Tous les tests passent: `composer test`
- [ ] Pas de warnings: `php artisan test -v`
- [ ] Coverage acceptable: `php artisan test --coverage`
- [ ] Tests exécutés en CI/CD (si vous avez)

---

## 📞 Questions courantes

**Q: Les tests ralentissent le développement?**
A: Non, ils accélèrent! Tests = moins de bugs = moins de debugging en production.

**Q: Faut-il tester tout?**
A: Non, priorité: sécurité (auth, payments), logique métier, workflows critiques.

**Q: Comment tester des webhooks externes?**
A: Mock les réponses HTTP avec `Http::fake()` (voir PaymentTest pour exemple).

**Q: Les tests passent en local mais échouent en CI?**
A: Vérifier .env test, variables d'environnement, ordre exécution tests.

---

**Bonne chance! 🚀**
