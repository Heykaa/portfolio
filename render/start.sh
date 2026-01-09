#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Run migration safely (Render free has no shell)
php artisan migrate --force || true

# Storage link
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

php-fpm -D
nginx -g "daemon off;"
