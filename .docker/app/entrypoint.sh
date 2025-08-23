#!/bin/sh
set -e

if [ ! -d "/var/www/vendor" ]; then
    echo "Устанавливаем зависимости Composer..."
    composer install --optimize-autoloader
fi

if [ ! -f "/var/www/.env" ]; then
    echo "Создаём .env из .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

if ! php artisan key:generate --show | grep -q 'base64:'; then
    echo "APP_KEY отсутствует, генерируем..."
    php artisan key:generate --ansi
fi

echo "Ожидаем доступность базы данных..."
until pg_isready -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}"; do
    sleep 2
done

echo "Применяем миграции и сиды..."
php artisan migrate --seed --force || {
    echo "Ошибка при миграциях/сидах, пробуем установить Faker и повторно..."
    composer require fakerphp/faker --dev
    php artisan migrate --seed --force
}
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

exec php-fpm
