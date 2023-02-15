<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Converter\Writer;

use App\Product\Domain\FileConverter\Dto\Collection\RowCollection;
use App\Product\Domain\FileConverter\ProductsWriterInterface;
use App\Product\Infrastructure\Converter\ProductsWriterTrait;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class JsonWriter implements ProductsWriterInterface
{
    use ProductsWriterTrait;
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function write(RowCollection $data, string $path): void //TODO
    {
        $data = $this->serializer->serialize($data, 'json', [
            'json_encode_options' => JSON_UNESCAPED_UNICODE
        ]);
        file_put_contents($path, $data);
    }

    public function getSupportedFormat(): string
    {
        return 'json';
    }
}