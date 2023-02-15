<?php

declare(strict_types=1);

namespace App\Infrastructure\Serializer;

use App\Core\Collection\AbstractCollection;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Нормализует и денормализует коллекцию
 */
final class CollectionNormalizer extends AbstractNormalizer
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        assert($this->serializer instanceof Serializer);

        return $this->serializer->denormalize(['items' => $data], $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, AbstractCollection::class, true) && is_array($data) && !isset($data['items']);
    }

    public function normalize($data, string $format = null, array $context = [])
    {
        assert($this->serializer instanceof Serializer);
        assert($data instanceof AbstractCollection);

        return $this->serializer->normalize($data->toArray(), $format, $context);
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof AbstractCollection;
    }
}