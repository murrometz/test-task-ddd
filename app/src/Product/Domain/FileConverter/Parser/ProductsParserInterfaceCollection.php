<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Parser;

use App\Core\Collection\AbstractCollection;
use App\Product\Domain\FileConverter\Dto\RowCollection;
use App\Product\Domain\FileConverter\Exception\NoSupportedParserException;

class ProductsParserInterfaceCollection extends AbstractCollection
{
    public function __construct(ProductsParserInterface ...$dtoList)
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

    public function parse(string $importFile): RowCollection
    {
        foreach ($this->items as $item) {
            if ($item->supports(pathinfo($importFile, PATHINFO_EXTENSION))) {
                return $item->parse($importFile);
            }
        }

        throw new NoSupportedParserException();
    }
}