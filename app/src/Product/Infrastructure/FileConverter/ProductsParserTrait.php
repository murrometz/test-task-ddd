<?php

namespace App\Product\Infrastructure\FileConverter;

trait ProductsParserTrait
{
    public function supports(string $format): bool
    {
        return $format === $this->getSupportedFormat();
    }
}