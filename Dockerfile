# Production-ready Laravel 12 + PHP 8.5-FPM
# Use 8.4 (Laravel 12 compatible). Switch to 8.5 when available.
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    zip \
    intl \
    mbstring \
    gd \
    opcache

# Install Redis extension (optional, for cache)
RUN pecl install redis 2>/dev/null || true \
    && docker-php-ext-enable redis 2>/dev/null || true

# OPcache production settings
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=256" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=16" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=20000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# Get Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies (no dev in production stage)
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist \
    && composer dump-autoload --optimize --no-dev

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
