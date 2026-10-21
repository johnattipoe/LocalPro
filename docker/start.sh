#!/bin/sh
set -eu

cd /var/www/html

php artisan config:cache
php artisan migrate --force

if [ ! -L public/storage ]; then
    php artisan storage:link
fi

exec apache2-foreground
