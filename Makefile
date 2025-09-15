# Makefile for sheepyTZ
# Usage: make <target>
# Common targets: help, up, down, logs, bash, perm, key, migrate, seed, clear, fresh, resetdb
# Dynamic helpers:
#   make composer-install        -> runs `composer install` inside app container
#   make artisan-migrate         -> runs `php artisan migrate` inside app container
#   make composer-%              -> pass any composer command (e.g. make composer-update)
#   make artisan-%               -> pass any artisan command (e.g. make artisan-route:list)

SHELL := /bin/bash
COMPOSE := docker compose
APP_CONTAINER ?= app

.DEFAULT_GOAL := help

.PHONY: help up down build logs bash perm key migrate seed clear fresh \
        composer-% artisan-% test-db migrate-test resetdb check-overdue check-overdue-dry

help:
	@echo "sheepyTZ — available targets:"
	@echo
	@echo "  up                      Build & start containers (foreground)"
	@echo "  down                    Stop & remove containers"
	@echo "  build                   Build images (without starting)"
	@echo "  logs                    Tail service logs (last 200 lines)"
	@echo "  bash                    Open a bash shell in the app container"
	@echo "  perm                    Fix permissions for storage and cache"
	@echo "  key                     Generate APP key"
	@echo "  migrate                 Run migrations"
	@echo "  seed                    Run db seeders"
	@echo "  clear                   Clear caches and regenerate swagger"
	@echo "  fresh                   Migrate:fresh --seed"
	@echo "  resetdb                 migrate:fresh --seed && phpunit (if configured)"
	@echo "  test-db                 Create test database inside DB container (psql)"
	@echo "  migrate-test            Run migrations for testing environment"
	@echo "  check-overdue           Run tasks:check-overdue"
	@echo "  check-overdue-dry       Run tasks:check-overdue --dry-run"
	@echo
	@echo "Dynamic commands:"
	@echo "  make composer-<cmd>     Runs composer <cmd> inside app (e.g. make composer-install)"
	@echo "  make artisan-<cmd>      Runs php artisan <cmd> inside app (e.g. make artisan-cache:clear)"
	@echo
	@echo "Override APP container: make migrate APP_CONTAINER=myapp"

up:
	$(COMPOSE) up --build

down:
	$(COMPOSE) down

build:
	$(COMPOSE) build

logs:
	$(COMPOSE) logs -f --tail=200

bash:
	$(COMPOSE) exec $(APP_CONTAINER) bash

perm:
	$(COMPOSE) exec $(APP_CONTAINER) bash -lc "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

key:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan key:generate

migrate:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan migrate

seed:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan db:seed

clear:
	@# note: php command runs on host in original file — run inside container for consistency
	$(COMPOSE) exec $(APP_CONTAINER) bash -lc "php artisan config:clear && php artisan cache:clear && CACHE_DRIVER=file php artisan l5-swagger:generate"

fresh:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan migrate:fresh --seed

resetdb:
	$(COMPOSE) exec $(APP_CONTAINER) bash -lc "php artisan migrate:fresh --seed && php artisan test || true"

composer-%:
	$(COMPOSE) exec $(APP_CONTAINER) composer $*

artisan-%:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan $*

test-db:
	@echo "Creating test DB inside DB container (psql). Adjust if you're using MySQL."
	docker exec -it db psql -U app -d app -c "CREATE DATABASE test OWNER app;" || true

migrate-test:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan migrate --env=testing

check-overdue-dry:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan tasks:check-overdue --dry-run

check-overdue:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan tasks:check-overdue

test:
	$(COMPOSE) exec $(APP_CONTAINER) php artisan test tests/Feature/Api || true


