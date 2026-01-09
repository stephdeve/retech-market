# Multi-stage Dockerfile for Laravel Application
# Stage 1: Dependencies
FROM composer:2.7 AS composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# Stage 2: Frontend Assets
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
COPY --from=composer /app/vendor ./vendor

RUN npm run build

# Stage 3: Production Runtime
FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    mysql-client \
    postgresql-client \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    freetype-dev \
    libjpeg-turbo-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --from=composer /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy configuration files
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
