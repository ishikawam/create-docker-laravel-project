# PROJECT_NAME
# for local development.

dummy:
	@echo PROJECT_NAME Makefile.

setup: pull
	docker compose run --rm php composer install
	docker compose up -d
	docker compose exec mysql bash -c 'MYSQL_PWD=password mysql -uroot -e "DROP USER IF EXISTS root@\"%\"; CREATE USER root@\"%\" IDENTIFIED BY \"password\"; GRANT ALL PRIVILEGES ON *.* TO root@\"%\" WITH GRANT OPTION;"'
	test -f .env || cp -n .env.example .env
	@if [ "`grep '^APP_KEY=.\+' .env`" ]; then \
			echo ; \
		elif [ "`grep '^APP_KEY=' .env`" ]; then \
			docker compose run --rm php php artisan key:generate ; \
		else \
			echo "APP_KEY=" >> .env ; \
			docker compose run --rm php php artisan key:generate ; \
		fi
	@echo
	@echo done.

install: pull
	git submodule update --init
	docker compose run --rm php sh -c "composer install ; php artisan clear-compiled"
	docker compose run --rm node sh -c "npm install ; npm run build"

clear:
	docker compose run --rm php sh -c "\
		composer dump-autoload --optimize ; \
		php artisan clear-compiled ; \
		php artisan view:clear"
	rm -rf storage/app/*
#	rm -rf storage/logs/*
	rm -rf storage/debugbar/*
	git checkout storage
	docker compose run --rm php php artisan cache:clear

ide:
	docker compose run --rm php sh -c "php artisan ide-helper:generate ; php artisan ide-helper:meta ; php artisan ide-helper:models --nowrite"

analyse:
	docker compose run --rm php ./vendor/bin/phpstan analyse

analyse-baseline:
	docker compose run --rm php ./vendor/bin/phpstan analyse --generate-baseline

pull:
	docker compose pull
	docker compose build

up:
	docker compose up

down:
	docker compose down --remove-orphans

php:
	docker compose exec php sh

nginx:
	docker compose exec nginx sh

DATABASE_NAME:
	docker compose exec DATABASE_NAME bash

migrate:
	docker compose exec php php artisan migrate

migrate-rollback:
	docker compose exec php php artisan migrate:rollback

migrate-refresh:
	docker compose exec php php artisan migrate:refresh

log:
	tail -f ./storage/logs/*

dev:
	docker compose run --rm node npm run dev

build:
	docker compose run --rm node npm run build

test:
	docker compose exec php php artisan test

open:
	open http://localhost:NGINX_PORT

# pint
pint:
	docker compose run --rm php ./vendor/bin/pint

#
# tasks
#

sample-inspire:
	docker compose exec php php artisan inspire

destroy:
	@echo "remove DB data. Are you sure? " && read ans && [ $$ans == yes ]
	docker compose down --remove-orphans
	rm -r storage/tmp/local-*/data
