<?php

namespace App\Product\Domain\FileConverter;

use App\Product\Domain\FileConverter\Dto\Collection\RowCollection;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.product.writer')]
interface ProductsWriterInterface
{
    public function write(RowCollection $data, string $path): void;
    public function supports(string $format): bool;
    public function getSupportedFormat(): string;
}