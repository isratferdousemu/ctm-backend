#!/bin/bash

composer install --no-progress --no-interaction --ignore-platform-reqs

echo "Creating env file for env $APP_ENV"
cp .env.example .env

php artisan migrate:fresh --seed
#php artisan migrate
php artisan key:generate
php artisan route:clear
php artisan config:clear
php artisan config:cache
php artisan cache:clear
php artisan optimize:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"
