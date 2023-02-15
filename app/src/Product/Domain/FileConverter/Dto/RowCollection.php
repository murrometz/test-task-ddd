<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Dto;

use App\Core\Collection\AbstractCollection;

class RowCollection extends AbstractCollection
{
    public function __construct(Row ...$dtoList)
    {
        $this->items = $dtoList;
    }
}