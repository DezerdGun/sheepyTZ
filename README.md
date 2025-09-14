# OnlyDigital – Доступные служебные автомобили

Docker-приложение на **Laravel**, с поддержкой **MySQL**, **Redis**, **Swagger** и **phpMyAdmin**.

---

## 🚀 Возможности

- **Бэкенд:** Laravel на `http://localhost:8080`
- **REST API:** Swagger UI на `http://localhost:8081`
- **База данных:** MySQL 8 с сохранением данных
- **Админка:** phpMyAdmin на `http://localhost:${PMA_PORT}`  
- **Кэш:** Redis 7 для очередей и сессий

---

## 🛠 Требования

- Docker & Docker Compose
- Make (для удобных команд)
- PHP 8.2+, Composer (для локальных команд)
- Node.js (если нужен фронтенд)

---

## 📦 Установка и запуск

1. Клонируйте репозиторий:
   ```bash
   git clone https://github.com/your-repo/onlydigital
   cd onlydigital
   cp .env.example .env
   ```

2. Соберите и запустите контейнеры:
   ```bash
   make up
   ```

3. Установите зависимости Laravel:
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```

4. (Опционально) Установите npm-зависимости и соберите фронтенд:
   ```bash
   docker compose exec app npm install
   docker compose exec app npm run build
   ```

### 🛑 Остановка контейнеров
```bash
make down
```

---

## 🔧 Основные сервисы

| Сервис       | URL / Хост                | Порт         | Описание              |
|--------------|---------------------------|--------------|-----------------------|
| Backend      | http://localhost:8080     | 8080         | Laravel API           |
| MySQL        | host: localhost           | 3306         | База данных MySQL     |
| Swagger      | http://localhost:8081     | 8081         | Документация API      |
| phpMyAdmin   | http://localhost:${PMA_PORT} | ${PMA_PORT} | Веб-интерфейс БД      |
| Redis        | localhost:6379            | 6379         | Кэш / Очереди         |

---

## 🧪 Тестирование (Feature tests)

1. Создайте тестовую базу:
   ```bash
   docker compose exec db mysql -u root -p -e "CREATE DATABASE onlydigital_test;"
   ```

2. Скопируйте `.env` в `.env.testing` и укажите тестовую базу:
   ```bash
   DB_DATABASE=onlydigital_test
   ```

3. Запустите тесты:
   ```bash
   docker compose exec app php artisan test --testsuite=Feature
   ```

Все тесты выполняются на тестовой базе и не затрагивают рабочие данные.

---

## 📜 Полезные команды

| Команда                                      | Описание                          |
|----------------------------------------------|-----------------------------------|
| `make up`                                    | Сборка и запуск контейнеров       |
| `make down`                                  | Остановка контейнеров             |
| `make restart`                               | Перезапуск контейнеров            |
| `docker compose logs -f`                     | Просмотр логов                    |
| `docker compose exec app php artisan migrate` | Прогнать миграции                 |
| `docker compose exec app composer dump-autoload` | Обновить автозагрузку         |

---

## 📂 Структура проекта

```
.docker/
  app/
    Dockerfile
    entrypoint.sh
  nginx/
    default.conf
docker-compose.yml
.env.example
Makefile
src/
  ...
```

---

## 🧾 Примечания

- Переменные окружения хранятся в `.env` (DB, Redis, Swagger, phpMyAdmin).
- Контейнеры связаны одной сетью `laravel`.
- Для быстрой сборки и удобных команд рекомендуется использовать `make`.

---

## 🏁 Готово!

Теперь проект доступен по адресам:

- 🔗 http://localhost:8080 – Laravel API
- 🔗 http://localhost:8081 – Swagger UI
- 🔗 http://localhost:${PMA_PORT} – phpMyAdmin

---

## 📝 Makefile

```makefile
# Запуск контейнеров
up:
	@docker compose up -d

# Остановка контейнеров
down:
	@docker compose down

# Перезапуск контейнеров
restart:
	@docker compose down && docker compose up -d

# Просмотр логов
logs:
	@docker compose logs -f

# Установка зависимостей
install:
	@docker compose exec app composer install
	@docker compose exec app php artisan key:generate
	@docker compose exec app php artisan migrate --seed

# Установка npm-зависимостей и сборка фронтенда
frontend:
	@docker compose exec app npm install
	@docker compose exec app npm run build

# Запуск тестов
test:
	@docker compose exec app php artisan test --testsuite=Feature
```