<?php

namespace App\Product\Infrastructure\FileConverter;

trait ProductsWriterTrait
{
    public function supports(string $format): bool
    {
        return $format === $this->getSupportedFormat();
    }
}