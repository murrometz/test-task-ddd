<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\DTO\Collection;

use App\Core\Collection\CollectionAbstract;
use App\Product\Domain\FileConverter\DTO\RowDto;

class RowDtoCollection extends CollectionAbstract
{
    public function __construct(RowDto ...$dtoList)
    {
        $this->items = $dtoList;
    }
}