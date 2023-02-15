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

# Описание структуры кода
Код написан в стиле DDD с разбиением по бизнес-доменам.
В коде есть основной бизнес-домен - продукт - основной объект предметной области.
Внутри него есть пока один поддомен - конвертер файлов.
Интерфейсы Reader-ы умеют считывать файлы, интерфейсы Writer - записывать в файл.

```php
├── Core    // Системные компоненты, непривязанные к домену и деталям реализации
├── Infrastructure // Служебные компоненты, привязанные к деталям реализации (БД, сериализаторы, нормализаторы, валидаторы)
└── Product  // Предметная область продукта
    ├── App  // То, что относится к слою приложения - Api, консольные команды и т.д
    │   └── Cli
    │       └── ProductConverterCommand.php
    ├── Domain  // То, что относится к поддоменам
    │   └── FileConverter
    │       ├── Command
    │       ├── Dto    
    │       ├── Exception
    │       ├── Parser
    │       ├── Writer
    └── Infrastructure // Техническая реализация бизнес-логики. Конкретные парсеры и обработчики файлов
        └── FileConverter
```

Внутри поддоменов за бизнес-логику отвечают CommandHandler-ы - обработчики запросов. В них используются исключительно абстракции.

```php
    private readonly ProductsParserInterfaceCollection $readers;
    private readonly ProductsWriterInterfaceCollection $writers;

    public function __construct(
        iterable $readers,
        iterable $writers,
    )
    {
        $readers = $readers instanceof \Traversable ? iterator_to_array($readers) : $readers;
        $this->readers = new ProductsParserInterfaceCollection(...$readers);

        $writers = $writers instanceof \Traversable ? iterator_to_array($writers) : $writers;
        $this->writers = new ProductsWriterInterfaceCollection(...$writers);
    }
```
Реализации абстракций находятся в папке Infrastructure поддомена.

```php
class CsvParser implements ProductsParserInterface
{
    use ProductsParserTrait;
    private DenormalizerInterface $denormalizer;

    private array $headers = ["itemName", "type", "parent", "relation"];
    private string $delimiter = ';';
    private LoggerInterface $logger;

```

В данном конкретном случае все доступные writer-ы и парсеры собираются на основе общего интерфейса и автоматически подставляются в CommandHandler