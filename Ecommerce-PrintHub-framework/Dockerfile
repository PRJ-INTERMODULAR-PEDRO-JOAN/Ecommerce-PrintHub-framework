# ─────────────────────────────────────────────
# Stage 1 – Node: build frontend assets
# ─────────────────────────────────────────────
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js tailwind.config.js ./

RUN npm run build

# ─────────────────────────────────────────────
# Stage 2 – Composer: install PHP dependencies
# ─────────────────────────────────────────────
FROM composer:2.7 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --no-dev --classmap-authoritative

# ─────────────────────────────────────────────
# Stage 3 – Production image
# ─────────────────────────────────────────────
FROM php:8.2-fpm-alpine AS production

LABEL maintainer="PrintHub Team"

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Install Redis PHP extension
RUN apk add --no-cache --virtual .build-deps autoconf g++ make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# PHP configuration for production
COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Nginx configuration
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Copy application files
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=frontend /app/public/build ./public/build

# Ensure required directories exist and have proper permissions
RUN mkdir -p storage/framework/{cache,sessions,views} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose HTTP port (Nginx → PHP-FPM)
EXPOSE 80

# Entrypoint runs migrations and boots Supervisor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
