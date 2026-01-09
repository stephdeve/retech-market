# Multi-stage Dockerfile pour Laravel

# -------------------------
# Stage 1: Composer / PHP dependencies
# -------------------------
FROM composer:2.7 AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# -------------------------
# Stage 2: Frontend assets (Tailwind / Vite)
# -------------------------
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
COPY --from=composer /app/vendor ./vendor

RUN npm run build

# -------------------------
# Stage 3: PHP-FPM production
# -------------------------
FROM php:8.3-fpm-alpine

# Installer dépendances système
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    oniguruma-dev \
    jpegoptim optipng pngquant gifsicle

# Installer extensions PHP
RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
        --with-webp=/usr/include/ \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# Installer Redis si nécessaire
RUN pecl install redis && docker-php-ext-enable redis

# Copier Composer depuis l'image composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copier configuration PHP personnalisée si nécessaire
# COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Exposer le port PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
