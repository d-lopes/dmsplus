#!/bin/bash

# on initial startup generate the laravel app key
INIT_INDICATOR_FILE='/var/www/storage/initialized'
if ! [[ -f "$INIT_INDICATOR_FILE" ]]; then 

  # rewrite ownership again, since it has been changed due to mounting of docker volumns
  chown www-data:www-data -R /var/www/storage
  chmod 777 /var/www/storage/app/documents /var/www/storage/search /var/www/storage/logs

  php artisan key:generate
  echo "true" >> $INIT_INDICATOR_FILE
fi

# wait for mysql
while ! nc -vz $DB_HOST 3306 ; do
  sleep 1
done

# switch to existing www user
su www-data

# run artisan database scripts
php artisan migrate
php artisan db:seed

# start workspace process
php-fpm