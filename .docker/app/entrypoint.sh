#!/bin/sh
set -e

echo "=== Запуск Laravel entrypoint ==="

# ========================
# 1. Установка зависимостей Composer
# ========================
if [ ! -d "/var/www/vendor" ]; then
    echo "Устанавливаем зависимости Composer..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# ========================
# 2. Создание .env
# ========================
if [ ! -f "/var/www/.env" ]; then
    echo "Создаём .env из .env.example..."
    cp /var/www/.env.example /var/www/.env
fi

# ========================
# 3. Ожидание MySQL
# ========================
echo "Ожидаем доступность базы данных MySQL..."
export MYSQL_PWD="${DB_PASSWORD:-pass}"
until mysqladmin ping -h "${DB_HOST:-db}" -P "${DB_PORT:-3306}" -u "${DB_USERNAME:-root}" --silent; do
    echo "База ещё не готова, ждём 2 секунды..."
    sleep 2
done
echo "MySQL доступна!"

# ========================
# 4. Ожидание Redis
# ========================
echo "Ожидаем доступность Redis..."
until nc -z "${REDIS_HOST:-redis}" "${REDIS_PORT:-6379}"; do
    echo "Redis ещё не готов, ждём 2 секунды..."
    sleep 2
done
echo "Redis доступен!"

# ========================
# 5. Генерация APP_KEY
# ========================
if [ -z "$(grep -E '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
    echo "APP_KEY отсутствует, генерируем..."
    php artisan key:generate --ansi
fi

# ========================
# 6. Применение миграций и сидов
# ========================
echo "Применяем миграции и сиды..."
php artisan migrate --seed --force | tee /var/log/laravel-migrate.log

# ========================
# 7. Настройка прав
# ========================
echo "Настраиваем права на storage и bootstrap/cache..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# ========================
# 8. Генерация Swagger документации
# ========================
echo "Генерируем Swagger документацию..."
php artisan l5-swagger:generate

# ========================
# 9. Запуск php-fpm
# ========================
echo "Запуск php-fpm..."
exec php-fpm
