<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Command;

use App\Product\Domain\FileConverter\ProductsParserInterfaceCollection;
use App\Product\Domain\FileConverter\ProductsProcessorInterface;
use App\Product\Domain\FileConverter\ProductsWriterInterfaceCollection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductConvertCommandHandler
{
    private readonly ProductsParserInterfaceCollection $readers;
    private readonly ProductsWriterInterfaceCollection $writers;

    public function __construct(
        iterable $readers,
        iterable $writers,
    )
    {
        $readers = $readers instanceof \Traversable ? iterator_to_array($readers) : $readers;
        $this->readers = new ProductsParserInterfaceCollection(...$readers);

        $writers = $writers instanceof \Traversable ? iterator_to_array($writers) : $writers;
        $this->writers = new ProductsWriterInterfaceCollection(...$writers);
    }

    public function validate(ProductConvertCommand $command): ConstraintViolationListInterface
    {
        $importFileExtension = pathinfo($command->getImportFile(), PATHINFO_EXTENSION);
        $exportFileExtension = pathinfo($command->getExportFile(), PATHINFO_EXTENSION);
        $result = new ConstraintViolationList();

        if (!$this->readers->supports($importFileExtension)) {
            $result->add(new ConstraintViolation('Допустимые форматы входных файлов: ' . implode(', ', $this->readers->getAllowedFormats()), null, [], null, null, null));
        }
        if (!$this->writers->supports($exportFileExtension)) {
            $result->add(new ConstraintViolation('Допустимые форматы результата: ' . implode(', ', $this->writers->getAllowedFormats()), null, [], null, null, null));
        }

        return $result;
    }

    public function convert(ProductConvertCommand $command): bool
    {
        $fileExtension = pathinfo($command->getImportFile(), PATHINFO_EXTENSION);
        $result = new ConstraintViolationList();
        if (!$this->readers->supports($fileExtension)) {
            $result->add(new ConstraintViolation('Допустимые форматы входных файлов: ' . implode(', ', $this->readers->getAllowedFormats()), null, [], null, null, null));
        }
        if (!$this->writers->supports($command->getExportFile())) {
            $result->add(new ConstraintViolation('Допустимые форматы результата: ' . implode(', ', $this->writers->getAllowedFormats()), null, [], null, null, null));
        }

        $data = $this->readers->parse($command->getImportFile());
        $result = $this->writers->write($data, $command->getExportFile());

        return true;
    }
}