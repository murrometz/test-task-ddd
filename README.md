# Описание задачи
[Читать](task/readme.txt)

# Сборка проекта
```shell
make build
```

# Запуск проекта
```shell
make start
```

# Команда импорта
```shell
docker-compose exec app sh -c "bin/console app:products:convert-file input.csv my-output.json"
```

Результат команды в файле [app/var/tmp/my-output.json](app/var/tmp/my-output.json)

# Автотесты
```shell
make test
```