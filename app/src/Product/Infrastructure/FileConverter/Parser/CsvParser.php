<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\FileConverter\Parser;

use App\Product\Domain\FileConverter\Dto\Row;
use App\Product\Domain\FileConverter\Dto\RowCollection;
use App\Product\Domain\FileConverter\Parser\ProductsParserInterface;
use App\Product\Infrastructure\FileConverter\ProductsParserTrait;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CsvParser implements ProductsParserInterface
{
    use ProductsParserTrait;

    private DenormalizerInterface $denormalizer;
    private LoggerInterface $logger;

    private array $headers = ["itemName", "type", "parent", "relation"];
    private string $delimiter = ';';

    public function __construct(DenormalizerInterface $denormalizer, LoggerInterface $logger)
    {
        $this->denormalizer = $denormalizer;
        $this->logger = $logger;
    }

    public function parse(string $path, int $listNumber = 0, int $startCol = 0, int $startRow = 0): RowCollection
    {
        $reader = Reader::createFromPath($path, 'r');
        $reader->setDelimiter($this->delimiter);
        $reader->setHeaderOffset(0);

        $records = $reader->getRecords($this->headers);

        /** @var Row[] $result Результат, который попадет в файл - дерево товаров */
        $result = [];
        /** @var Row[] $repository Репозиторий всех элементов - общий справочник */
        $repository = [];

        foreach ($records as $index => $recordRaw) {
            if (!$this->denormalizer->supportsDenormalization($recordRaw, Row::class)) {
                $this->logger->error('Record is not normalizable', ['index' => $index]);
                continue;
            }

            // Добавляем запись в репозиторий
            $record = $this->pushDataToRepository($recordRaw, $repository);

            // Работаем с полем Parent
            $this->processParentField($record, $result, $repository);

            // Работаем с полем Relation
            $this->processRelationField($record, $result, $repository);
        }

        return new RowCollection(...$result);
    }

    public function getSupportedFormat(): string
    {
        return 'csv';
    }

    /**
     * @return Row Добавленный элемент
     */
    private function pushDataToRepository(array $recordRaw, array &$repository): Row
    {
        $itemName = $recordRaw['itemName'];

        if (!isset($repository[$itemName])) {
            /** @var Row $record */
            $record = $this->denormalizer->denormalize($recordRaw, Row::class);
            $repository[$record->getItemName()] = $record;
        } else {
            // Если завели из дочернего элемента, то просто заполняем недостающие данные
            /** @var Row $record */
            $record = $repository[$itemName];
            $record
                ->setType($recordRaw['type'])
                ->setRelation($recordRaw['relation'])
                ->setParent($recordRaw['parent']);
        }
        return $record;
    }

    private function processParentField(Row $record, array &$result, array &$repository)
    {
        if ($record->getParent() === '') {
            $result[] = $record;
        } else {
            if (!isset($repository[$record->getParent()])) {
                $repository[$record->getParent()] = (new Row())->setItemName($record->getParent())->addChild($record);
            } else {
                $repository[$record->getParent()]->addChild($record);
            }
        }
    }
    private function processRelationField(Row $record, array &$result, array &$repository)
    {
        if ($record->getRelation() !== '') {
            if (!isset($repository[$record->getRelation()])) {
                $repository[$record->getRelation()] = (new Row())->setItemName($record->getRelation());
            }

            $repository[$record->getItemName()]->setChildren($repository[$record->getRelation()]->getChildren());
        }
    }
}