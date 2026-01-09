# ---------- 1) Build frontend assets (Vite) ----------
FROM node:20-alpine AS node_build
WORKDIR /app

COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* ./
# Pilih ikut lock file yang ada:
RUN if [ -f package-lock.json ]; then npm ci; \
    elif [ -f pnpm-lock.yaml ]; then npm i -g pnpm && pnpm i --frozen-lockfile; \
    elif [ -f yarn.lock ]; then yarn install --frozen-lockfile; \
    else npm install; fi

COPY . .
RUN npm run build

# ---------- 2) PHP + Nginx runtime ----------
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

# Copy built assets from node stage
COPY --from=node_build /app/public/build /var/www/html/public/build

# Ensure Laravel writable folders
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Install PHP deps (no scripts during build)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Clear caches safely
RUN php artisan config:clear || true \
 && php artisan route:clear || true \
 && php artisan view:clear || true

COPY ./render/nginx.conf /etc/nginx/nginx.conf
RUN chmod +x /var/www/html/render/start.sh

EXPOSE 8080
CMD ["bash", "-lc", "/var/www/html/render/start.sh"]
