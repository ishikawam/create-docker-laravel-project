# PROJECT_NAME
# for local development.

dummy:
	@echo PROJECT_NAME Makefile.

setup:
	docker-compose build
	docker-compose run php composer install
	docker-compose run --rm mysql bash -c 'MYSQL_PWD=password mysql -uroot -e "DROP USER IF EXISTS root@\"%\"; CREATE USER root@\"%\" IDENTIFIED BY \"password\"; GRANT ALL PRIVILEGES ON *.* TO root@\"%\" WITH GRANT OPTION;"'
	test -f .env || cp -n .env.example .env
	@if [ "`grep '^APP_KEY=.\+' .env`" ]; then \
			echo ; \
		elif [ "`grep '^APP_KEY=' .env`" ]; then \
			docker-compose run php php artisan key:generate ; \
		else \
			echo "APP_KEY=" >> .env ; \
			docker-compose run php php artisan key:generate ; \
		fi
	@echo
	@echo done.

install:
	git submodule update --init
	docker-compose build
	docker-compose run php composer install
	docker-compose run php php artisan clear-compiled
	make ide
	npm install
	npm run build

clear:
	docker-compose run php sh -c "\
		composer dump-autoload --optimize ; \
		php artisan clear-compiled ; \
		php artisan view:clear"
	rm -rf storage/app/*
#	rm -rf storage/logs/*
	rm -rf storage/debugbar/*
	git checkout storage
	docker-compose run php php artisan cache:clear

ide:
	docker-compose exec php sh -c "php artisan ide-helper:generate ; php artisan ide-helper:meta ; php artisan ide-helper:models --nowrite"

up:
	docker-compose up -d --build

down:
	docker-compose down

php:
	docker-compose exec php sh

nginx:
	docker-compose exec nginx sh

DATABASE_NAME:
	docker-compose exec DATABASE_NAME bash

migrate:
	docker-compose exec php php artisan migrate

migrate-rollback:
	docker-compose exec php php artisan migrate:rollback

migrate-refresh:
	docker-compose run php php artisan migrate:refresh

logs:
	docker-compose logs -f

log:
	tail -f ./storage/logs/*

dev:
	npm run dev

build:
	npm run build

test:
	docker-compose exec php php artisan test

open:
	open http://localhost:NGINX_PORT

# cs-fixer
fix-diff:
	docker-compose run php ./vendor/bin/php-cs-fixer fix --dry-run --diff -v

fix-v:
	docker-compose run php ./vendor/bin/php-cs-fixer fix --dry-run -v

fix:
	docker-compose run php ./vendor/bin/php-cs-fixer fix -v

#
# tasks
#

sample-inspire:
	docker-compose exec php php artisan inspire