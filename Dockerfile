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

COPY package*.json ./
RUN npm ci

COPY . .
COPY --from=composer /app/vendor ./vendor

RUN npm run build

# -------------------------
# Stage 3: PHP-FPM production
# -------------------------
FROM php:8.3-fpm-alpine

# Installer les dépendances système nécessaires et extensions PHP
RUN apk add --no-cache \
        mysql-client \
        libpng-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libwebp-dev \
        zlib-dev \
        oniguruma-dev \
        zip \
        unzip \
        git \
        curl \
        jpegoptim optipng pngquant gifsicle \
    && docker-php-ext-configure gd \
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
        zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/cache/apk/* /tmp/*

# Copier Composer depuis l'image composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier fichiers de l'application + vendor + assets buildés
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Exposer le port PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
