FROM php:8.3-fpm

# Устанавливаем необходимые пакеты, включая netcat-openbsd
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    unzip \
    libicu-dev \
    libonig-dev \
    default-mysql-client \
    netcat-openbsd \
    && docker-php-ext-install intl zip pdo pdo_mysql \
    && docker-php-ext-enable opcache \
    && rm -rf /var/lib/apt/lists/*

# Копируем Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www

# Создаем storage и bootstrap/cache с правильными правами
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Копируем entrypoint
COPY .docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
