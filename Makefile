docker-down:
	docker-compose -f docker-compose.yml down --remove-orphans
docker-build:
	docker-compose -f docker-compose.yml build --pull
docker-up:
	docker-compose -f docker-compose.yml up -d --remove-orphans

app-init: app-composer-install app-assets-install app-wait-db app-migrations
test-app-init: test-app-composer-install test-app-wait-db test-app-migrations
app-composer-install:
	docker exec -d enroll-php-fpm composer i
test-app-composer-install:
	docker exec -d test-enroll-php-fpm composer i
app-assets-install:
	docker-compose -f docker-compose.yml run --rm enroll-node yarn install
app-wait-db:
	until docker-compose -f docker-compose.yml exec -T enroll-db pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done
test-app-wait-db:
	until docker-compose -f docker-compose-test.yml exec -T test-enroll-db pg_isready --timeout=0 --dbname=app ; do sleep 1 ; done
app-migrations:
	docker exec -d enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction
test-app-migrations:
	docker exec -d test-enroll-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

init: docker-down docker-build docker-up app-init

########## tests

test-docker-down:
	docker-compose -f docker-compose-test.yml down --remove-orphans
test-docker-build:
	docker-compose -f docker-compose-test.yml build --pull
test-docker-up:
	docker-compose -f docker-compose-test.yml up -d --remove-orphans

init-tests: test-docker-down test-docker-build test-docker-up test-app-init
tests-a:
	docker exec -it test-enroll-php-fpm vendor/bin/codecept run Acceptance -vvv



docker-pull:
	docker-compose -f docker-compose.yml pull

bash:
	docker exec -it enroll-php-fpm /bin/bash

bash-tests:
	docker exec -it test-enroll-php-fpm /bin/bash

users:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n --group=user
data:
	docker exec -it enroll-php-fpm bin/console doctrine:fixtures:load -n
test-data:
	docker exec -it test-enroll-php-fpm bin/console doctrine:fixtures:load -n --group=user

front-lint:
	docker-compose -f docker-compose.yml run --rm enroll-node yarn eslint --ext .js,.jsx assets
fix:
	docker exec -it enroll-php-fpm vendor/bin/php-cs-fixer fix -v --using-cache=no --allow-risky=yes
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