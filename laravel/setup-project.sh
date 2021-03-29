
#!/bin/bash

#composer create-project --prefer-dist laravel/laravel:^8.0 laravel
#cd laravel

composer require laravel/jetstream
composer require laravel-views/laravel-views

php artisan jetstream:install livewire --teams

npm install && npm run dev