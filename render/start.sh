#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# ----------------------
# Clean old caches
# ----------------------
rm -f bootstrap/cache/*.php || true

# ----------------------
# Ensure directories
# ----------------------
mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache public/storage public/livewire

# ----------------------
# Composer install (if needed)
# ----------------------
composer install --no-dev --optimize-autoloader

# ----------------------
# Generate APP_KEY
# ----------------------
if [ -z "$APP_KEY" ]; then
  php artisan key:generate --force
fi

# ----------------------
# Publish Livewire assets
# ----------------------
php artisan livewire:publish --assets || true

# ----------------------
# Copy storage (Render safe)
# ----------------------
if [ ! -d "public/storage" ]; then
  cp -r storage/app/public/* public/storage/ || true
fi

# ----------------------
# Migrate & Seed
# ----------------------
php artisan migrate --force || true
php artisan db:seed --force || true

# ----------------------
# Cache for production
# ----------------------
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ----------------------
# Permissions
# ----------------------
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R 775 storage bootstrap/cache public || true

# ----------------------
# Start PHP-FPM & Nginx
# ----------------------
php-fpm -D
nginx -g "daemon off;"
