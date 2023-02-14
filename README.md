# Описание задачи
[Читать](task/readme.txt)

# Сборка проекта
make build

# Запуск проекта
make up

# Команда импорта
docker-compose exec app sh -c "bin/console app:products:convert-file input.csv output.json"
