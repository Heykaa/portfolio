#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# Ensure folders
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache public/storage || true

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# Clear caches
php artisan optimize:clear || true

# Publish Livewire assets (CRITICAL)
php artisan livewire:publish --assets || true

# Render-safe storage (NO symlink)
if [ ! -d "public/storage" ]; then
  cp -r storage/app/public public/storage || true
fi

# Migrate & seed
php artisan migrate --force || true
php artisan db:seed --force || true

# Cache for production
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Permissions
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R 775 storage bootstrap/cache public || true

# Start services
php-fpm -D
nginx -g "daemon off;"
