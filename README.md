# OnlyDigital - Доступные служебные автомобили

**Docker-приложение на Laravel и PostgreSQL**


## Возможности

 **Бэкенд**: Laravel на `http://localhost:8000`  
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
| Swagger     | [http://localhost:8000](http://localhost:8001/) | 8001 | Swagger task api     |

