<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Core\Collection\CollectionAbstract;
use App\Product\Domain\FileConverter\Dto\Row;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

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