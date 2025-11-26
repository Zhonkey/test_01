ROOT_DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))
build:
	docker compose build
style:
	./vendor/bin/phpinsights --no-interaction
up: build
	docker compose up -d
	docker compose exec -T queue sh -c "./init --env=Docker --overwrite=y"
	docker compose exec -T queue sh -c "composer install --no-interaction --prefer-dist -o"
	docker compose exec -T queue sh -c "cat /app/.env.template | envsubst > /app/.env"
	docker compose exec -T queue sh -c "./yii migrate"
sh: up
	docker compose exec queue sh -c "/bin/bash"
db: up
	docker compose exec db mariadb -u mariadb -pmariadb mariadb
down:
	docker compose down --remove-orphans