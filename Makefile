docker-build:
	docker-compose build

docker-up:
	docker-compose up -d --remove-orphans

docker-down:
	docker-compose down --remove-orphans

wait-db:
	until docker-compose exec -T enroll-app-db pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

init: docker-down docker-build docker-up
up: docker-up
down: docker-down

docker-pull:
	docker-compose pull

app-init:
	docker exec -d enroll-app-php-fpm composer i

bash:
	docker exec -it enroll-app-php-fpm /bin/bash

admin:
	docker exec -it enroll-app-php-fpm bin/console doctrine:fixtures:load -n --append
