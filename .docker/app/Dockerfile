FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git curl libpq-dev libzip-dev unzip libicu-dev libonig-dev postgresql-client \
    && docker-php-ext-install intl zip pdo pdo_pgsql \
    && docker-php-ext-enable opcache \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY .docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
