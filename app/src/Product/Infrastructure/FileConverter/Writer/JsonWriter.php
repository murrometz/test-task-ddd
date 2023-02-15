<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\FileConverter\Writer;

use App\Product\Domain\FileConverter\Dto\RowCollection;
use App\Product\Domain\FileConverter\Writer\ProductsWriterInterface;
use App\Product\Infrastructure\FileConverter\ProductsWriterTrait;
use Symfony\Component\Serializer\SerializerInterface;

class JsonWriter implements ProductsWriterInterface
{
    use ProductsWriterTrait;

    public function __construct(private readonly SerializerInterface $serializer)
    {

    }

    public function write(RowCollection $data, string $path): void
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