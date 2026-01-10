# ---------- 0) Composer binary ----------
FROM composer:2 AS composer_bin

# ---------- 1) Composer deps (WITH intl + zip) ----------
FROM php:8.4-cli AS vendor

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev \
 && docker-php-ext-install intl zip \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer_bin /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

# ---------- 2) Vite build ----------
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ---------- 3) Final image (PHP-FPM + Nginx) ----------
FROM php:8.4-fpm

# System deps + PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip nginx \
    libzip-dev libpq-dev libicu-dev \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip intl \
 && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# App source
COPY . .

# Vendor from composer stage
COPY --from=vendor /app/vendor ./vendor

# Built assets from node stage
COPY --from=assets /app/public/build ./public/build

# Ensure Laravel writable/cache folders exist (include cache/data)
RUN mkdir -p \
      storage/framework/cache/data \
      storage/framework/sessions \
      storage/framework/views \
      bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Prevent Vite dev hotfile
RUN rm -f public/hot || true

# Nginx config + start script
COPY ./render/nginx.conf /etc/nginx/nginx.conf
RUN chmod +x /var/www/html/render/start.sh

# Optional: make nginx folders writable (reduces noisy warnings)
RUN mkdir -p /var/log/nginx /var/lib/nginx /run \
 && chown -R www-data:www-data /var/log/nginx /var/lib/nginx || true

EXPOSE 8080

CMD ["bash", "-lc", "/var/www/html/render/start.sh"]
