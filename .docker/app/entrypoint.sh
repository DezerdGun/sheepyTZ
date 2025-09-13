#!/bin/sh
set -e

if [ ! -d "/var/www/vendor" ]; then
    echo "Устанавливаем зависимости Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ ! -f "/var/www/.env" ]; then
    echo "Создаём .env из .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

echo "Ожидаем доступность базы данных..."
export MYSQL_PWD="${DB_PASSWORD:-pass}"

until mysql -h "${DB_HOST:-db}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME:-root}" -e "SELECT 1;" > /dev/null 2>&1; do
    echo "БД ещё не готова, ждём..."
    sleep 2
done

echo "База доступна!"

if [ -z "$(grep -E '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
    echo "APP_KEY отсутствует, генерируем..."
    php artisan key:generate --ansi
fi

php artisan migrate --seed --force

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

exec php-fpm
