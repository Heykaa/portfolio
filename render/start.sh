#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Tunggu DB siap (Postgres / MySQL)
php -r '
$driver = getenv("DB_CONNECTION") ?: "sqlite";
if (!in_array($driver, ["pgsql","mysql","mariadb"])) exit(0);

$host = getenv("DB_HOST");
$port = getenv("DB_PORT");
$db   = getenv("DB_DATABASE");
$user = getenv("DB_USERNAME");
$pass = getenv("DB_PASSWORD");

$dsn = $driver === "pgsql"
    ? "pgsql:host=$host;port=$port;dbname=$db"
    : "mysql:host=$host;port=$port;dbname=$db";

for ($i=1; $i<=30; $i++) {
    try {
        new PDO($dsn, $user, $pass);
        exit(0);
    } catch (Throwable $e) {
        sleep(2);
    }
}
exit(1);
'

php artisan migrate --force || true

if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

php-fpm -D
nginx -g "daemon off;"
