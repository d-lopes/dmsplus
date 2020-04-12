#!/bin/bash

# wait for mysql
while ! nc -vz $DB_HOST 3306 ; do
  sleep 1
done

# run artisan database scripts
# TODO: switch back to "php artisan migrate" once we have finished the first release
php artisan migrate:fresh
php artisan db:seed

# start workspace process
php-fpm