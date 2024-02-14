#!/bin/bash

composer install --no-progress --no-interaction --ignore-platform-reqs

echo "Creating env file for env $APP_ENV"
cp .env.example .env


role=${CONTAINER_ROLE:-app}

if [ "$role" = "app" ]; then
    php artisan migrate:fresh --seed
    #php artisan migrate
    php artisan key:generate
    php artisan route:clear
    php artisan config:clear
    php artisan config:cache
    php artisan cache:clear
    php artisan optimize:clear
    php artisan l5-swagger:generate
    php artisan storage:link


    php artisan serve --port=$PORT --host=0.0.0.0 --env=.env
    exec docker-php-entrypoint "$@"
elif [ "$role" = "queue" ]; then
    echo "Running the queue ... "
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=180
elif [ "$role" = "websocket" ]; then
    echo "Running the websocket server ... "
    php artisan websockets:serve
fi


