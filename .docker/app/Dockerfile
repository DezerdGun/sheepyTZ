FROM php:8.3-fpm

# системные зависимости
RUN apt-get update && apt-get install -y \
    git curl libpq-dev libzip-dev unzip libicu-dev libonig-dev \
    && docker-php-ext-install intl zip pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# opcache
RUN docker-php-ext-enable opcache

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# рабочая директория
WORKDIR /var/www

# права на storage/bootstrap
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# устанавливаем Node.js (для vite или фронта)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# пользователь по умолчанию
USER www-data

CMD ["php-fpm"]
