FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl libpq-dev libzip-dev unzip libicu-dev libonig-dev postgresql-client \
    && docker-php-ext-install intl zip pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-enable opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

USER www-data

CMD ["php-fpm"]
