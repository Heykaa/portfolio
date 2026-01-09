#!/usr/bin/env bash
set -e

cd /var/www/html

# Ensure folders exist
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

# Best-effort permissions (Render sometimes blocks chown)
if id -u www-data >/dev/null 2>&1; then
  chown -R www-data:www-data storage bootstrap/cache || true
fi
chmod -R 775 storage bootstrap/cache || true

# Clear caches safely
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Rebuild caches (good for prod; won't break if env missing)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Storage link (only if missing)
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# NOTE:
# - APP_KEY must be set in Render env
# - DB_URL should be set (or your config should read DATABASE_URL)
# - Avoid auto-migrate in prod unless you want it

php-fpm -D
nginx -g "daemon off;"
