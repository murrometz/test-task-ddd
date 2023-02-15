<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Converter\Parser;

use App\Product\Domain\FileConverter\Dto\Collection\RowCollection;
use App\Product\Domain\FileConverter\Dto\Row;
use App\Product\Domain\FileConverter\ProductsParserInterface;
use App\Product\Infrastructure\Converter\ProductsParserTrait;
use League\Csv\Reader;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class CsvParser implements ProductsParserInterface
{
    use ProductsParserTrait;
    private DenormalizerInterface $denormalizer;

    private array $headers = ["itemName", "type", "parent", "relation"];
    private string $delimiter = ';';
    private LoggerInterface $logger;

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

        /** @var Row[] $result Результат, который попадет в файл */
        $result = [];
        /** @var Row[] $repository Репозиторий всех элементов по имени */
        $repository = [];

        foreach ($records as $index => $recordRaw) {
            if (!$this->denormalizer->supportsDenormalization($recordRaw, Row::class)) {
                $this->logger->error('Record is not normalizable', ['index' => $index]);
                continue;
            }

            $itemName = $recordRaw['itemName'] = trim($recordRaw['itemName']);

            if (!isset($repository[$itemName])) {
                /** @var Row $record */
                $record = $this->denormalizer->denormalize($recordRaw, Row::class);
                $repository[$record->getItemName()] = $record;
            } else {
                // Если завели из дочернего элемента, то просто заполняем недостающие данные
                $record = $repository[$itemName];
                $record
                    ->setType($recordRaw['type'])
                    ->setRelation($recordRaw['relation'])
                    ->setParent($recordRaw['parent']);
            }

            // Работаем с полем родителя
            if ($record->getParent() === '') {
                $result[] = $record;
            } else {
                if (!isset($repository[$record->getParent()])) {
                    $repository[$record->getParent()] = (new Row())->setItemName($record->getParent())->addChild($record);
                } else {
                    $repository[$record->getParent()]->addChild($record);
                }
            }

            // Работаем с полем Relation
            if ($record->getRelation() !== '') {
                if (!isset($repository[$record->getRelation()])) {
                    $repository[$record->getRelation()] = (new Row())->setItemName($record->getRelation());
                }

                $repository[$itemName]->setChildren($repository[$record->getRelation()]->getChildren());
            }
        }

        return new RowCollection(...$result);
    }

    public function getSupportedFormat(): string
    {
        return 'csv';
    }
}