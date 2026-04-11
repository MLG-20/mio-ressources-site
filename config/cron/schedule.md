# Configuration Scheduler Laravel — Cron

## Installation

Sur le serveur de production, ajouter cette ligne au crontab de www-data :

```bash
crontab -e -u www-data
* * * * * cd /var/www/mio-ressources-site && php artisan schedule:run >> /dev/null 2>&1
