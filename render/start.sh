#!/usr/bin/env bash
set -e

cd /var/www/html

# ✅ IMPORTANT: prevent Vite trying dev-server in production
rm -f public/hot || true

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Clear caches safely
php artisan optimize:clear || true

# Storage link
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# ✅ RUN MIGRATIONS (set RUN_MIGRATIONS=true in Render env once, then you can turn it off)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
  php artisan migrate --force || true
fi

php-fpm -D
nginx -g "daemon off;"
