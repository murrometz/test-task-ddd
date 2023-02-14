<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Converter\Writer;

use App\Product\Domain\FileConverter\DTO\Collection\RowDtoCollection;
use App\Product\Domain\FileConverter\ProductsWriterInterface;
use App\Product\Infrastructure\Converter\ProductsWriterTrait;

class JsonWriter implements ProductsWriterInterface
{
    use ProductsWriterTrait;

    public function write(string $path): RowDtoCollection
    {
        return new RowDtoCollection();
    }

    public function getSupportedFormat(): string
    {
        return 'json';
    }
}