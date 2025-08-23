SHELL := /bin/bash

up:
	docker compose up -d --build

down:
	docker compose down

logs:
	docker compose logs -f --tail=200

bash:
	docker compose exec app bash

perm:
	docker compose exec app bash -lc "chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache"

key:
	docker compose exec app php artisan key:generate

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

swagger:
	docker compose exec app php artisan l5-swagger:generate

fresh:
	docker compose exec app php artisan migrate:fresh --seed

composer-%:
	docker compose exec app composer $*

artisan-%:
	docker compose exec app php artisan $*
