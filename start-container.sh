#!/bin/bash

   # Démarrer le service cron
   service cron start

   # Exécuter les migrations et les seeders
   php artisan migrate --force
   php artisan db:seed --force

   # Démarrer le planificateur Laravel
   php artisan schedule:work