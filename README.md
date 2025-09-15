# sheepyTZ

A Dockerized Laravel application with MySQL, Redis, Swagger, and phpMyAdmin support.

---

## Features

- **Backend:** Laravel API at `http://localhost:8080`
- **REST API Docs:** Swagger UI at `http://localhost:8081`
- **Database:** MySQL 8 with persistent storage
- **Admin Interface:** phpMyAdmin at `http://localhost:8082` .. `http://localhost:${PMA_PORT}`
- **Caching:** Redis 7 for queues and sessions

---

## Requirements

- Docker & Docker Compose
- Make (for convenient commands)
- PHP 8.2+, Composer (for local commands)
- Node.js (if frontend is needed)

---

## Installation & Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/your-repo/sheepyTZ
   cd sheepyTZ
   cp .env.example .env
   ```

2. Start containers:
   ```bash
   make up
   ```

3. Install Laravel dependencies:
   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```

4. (Optional) Install and build frontend:
   ```bash
   docker compose exec app npm install
   docker compose exec app npm run build
   ```

5. Stop containers:
   ```bash
   make down
   ```

---

## Key Services

| Service       | URL / Host                | Port         | Description          |
|---------------|---------------------------|--------------|----------------------|
| Backend       | http://localhost:8080     | 8080         | Laravel API          |
| MySQL         | localhost                 | 3306         | Database             |
| Swagger       | http://localhost:8081     | 8081         | API Documentation    |
| phpMyAdmin    | http://localhost:8082     | 8082         | Database UI          |
| Redis         | localhost:6379            | 6379         | Cache / Queues       |

---

## Testing (Feature Tests)

1. Create a test database:
   ```bash
   docker compose exec db mysql -u root -p -e "CREATE DATABASE sheepyTZ_test;"
   ```

2. Copy `.env` to `.env.testing` and set:
   ```bash
   DB_DATABASE=sheepyTZ_test
   ```

3. Run tests:
   ```bash
   docker compose exec app php artisan test --testsuite=Feature 
   ```

Tests run on the test database and do not affect production data.

---

## Useful Commands

| Command                                      | Description                  |
|----------------------------------------------|------------------------------|
| `make up`                                    | Start containers             |
| `make down`                                  | Stop containers              |
| `make logs`                                  | View logs (last 200 lines)   |
| `make migrate`                               | Run migrations               |
| `make test-db`                               | Create test database         |
| `make help`                                  | All list commands            |
| `make test`                                  | Run test           |

See `Makefile` for more commands (e.g., `artisan-`, `composer-`).

---

## Project Structure

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
  ...
```

---

## Notes

- Environment variables are in `.env` (DB, Redis, Swagger, phpMyAdmin).
- Containers use the `laravel` network.
- Use `make` for quick setup and commands.

---

## Ready!

Access the project at:

- 🔗 http://localhost:8080 – Laravel
- 🔗 http://localhost:8081 – Swagger UI
- 🔗 http://localhost:8082/ – phpMyAdmin

---

### Key Improvements:
1. **Conciseness:** Removed redundant sections (e.g., duplicate Makefile content) and streamlined instructions.
2. **Clarity:** Organized content with clear headings and tables, making it easier to navigate.
3. **Consistency:** Aligned command examples with the provided `Makefile`.
4. **Professional Tone:** Simplified language and removed overly detailed explanations unless critical.

You can copy this text into your `README.md` and adjust the repository URL or other specifics as needed. Let me know if you'd like further refinements!