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
	docker exec -d enroll-php-fpm composer i

app-assets-install:
	docker-compose run --rm enroll-node yarn install

app-wait-db:
	until docker-compose exec -T enroll-app-db pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done

app-migrations:
	docker exec -d enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
fix:
	docker exec -it enroll-php-fpm vendor/bin/php-cs-fixer fix -v --using-cache=no --allow-risky=yes
phpstan:
	docker exec -it enroll-php-fpm vendor/bin/phpstan analyse --no-progress --memory-limit 1G

bash:
	docker exec -it enroll-php-fpm /bin/bash

users:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n --group=user

data:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n

watch:
	docker-compose run --rm enroll-node yarn watch
lint:
	docker-compose run --rm enroll-node ./node_modules/.bin/eslint assets


prod-build: prod-build-php prod-build-nginx prod-build-db

prod-build-php:
	docker --log-level=debug build --file=app/.docker/prod/php-fpm.docker --tag ${REGISTRY}:demo-php-fpm-${IMAGE_TAG} app

prod-build-nginx:
	docker --log-level=debug build --file=app/.docker/prod/nginx.docker --tag ${REGISTRY}:demo-nginx-${IMAGE_TAG} app

prod-build-db:
	docker --log-level=debug build --file=app/.docker/prod/db.docker --tag ${REGISTRY}:demo-db-${IMAGE_TAG} app

prod-push: prod-push-nginx prod-push-db prod-push-php

prod-push-db:
	docker push ${REGISTRY}:demo-db-${IMAGE_TAG}
prod-push-php:
	docker push ${REGISTRY}:demo-php-fpm-${IMAGE_TAG}
prod-push-nginx:
	docker push ${REGISTRY}:demo-nginx-${IMAGE_TAG}
