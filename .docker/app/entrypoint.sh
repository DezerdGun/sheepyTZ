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

echo "Ожидаем доступность базы данных..."
until pg_isready -h "${DB_HOST:-db}" -p "${DB_PORT:-5432}" -U "${DB_USERNAME:-postgres}"; do
    echo "Ожидание БД..."
    sleep 5
done

echo "Применяем миграции и сиды..."
php artisan migrate --seed --force || {
    echo "Ошибка при миграциях/сидах, пробуем установить Faker и повторно..."
    php artisan migrate --seed --force
}
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

if ! php artisan key:generate --show | grep -q 'base64:'; then
    echo "APP_KEY отсутствует, генерируем..."
    php artisan key:generate --ansi
fi
exec php-fpm
