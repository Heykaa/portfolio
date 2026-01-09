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

# IMPORTANT:
# - Do NOT generate APP_KEY here (set APP_KEY in Render env)
# - Do NOT auto-migrate in production unless you really want it

php-fpm -D
nginx -g "daemon off;"
