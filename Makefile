docker-build:
	docker-compose build

docker-up:
	docker-compose up -d --remove-orphans

docker-down:
	docker-compose down --remove-orphans

init: docker-down docker-build docker-up app-init

up: docker-up
down: docker-down

docker-pull:
	docker-compose pull


app-init: app-composer-install app-assets-install app-wait-db app-migrations

app-composer-install:
	docker exec -d enroll-app-php-fpm composer i

app-assets-install:
	docker-compose run --rm enroll-node yarn install

app-wait-db:
	until docker-compose exec -T enroll-app-db pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

app-migrations:
	docker exec -d enroll-app-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
fix:
	docker exec -it enroll-app-php-fpm vendor/bin/php-cs-fixer fix -v --using-cache=no --allow-risky=yes

bash:
	docker exec -it enroll-app-php-fpm /bin/bash

admin:
	docker exec -it enroll-app-php-fpm bin/console doctrine:fixtures:load -n

watch:
	docker-compose run --rm enroll-node yarn watch