#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Make sure cache from old builds is not used
php artisan optimize:clear || true

# Run package discovery at runtime (since we used --no-scripts in build)
php artisan package:discover --ansi || true

# Only run storage link if not exists
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

# DO NOT generate APP_KEY here (use Render env)
# DO NOT auto-migrate unless you really want it in prod

php-fpm -D
nginx -g "daemon off;"
