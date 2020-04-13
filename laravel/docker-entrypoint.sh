#!/bin/bash

# wait for mysql
while ! nc -vz $DB_HOST 3306 ; do
  sleep 1
done

# run artisan database scripts
php artisan migrate
php artisan db:seed

# start workspace process
php-fpm