#!/usr/bin/env bash
set -e

cd /var/www/html

# Ensure env exists (Render injects env vars, so this is usually OK)
php -v

# Laravel prep
php artisan config:clear || true
php artisan route:clear  || true
php artisan view:clear   || true
php artisan cache:clear  || true

# Storage link (ignore if already exists)
php artisan storage:link || true

# Permissions (Render sometimes mounts read-only parts; keep safe)
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Start PHP-FPM (port 9000)
php-fpm -D

# Start Nginx foreground
nginx -g "daemon off;"
