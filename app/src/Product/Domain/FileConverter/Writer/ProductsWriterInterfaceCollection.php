<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Writer;

use App\Core\Collection\AbstractCollection;
use App\Product\Domain\FileConverter\Dto\RowCollection;
use App\Product\Domain\FileConverter\Exception\NoSupportedWriterException;

class ProductsWriterInterfaceCollection extends AbstractCollection
{
    public function __construct(ProductsWriterInterface ...$dtoList)
    {
        $this->items = $dtoList;
    }

    public function supports(string $format): bool
    {
        foreach ($this->items as $item) {
            if ($item->supports($format)) {
                return true;
            }
        }
        return false;
    }

    /** @return string[] */
    public function getAllowedFormats(): array
    {
        return array_map(fn ($item) => $item->getSupportedFormat(), $this->items);
    }

    public function write(RowCollection $data, string $exportFile): void
    {
        foreach ($this->items as $item) {
            if ($item->supports(pathinfo($exportFile, PATHINFO_EXTENSION))) {
                $item->write($data, $exportFile);
                return;
            }
        }

        throw new NoSupportedWriterException();
    }
}