#!/usr/bin/env bash
set -e

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Clear caches safely
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Wait for DB (pgsql/mysql) if needed
php -r '
$driver = getenv("DB_CONNECTION") ?: "sqlite";
if (!in_array($driver, ["pgsql","mysql","mariadb"])) { exit(0); }

$host = getenv("DB_HOST");
$port = getenv("DB_PORT");
$db   = getenv("DB_DATABASE");
$user = getenv("DB_USERNAME");
$pass = getenv("DB_PASSWORD");

$dsn = $driver === "pgsql"
    ? "pgsql:host=$host;port=$port;dbname=$db"
    : "mysql:host=$host;port=$port;dbname=$db";

$max = 30;
for ($i=1; $i<=$max; $i++) {
  try {
    new PDO($dsn, $user, $pass, [PDO::ATTR_TIMEOUT => 2]);
    fwrite(STDERR, "DB ready\n");
    exit(0);
  } catch (Throwable $e) {
    fwrite(STDERR, "Waiting DB ($i/$max)...\n");
    sleep(2);
  }
}
fwrite(STDERR, "DB not reachable\n");
exit(1);
' || exit 1

# Run migrations (needed on Render free)
php artisan migrate --force || true

# Storage link
if [ ! -L public/storage ]; then
  php artisan storage:link || true
fi

php-fpm -D
nginx -g "daemon off;"
