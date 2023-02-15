build:
	docker-compose build --build-arg UID=`id -u` --build-arg GID=`id -g`
	docker-compose up -d
	docker-compose exec app sh -c "composer install"

start:
	docker-compose up -d

test:
	docker-compose exec app sh -c "php -d vendor/bin/phpunit"
