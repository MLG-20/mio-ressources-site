# Configuration UptimeRobot — Surveillance de disponibilité

## Qu'est-ce qu'UptimeRobot ?

UptimeRobot vérifie **toutes les 5 minutes** que ton site répond correctement. Si le site est down, il envoie une **alerte email/SMS immédiate**.

## Installation (étapes)

### 1. Créer un compte UptimeRobot

- Aller sur [uptimerobot.com](https://uptimerobot.com)
- Sign up (gratuit)
- Valider l'email

### 2. Ajouter un moniteur

Dans le dashboard :
- Cliquer sur **"Add New Monitor"**
- Type : **HTTP(s)**
- URL : `https://mio-ressources.me/health`
- Friendly Name : `MIO Ressources - Health Check`
- Monitoring Interval : **5 minutes**
- Check Frequency : **Every 5 minutes**

### 3. Configurer les alertes

En bas, ajouter tes **Contacts** :
- **Email Alert** : Ton email (obligatoire)
- **SMS Alert** (optionnel) : Ton numéro si tu veux des SMS

Puis **activer les alertes** pour ce moniteur.

### 4. Vérifier le endpoint `/health`

Sur le serveur, vérifier que `/health` répond bien :

```bash
curl https://mio-ressources.me/health
```

Doit retourner :
```json
{"status":"healthy",...}
```

## Alertes reçues

Quand le site est down ou remonte, tu reçois :
- ✅ Email immédiat
- ✅ SMS (si configuré)

## Dashboard

Dans UptimeRobot, tu verras :
- La **disponibilité mensuelle** (cf : uptime)
- L'**historique des pannes**
- Le **temps de réponse** moyen

Gratuit = **50 moniteurs** maximum (tu n'en as besoin que d'1)
