<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Converter\Parser;

use App\Product\Domain\FileConverter\DTO\Collection\RowDtoCollection;
use App\Product\Domain\FileConverter\ProductsParserInterface;
use App\Product\Infrastructure\Converter\ProductsParserTrait;

class XmlParser implements ProductsParserInterface
{
    use ProductsParserTrait;

    public function parse(string $path, int $list, int $startCol, string $startRow): RowDtoCollection
    {
        return new RowDtoCollection();
    }

    public function getSupportedFormat(): string
    {
        return 'xml';
    }
}