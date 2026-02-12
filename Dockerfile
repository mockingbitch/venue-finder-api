FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    icu-dev \
    oniguruma-dev \
    mariadb-dev \
    linux-headers \
    $PHPIZE_DEPS \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        intl \
        opcache \
        zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist 2>/dev/null || composer install --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["docker-entrypoint.sh"]
