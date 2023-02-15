<?php

namespace App\Product\Domain\FileConverter;

use App\Product\Domain\FileConverter\Dto\Collection\RowCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.product.parser')]
interface ProductsParserInterface
{
    public function parse(string $path, int $listNumber = 0, int $startCol = 0, int $startRow = 0): RowCollection;
    public function supports(string $format): bool;
    public function getSupportedFormat(): string;
}