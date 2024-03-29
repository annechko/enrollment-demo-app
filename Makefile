docker-down:
	COMPOSE_PROJECT_NAME=enrollment-demo-app docker-compose -f docker-compose.yml down --remove-orphans
docker-build:
	COMPOSE_PROJECT_NAME=enrollment-demo-app docker-compose -f docker-compose.yml build --pull
docker-up:
	COMPOSE_PROJECT_NAME=enrollment-demo-app docker-compose -f docker-compose.yml up -d --remove-orphans

app-init: app-composer-install app-migrations

app-composer-install:
	docker exec -it enroll-php-fpm composer i

app-migrations:
	docker exec -it enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

init: docker-down docker-build create-cache-volume docker-up app-init

########## tests

tests-init: tests-docker-down tests-docker-build create-cache-volume tests-docker-up tests-app-init tests-clean

tests-docker-down:
	COMPOSE_PROJECT_NAME=test-enroll docker-compose -f docker-compose-test.yml down --remove-orphans
tests-docker-build:
	COMPOSE_PROJECT_NAME=test-enroll docker-compose -f docker-compose-test.yml build
tests-docker-up:
	COMPOSE_PROJECT_NAME=test-enroll docker-compose -f docker-compose-test.yml up -d --remove-orphans
create-cache-volume:
	docker volume create enrollment-demo-app_node-cache

tests-app-init: tests-app-composer-install tests-app-migrations

tests-app-migrations:
	docker exec test-enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
tests-app-composer-install:
	docker exec -t test-enroll-php-fpm composer i
tests-clean:
	docker exec -t test-enroll-php-fpm vendor/bin/codecept clean

tests-data:
	docker exec test-enroll-php-fpm bin/console doctrine:fixtures:load -n --group=user
tests-bash:
	docker exec -it test-enroll-php-fpm /bin/bash

tests-a:
	COMPOSE_PROJECT_NAME=test-enroll docker-compose -f docker-compose-test.yml exec -it enroll-php-cli vendor/bin/codecept run Acceptance -vvv -d $(o)

########## end tests


docker-pull:
	COMPOSE_PROJECT_NAME=enrollment-demo-app docker-compose -f docker-compose.yml pull

bash:
	docker exec -it enroll-php-fpm /bin/bash


users:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n --group=user
data:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n

front-lint:
	docker-compose -f docker-compose.yml run --rm enroll-node yarn eslint --ext .js,.jsx assets
fix:
	docker exec -it enroll-php-fpm vendor/bin/php-cs-fixer fix -v --using-cache=no --allow-risky=yes
	docker-compose -f docker-compose.yml run --rm enroll-node yarn eslint --ext .js,.jsx assets --fix
phpstan:
	docker exec -it enroll-php-fpm vendor/bin/phpstan analyse --no-progress --memory-limit 1G


####### prod

prod-build: prod-build-php prod-build-nginx prod-build-db

prod-build-php:
	docker --log-level=debug build --pull --file=app/.docker/prod/php-fpm.docker --tag ${REGISTRY}:demo-php-fpm-${IMAGE_TAG} app

prod-build-nginx:
	docker --log-level=debug build --pull --file=app/.docker/prod/nginx.docker --tag ${REGISTRY}:demo-nginx-${IMAGE_TAG} app

prod-build-db:
	docker --log-level=debug build --pull --file=app/.docker/prod/db.docker --tag ${REGISTRY}:demo-db-${IMAGE_TAG} app

prod-push: prod-push-nginx prod-push-db prod-push-php

prod-push-db:
	docker push ${REGISTRY}:demo-db-${IMAGE_TAG}
prod-push-php:
	docker push ${REGISTRY}:demo-php-fpm-${IMAGE_TAG}
prod-push-nginx:
	docker push ${REGISTRY}:demo-nginx-${IMAGE_TAG}
prod-build-push: prod-build prod-push

deploy:
	ssh -o StrictHostKeyChecking=no -t ${PROD_HOST} 'cd ${HOME_DIR} && sudo rm -rf docker-compose.yml'
	scp -o StrictHostKeyChecking=no docker-compose-prod.yml ${PROD_HOST}:${HOME_DIR}docker-compose.yml
	ssh -o StrictHostKeyChecking=no -t ${PROD_HOST} 'cd ${HOME_DIR} && sudo docker compose pull'
	ssh -o StrictHostKeyChecking=no -t ${PROD_HOST} 'cd ${HOME_DIR} && sudo docker compose up --build --remove-orphans -d'
	ssh -o StrictHostKeyChecking=no -t ${PROD_HOST} 'cd ${HOME_DIR} && sudo docker compose run --rm enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction'

################## CI experiments
ci-code-style-check-php:
	docker exec -t enroll-php-fpm vendor/bin/php-cs-fixer fix --dry-run -v --using-cache=no --allow-risky=yes
ci-code-style-check-js:
	docker-compose -f docker-compose-ci.yml run --rm enroll-node yarn eslint --ext .js,.jsx assets
ci-code-style-check: ci-code-style-check-php ci-code-style-check-js

ci-validate-composer:
	docker exec -t enroll-php-fpm composer validate

ci-build:
	docker-compose -f docker-compose-ci.yml build

ci-push:
	docker push ${REGISTRY}:demo-php-fpm-${IMAGE_TAG}
	docker push ${REGISTRY}:demo-nginx-${IMAGE_TAG}

ci-up:
	docker-compose -f docker-compose-ci.yml up -d
ci-init:
	docker exec -t enroll-php-fpm composer i --prefer-dist
	docker-compose -f docker-compose-ci.yml run --rm enroll-node yarn install
	docker-compose -f docker-compose-ci.yml run --rm enroll-node yarn build
ci-db:
	docker exec -t enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

ci-tests-a:
	docker exec -t enroll-php-cli vendor/bin/codecept run Acceptance --env ci -vvv
