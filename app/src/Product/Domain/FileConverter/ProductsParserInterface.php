<?php

namespace App\Product\Domain\FileConverter;

use App\Product\Domain\FileConverter\DTO\Collection\RowDtoCollection;

interface ProductsParserInterface
{
    public function parse(string $path, int $list, int $startCol, string $startRow): RowDtoCollection;
    public function supports(string $format): bool;
    public function getSupportedFormat(): string;
}