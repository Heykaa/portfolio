#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# --- MUST EXIST: Livewire temp upload directory ---
mkdir -p storage/app/livewire-tmp || true
mkdir -p storage/app/public || true

# --- Laravel runtime dirs ---
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views bootstrap/cache || true

# --- Public dirs used by Filament assets / uploads ---
mkdir -p public/storage public/js public/css || true

# --- Permissions (important on Render) ---
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R ug+rwX storage bootstrap/cache public || true

# Ensure APP_KEY exists
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Generating..."
  php artisan key:generate --force || true
fi

# Clear caches
php artisan optimize:clear || true

# Storage link (may fail if filesystem blocks symlink; we ignore safely)
php artisan storage:link || true

# Filament assets (copy into /public) - may fail if public not writable; we already fixed perms
php artisan filament:assets || true

# Migrate + seed (if you want)
php artisan migrate --force || true
php artisan db:seed --force || true

# Re-cache
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true

# Normalize perms again
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R ug+rwX storage bootstrap/cache public || true

php-fpm -D
nginx -g "daemon off;"
