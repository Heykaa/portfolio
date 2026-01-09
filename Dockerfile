# ---------- 1) Composer deps ----------
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader

# ---------- 2) Vite build ----------
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
# If you DO have tailwind/postcss config files, keep them in repo and add explicit COPY lines.
RUN npm run build

# ---------- 3) Final image ----------
FROM php:8.4-fpm

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

# Ensure Laravel writable/cache folders exist
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Prevent Vite dev hotfile from being in image
RUN rm -f public/hot || true

COPY ./render/nginx.conf /etc/nginx/nginx.conf
RUN chmod +x /var/www/html/render/start.sh

EXPOSE 8080
CMD ["bash", "-lc", "/var/www/html/render/start.sh"]
