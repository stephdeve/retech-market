# -------------------------
# Stage 1: Composer / PHP dependencies
# -------------------------
FROM composer:2.7 AS composer

WORKDIR /app

# Copier les fichiers de dépendances et installer les packages PHP
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copier tout le projet pour générer l'autoload optimisé
COPY . .
RUN composer dump-autoload --optimize --no-dev

# -------------------------
# Stage 2: Frontend assets (Tailwind / Vite)
# -------------------------
FROM node:20-alpine AS frontend

WORKDIR /app

# Build arguments pour les variables Vite
ARG VITE_PUSHER_APP_KEY
ARG VITE_PUSHER_APP_CLUSTER

# Les rendre disponibles pendant le build
ENV VITE_PUSHER_APP_KEY=$VITE_PUSHER_APP_KEY
ENV VITE_PUSHER_APP_CLUSTER=$VITE_PUSHER_APP_CLUSTER

COPY package*.json ./
RUN npm ci

COPY . .
COPY --from=composer /app/vendor ./vendor

RUN npm run build

# -------------------------
# Stage 3: PHP-FPM production
# -------------------------
FROM php:8.3-fpm-alpine

# Installer les dépendances système nécessaires
RUN apk add --no-cache \
    nginx \
    mysql-client \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    oniguruma-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    autoconf \
    g++ \
    make \
    linux-headers

# Configurer et installer l'extension GD
RUN docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    --with-webp=/usr/include/

# Installer les extensions PHP
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Installer Redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Nettoyer le cache
RUN rm -rf /var/cache/apk/* /tmp/*

# Copier Composer depuis l'image composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier fichiers de l'application + vendor + assets buildés
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

# Publier les assets Livewire (JS/CSS) pendant le build
RUN php artisan vendor:publish --tag=livewire:assets --force

# Copier le script d'entrypoint Railway
COPY docker/railway-entrypoint.sh /usr/local/bin/railway-entrypoint.sh
RUN chmod +x /usr/local/bin/railway-entrypoint.sh

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Créer le répertoire pour Nginx
RUN mkdir -p /run/nginx

# Exposer le port dynamique de Railway (défini via $PORT)
EXPOSE 8080

# Utiliser le script d'entrypoint
CMD ["/usr/local/bin/railway-entrypoint.sh"]
