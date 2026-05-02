# Scalabilité — MIO Ressources

Analyse technique de la scalabilité du projet (mai 2026).

---

## État actuel

| Catégorie | État | Risque |
|---|---|---|
| Indexes base de données | ❌ Manquants sur plusieurs tables | CRITIQUE |
| Requêtes N+1 | ❌ Présentes dans Filament et l'API | ÉLEVÉ |
| Emails synchrones | ⚠️ Bloquent la réponse HTTP | ÉLEVÉ |
| Cache widgets dashboard | ⚠️ Requêtes lourdes sans cache | ÉLEVÉ |
| Stockage fichiers | ⚠️ Local (VPS) — pas de CDN | MOYEN |
| Sessions | ✅ Base de données avec indexes | OK |
| Queue Redis | ✅ Configuré et actif | OK |
| Cache Redis | ✅ Utilisé sur sliders/semestres/settings | OK |

---

## Capacité estimée

| Utilisateurs actifs / mois | Tient ? |
|---|---|
| 0 – 200 | ✅ Très bien |
| 200 – 2 000 | ⚠️ Tient, dashboard lent sans cache |
| 2 000 – 10 000 | ❌ Nécessite indexes + cache + séparation MySQL |
| 10 000+ | ❌ Refonte infrastructure requise |

---

## Problèmes à corriger (par priorité)

### 1. Indexes manquants — CRITIQUE

Les tables suivantes manquent d'indexes sur les colonnes fréquemment interrogées :

| Table | Colonnes sans index |
|---|---|
| `ressources` | `user_id`, `matiere_id`, `is_premium`, `created_at` |
| `purchases` | `user_id`, `created_at` |
| `visits` | `visit_date`, `page_visited` ← grossit sans limite |
| `download_histories` | `user_id`, `downloaded_at` |
| `resource_ratings` | `user_id`, `ressource_id` |

**Fix prévu :** migration `add_indexes_to_main_tables`

```php
Schema::table('visits', function (Blueprint $table) {
    $table->index('visit_date');
    $table->index(['page_visited', 'visit_date']);
});

Schema::table('ressources', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('matiere_id');
    $table->index('is_premium');
});

Schema::table('purchases', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('created_at');
});
```

---

### 2. Requêtes N+1 — ÉLEVÉ

Plusieurs endroits chargent des relations sans eager loading :

- `RessourceResource` : `matiere.semestre.niveau` chargé sans `with()`
- `PurchaseResource` : accède à `$record->ressource->titre` sans eager loading
- `Api/RessourceController` : aucun `with()` sur les relations

**Fix prévu :** ajouter `->with(['matiere.semestre'])` dans les requêtes Eloquent des List pages.

---

### 3. Emails synchrones — ÉLEVÉ

Tous les envois de mails (achat, facture, contact, abonnement) sont **synchrones**.
Si le serveur mail est lent, l'utilisateur attend que l'email parte avant de recevoir sa réponse.

Fichiers concernés :
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/ContactController.php`

**Fix prévu :** créer des Jobs Laravel et les dispatcher en queue.

```php
// Exemple
SendPurchaseConfirmation::dispatch($user, $purchase);
SendInvoiceEmail::dispatch($user, $pdfPath);
```

---

### 4. Cache widgets dashboard — ÉLEVÉ

Les widgets `StatsOverview` et `FinanceOverview` relancent des agrégations SQL
lourdes à **chaque ouverture** du tableau de bord admin. 10 admins connectés = 10 full-table scans simultanés.

**Fix prévu :**
```php
Cache::remember('stats_visits_month', 600, fn() =>
    Visit::where('visit_date', '>=', now()->startOfMonth())->count()
);
```

---

### 5. Sessions Redis — FAIBLE (10 min de travail)

Les sessions sont stockées en base de données MySQL → charge supplémentaire à chaque requête.
Passer sur Redis allège la base.

**Fix :** dans `.env`
```
SESSION_DRIVER=redis
```

---

## Infrastructure future (10 000+ utilisateurs)

- Séparer MySQL sur un serveur dédié
- Passer le stockage fichiers sur S3 / espace objet cloud
- Ajouter un load balancer (Nginx / HAProxy) avec 2-3 serveurs app
- Passer Redis sur un serveur dédié
- Archiver ou partitionner la table `visits` (millions de lignes à terme)
