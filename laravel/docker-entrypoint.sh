#!/bin/bash

# on initial startup run composer install & generate the laravel app key
INIT_INDICATOR_FILE='/var/www/storage/initialized'
if ! [[ -f "$INIT_INDICATOR_FILE" ]]; then 
  composer install
  php artisan key:generate
  echo "true" >> $INIT_INDICATOR_FILE
fi

# wait for mysql
while ! nc -vz $DB_HOST 3306 ; do
  sleep 1
done

# run artisan database scripts
php artisan migrate
php artisan db:seed

# start workspace process
php-fpm