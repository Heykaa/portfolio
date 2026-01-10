#!/usr/bin/env bash
set -e

echo "🚀 Starting Laravel container..."

cd /var/www/html

php -v

# --------------------------------------------------
# 1. Ensure required directories exist
# --------------------------------------------------
echo "📁 Ensuring storage & cache directories..."

mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/app/public \
  bootstrap/cache \
  public/storage \
  public/livewire || true

# --------------------------------------------------
# 2. Ensure APP_KEY exists
# --------------------------------------------------
if [ -z "$APP_KEY" ]; then
  echo "🔑 APP_KEY missing, generating..."
  php artisan key:generate --force
fi

# --------------------------------------------------
# 3. Clear all stale caches (safe in container)
# --------------------------------------------------
echo "🧹 Clearing caches..."
php artisan optimize:clear || true

# --------------------------------------------------
# 4. Publish Livewire assets (CRITICAL FOR FILAMENT)
# Render DOES NOT support symlink
# --------------------------------------------------
echo "⚡ Publishing Livewire assets..."
php artisan livewire:publish --assets || true

# --------------------------------------------------
# 5. Render-safe storage handling (NO symlink)
# --------------------------------------------------
if [ ! -d "public/storage" ]; then
  echo "📦 Copying storage assets (Render-safe)..."
  cp -r storage/app/public/* public/storage/ 2>/dev/null || true
fi

# --------------------------------------------------
# 6. Run migrations & seeders (if enabled)
# --------------------------------------------------
if [ "$RUN_MIGRATIONS" = "true" ]; then
  echo "🗄️ Running migrations..."
  php artisan migrate --force || true

  echo "🌱 Seeding database..."
  php artisan db:seed --force || true
fi

# --------------------------------------------------
# 7. Optimize for production
# --------------------------------------------------
echo "⚙️ Caching config, routes & views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# --------------------------------------------------
# 8. Permissions (important for sessions & cache)
# --------------------------------------------------
echo "🔐 Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R 775 storage bootstrap/cache public || true

# --------------------------------------------------
# 9. Start PHP-FPM & Nginx
# --------------------------------------------------
echo "🚦 Starting PHP-FPM & Nginx..."

php-fpm -D
nginx -g "daemon off;"
