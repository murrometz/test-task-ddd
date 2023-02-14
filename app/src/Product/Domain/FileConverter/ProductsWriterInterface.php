<?php

namespace App\Product\Domain\FileConverter;

use App\Product\Domain\FileConverter\DTO\Collection\RowDtoCollection;

interface ProductsWriterInterface
{
    public function write(string $path): RowDtoCollection;
    public function supports(string $format): bool;
    public function getSupportedFormat(): string;
}