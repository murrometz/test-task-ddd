build:
	docker-compose build --build-arg UID=`id -u` --build-arg GID=`id -g`

start:
	docker-compose up -d