# 📚 Guide d'utilisation de l'API MIO Ressources

## 🚀 Introduction

Bienvenue ! Cette API vous permet d'accéder à toutes les ressources, publications et données de **MIO Ressources** de manière structurée et sécurisée.

---

## 🌐 Accès à la documentation interactive

**URL Swagger UI (pour tester l'API directement):**

http://localhost:8000/api/documentation

Cette interface vous permet de :
- ✅ Voir tous les endpoints disponibles
- ✅ Tester les appels directement ("Try it out")
- ✅ Voir les paramètres requis
- ✅ Voir les réponses attendues
- ✅ Générer du code client

---

## 📋 Endpoints disponibles

### 1️⃣ Récupérer TOUTES les ressources

**Endpoint:** `GET /api/ressources`

**Paramètres (optionnels):**

| Paramètre | Type | Description |
|-----------|------|-------------|
| `page` | integer | Numéro de la page (défaut: 1) |
| `limit` | integer | Nombre de résultats par page (défaut: 15) |

**Exemple de requête:**

```bash
curl http://localhost:8000/api/ressources?page=1&limit=15
```

**Réponse (exemple):**

```json
{
  "data": [
    {
      "id": 1,
      "titre": "Mathématiques L1",
      "description": "Cours complet de mathématiques",
      "type": "Livre",
      "prix": 5000,
      "gratuit": false
    }
  ],
  "total": 42,
  "per_page": 15,
  "current_page": 1
}
```

---

### 2️⃣ Récupérer UNE ressource spécifique

**Endpoint:** `GET /api/ressources/{id}`

**Paramètres:**

| Paramètre | Type | Description |
|-----------|------|-------------|
| `id` | integer | ID de la ressource (OBLIGATOIRE) |

**Exemple de requête:**

```bash
curl http://localhost:8000/api/ressources/1
```

**Réponse (exemple):**

```json
{
  "id": 1,
  "titre": "Mathématiques L1",
  "description": "Cours complet de mathématiques",
  "type": "Livre",
  "prix": 5000,
  "gratuit": false
}
```

---

## 🧪 Comment tester avec Swagger UI

### Méthode 1️⃣ : Directement dans l'interface

1. Allez à : **http://localhost:8000/api/documentation**
2. Cliquez sur un endpoint (ex: `GET /api/ressources`)
3. Cliquez sur **"Try it out"**
4. Entrez les paramètres
5. Cliquez sur **"Execute"**

### Méthode 2️⃣ : Avec cURL (terminal)

```bash
# Récupérer page 1 des ressources
curl http://localhost:8000/api/ressources?page=1

# Récupérer une ressource spécifique
curl http://localhost:8000/api/ressources/5
```

### Méthode 3️⃣ : Avec JavaScript/Fetch

```javascript
fetch('http://localhost:8000/api/ressources?page=1')
  .then(response => response.json())
  .then(data => console.log(data));
```

---

## 📞 Support

Besoin d'aide ? Consultez la documentation Swagger à : **http://localhost:8000/api/documentation**

---

**Version API:** 1.0.0  
**Date:** 26 janvier 2026
