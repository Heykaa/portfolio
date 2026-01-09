#!/usr/bin/env bash
set -e

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan config:clear || true
php artisan cache:clear || true

php artisan key:generate --force || true
php artisan storage:link || true
php artisan migrate --force || true

php-fpm -D
nginx -g 'daemon off;'
