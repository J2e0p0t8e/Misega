# 1) Dépendances PHP
FROM composer:2 AS vendor
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --ignore-platform-reqs

# 2) Fichiers front (Vue + Vite)
FROM node:22-alpine AS assets
WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor ./vendor
RUN npm ci && npm run build

# 3) Image finale
FROM php:8.3-cli
RUN apt-get update && apt-get install -y libpq-dev libzip-dev unzip \
 && docker-php-ext-install pdo_pgsql zip bcmath \
 && rm -rf /var/lib/apt/lists/*
WORKDIR /app
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build
RUN mkdir -p storage/framework/views storage/framework/sessions storage/framework/cache bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache
EXPOSE 10000
CMD php artisan package:discover && php artisan config:clear && php artisan migrate --force \
 && php artisan app:seed-if-empty && php artisan serve --host 0.0.0.0 --port ${PORT:-10000}
