#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction --ignore-platform-reqs
fi

#composer install --no-progress --no-interaction --ignore-platform-reqs

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

#echo "Creating env file for env $APP_ENV"
#cp .env.example .env

php artisan migrate:fresh --seed
#php artisan db:seed --class=PermissionTableSeeder
#php artisan db:seed --class=UserSeeder
php artisan key:generate
php artisan route:clear
php artisan config:clear
php artisan config:cache
php artisan cache:clear
php artisan optimize:clear

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
exec docker-php-entrypoint "$@"
