<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Dto\Collection;

use App\Core\Collection\CollectionAbstract;
use App\Product\Domain\FileConverter\Dto\Row;

class RowCollection extends CollectionAbstract
{
    public function __construct(Row ...$dtoList)
    {
        $this->items = $dtoList;
    }
}