#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# Ensure required dirs (include cache/data)
mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  bootstrap/cache || true

# Fix permissions FIRST
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Optional: hard reset stale cache files (prevents weird cache clear failures)
rm -rf storage/framework/cache/* storage/framework/views/* bootstrap/cache/* || true
mkdir -p storage/framework/cache/data storage/framework/views bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Helper to run artisan as www-data (avoids root-owned cache files)
artisan() {
  su -s /bin/bash www-data -c "php artisan $*" || true
}

# Ensure APP_KEY exists (Render env vars should provide this)
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Generating..."
  artisan "key:generate --force"
fi

# Clear caches
php artisan optimize:clear || true
php artisan view:clear || true
php artisan config:clear || true

# Storage link
artisan "storage:link"

# Migrations

artisan "migrate --force"
artisan "db:seed --force"


# Rebuild production caches
artisan "config:cache"
artisan "route:cache"
artisan "view:cache"

# Start PHP-FPM
php-fpm -D

# Start Nginx foreground
nginx -g "daemon off;"
