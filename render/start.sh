#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Clear caches safely
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Only run storage link if not exists
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# Optional: run migrations/seeders on boot (use Render env flags)
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  php artisan migrate --force || true
fi

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
  php artisan db:seed --force || true
fi

php-fpm -D
nginx -g "daemon off;"
