#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

# Ensure folders exist
mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  bootstrap/cache \
  public/js \
  public/css \
  public/storage || true

# Fix permissions (important for Render)
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R 775 storage bootstrap/cache public || true

# Clear old caches (avoid stale permission files)
rm -rf storage/framework/views/* bootstrap/cache/* || true

# Ensure APP_KEY exists
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Generating..."
  php artisan key:generate --force || true
fi

# Clear caches first
php artisan optimize:clear || true

# ✅ Run these as root to avoid permission denied on public/
php artisan storage:link || true
php artisan filament:assets || true

# Run migrations & seed
php artisan migrate --force || true
php artisan db:seed --force || true

# Re-cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Final permission normalize
chown -R www-data:www-data storage bootstrap/cache public || true
chmod -R 775 storage bootstrap/cache public || true

php-fpm -D
nginx -g "daemon off;"
