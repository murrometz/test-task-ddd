<?php

namespace App\Product\Infrastructure\Converter;

trait ProductsParserTrait
{
    public function supports(string $format): bool
    {
        return $format === $this->getSupportedFormat();
    }
}