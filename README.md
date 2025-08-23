# OnlyDigital - Доступные служебные автомобили

**Docker-приложение на Laravel и PostgreSQL**


## Возможности

 **Бэкенд**: Laravel на `http://localhost:8000`  
 **REST API**: Swagger на `http://localhost:8081` 
 **База данных**: PostgreSQL с сохранением данных  

## Требования

- Docker и Docker Compose
- Laravel

## Установка

1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/your-repo/
   cp .env.example .env

# Сборка и запуск
    make up

# Остановка

    make down

## Services & Ports

| Service    | URL                                            | Port | Description            |
|------------|------------------------------------------------|------|------------------------|
| Backend    | [http://localhost:8000](http://localhost:8000) | 8000 | Laravel                |
| PostgreSQL | `host: localhost`                              | 5432 | Database               |
| Swagger     | [http://localhost:8081](http://localhost:8081/) | 8081 | Swagger task api     |

## Тестирование (Feature tests)

1. Создайте отдельную тестовую базу данных Postgres (например, onlydigitaltz_test):
   ```bash
   psql -U postgres -c "CREATE DATABASE onlydigitaltz_test;"
   ```
2. Проверьте/отредактируйте файл `.env.testing` (создан автоматически, если нет — скопируйте из .env и поменяйте DB_DATABASE, DB_USERNAME, DB_PASSWORD на тестовые значения).
3. Запустите тесты:
   ```bash
   php artisan test --testsuite=Feature
   ```
   Все тесты будут выполняться на тестовой базе, не затрагивая основную.

