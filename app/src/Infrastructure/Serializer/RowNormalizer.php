<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Product\Domain\FileConverter\Dto\Row;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

/**
 * Нормализатор полей позиции для выгрузки списка товаров в Json
 *
 * 1. Переопределяет название Parent у всех child
 * 2. Заменяет parent на null, если он = пустой строке
 *
 * @see \App\Product\App\Cli\ProductConverterCommand
 */
final class RowNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($data, string $format = null, array $context = [])
    {
        assert($this->serializer instanceof Serializer);
        assert($data instanceof Row);

        return $this->serializer->normalize(
            [
                'itemName' => $data->getItemName(),
                'parent' => $data->getParent() ?: null,
                'children' => $this->serializer->normalize(
                    array_map(fn (Row $item) => (clone $item)->setParent($data->getItemName()), $data->getChildren()->toArray())
                )
            ],
            $format,
            $context
        );
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Row;
    }
}