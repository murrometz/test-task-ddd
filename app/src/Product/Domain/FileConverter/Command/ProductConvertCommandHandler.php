<?php

declare(strict_types=1);

namespace App\Product\Domain\FileConverter\Command;

use App\Infrastructure\Validator\Exception\ValidationException;
use App\Product\Domain\FileConverter\Parser\ProductsParserInterfaceCollection;
use App\Product\Domain\FileConverter\Writer\ProductsWriterInterfaceCollection;
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

        if (!file_exists($command->getImportFile())) {
            $result->add(new ConstraintViolation('Файл импорта отсутствует', null, [], null, null, null));
        }

        @touch($command->getExportFile());
        if (!is_writable($command->getExportFile())) {
            $result->add(new ConstraintViolation('Невозможно записать в файл для экспорта', null, [], null, null, null));
        }

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
        $violations = $this->validate($command);
        if ($violations->count()) {
            throw new ValidationException($violations);
        }

        $data = $this->readers->parse($command->getImportFile());
        $this->writers->write($data, $command->getExportFile());

        return true;
    }
}