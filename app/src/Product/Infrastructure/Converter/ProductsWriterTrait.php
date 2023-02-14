<?php

namespace App\Product\Infrastructure\Converter;

trait ProductsWriterTrait
{
    public function supports(string $format): bool
    {
        return $format === $this->getSupportedFormat();
    }
}