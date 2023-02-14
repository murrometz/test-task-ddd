<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Command;

class ProductConvertCommand
{
    public function __construct(
        private readonly string $importFile,
        private readonly string $exportFile,
    ) {
    }

    public function getImportFile(): string
    {
        return $this->importFile;
    }

    public function getExportFile(): string
    {
        return $this->exportFile;
    }
}