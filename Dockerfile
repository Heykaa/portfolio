FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip nginx \
    libzip-dev libpq-dev \
    libicu-dev \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Ensure Laravel writable/cache folders exist
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# IMPORTANT: prevent artisan scripts running during build
RUN composer install --no-dev --optimize-autoloader --no-scripts


COPY ./render/nginx.conf /etc/nginx/nginx.conf

# Start script (runtime tasks)
RUN chmod +x /var/www/html/render/start.sh

EXPOSE 8080
CMD ["bash", "-lc", "/var/www/html/render/start.sh"]

