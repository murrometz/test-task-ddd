build:
	docker-compose build --build-arg UID=`id -u` --build-arg GID=`id -g`

up:
	docker-compose up -d