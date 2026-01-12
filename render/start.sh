#!/usr/bin/env bash
set -e

cd /var/www/html

php -v

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  bootstrap/cache || true

chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

rm -rf storage/framework/cache/* storage/framework/views/* bootstrap/cache/* || true
mkdir -p storage/framework/cache/data storage/framework/views bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

artisan() {
  su -s /bin/bash www-data -c "php artisan $*" || true
}

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY is missing. Generating..."
  artisan "key:generate --force"
fi

artisan "optimize:clear"
artisan "storage:link"
artisan "migrate --force"
artisan "db:seed --force"

# Filament assets (safe to run)
artisan "filament:assets"

artisan "config:cache"
artisan "route:cache"
artisan "view:cache"

php-fpm -D
nginx -g "daemon off;"
