# Configuration Logs Laravel — Surveillance manuelle

## Qu'est-ce que les logs ?

Les logs enregistrent **tous les événements et erreurs** de ton application. C'est ton backup au cas où Sentry manquerait quelque chose.

## Emplacement

```
/var/www/mio-ressources-site/storage/logs/laravel.log
```

## Surveiller les logs en temps réel

Sur le serveur, pour voir les NOUVEAUX logs à mesure qu'ils arrivent :

```bash
tail -f /var/www/mio-ressources-site/storage/logs/laravel.log
```

(Appuie sur `Ctrl+C` pour arrêter)

## Voir les 50 dernières lignes

```bash
tail -50 /var/www/mio-ressources-site/storage/logs/laravel.log
```

## Voir les logs complets

```bash
cat /var/www/mio-ressources-site/storage/logs/laravel.log
```

## Rechercher une erreur spécifique

```bash
grep "ERROR" /var/www/mio-ressources-site/storage/logs/laravel.log

grep "Exception" /var/www/mio-ressources-site/storage/logs/laravel.log
```

## Nettoyer les logs (si trop volumineux)

```bash
# Vider le fichier log
> /var/www/mio-ressources-site/storage/logs/laravel.log

# Ou supprimer et le système le recréera
rm /var/www/mio-ressources-site/storage/logs/laravel.log
```

## Logs des workers (queue)

```bash
tail -f /var/www/mio-ressources-site/storage/logs/worker.log
```

## Bonnes pratiques

- 📋 Consulter les logs **chaque jour** au démarrage
- 🔴 **Immédiatement** s'il y a une erreur Sentry
- 🧹 Nettoyer les logs vieux de très longtemps
