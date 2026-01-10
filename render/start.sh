#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# Create required dirs (include cache/data)
mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  bootstrap/cache || true

# Fix permissions FIRST (before running artisan clear)
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# (Optional but powerful) hard reset stale cache files
rm -rf storage/framework/cache/* storage/framework/views/* bootstrap/cache/* || true
mkdir -p storage/framework/cache/data storage/framework/views bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Ensure APP_KEY exists (Render env vars should provide this)
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Generating..."
  php artisan key:generate --force || true
fi

# Clear caches (now should not error)
php artisan optimize:clear || true

# Storage link
php artisan storage:link || true

# Migrations
php artisan migrate --force || true

# Rebuild production caches
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true

# Start PHP-FPM
php-fpm -D

# Start Nginx foreground
nginx -g "daemon off;"
