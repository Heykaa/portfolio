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

RUN composer install --no-dev --optimize-autoloader

COPY ./render/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080
CMD ["bash", "-lc", "php-fpm -D && nginx -g 'daemon off;'"]

