FROM sail-8.2/app

   # Installer cron
   RUN apt-get update && apt-get install -y cron

   # Ajouter le fichier crontab
   COPY crontab /etc/cron.d/laravel-cron
   RUN chmod 0644 /etc/cron.d/laravel-cron
   RUN crontab /etc/cron.d/laravel-cron

   # Script pour démarrer les services
   COPY start-container.sh /usr/local/bin/start-container
   RUN chmod +x /usr/local/bin/start-container

   CMD ["/usr/local/bin/start-container"]