<?php

namespace App\Product\App\Cli;

use App\Product\Domain\FileConverter\Command\ProductConvertCommand;
use App\Product\Domain\FileConverter\Command\ProductConvertCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:products:convert-file'
)]
class ProductConverterCommand extends Command
{
    public function __construct(private ProductConvertCommandHandler $handler, private string $fileDirectory)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $importFilePath = $input->getArgument('import-file');
        $exportFilePath = $input->getArgument('export-file');

        if (!file_exists($this->fileDirectory . $importFilePath)) {
            $output->writeln('Import file does not exist');
            return Command::FAILURE;
        }
        if (touch($this->fileDirectory . $exportFilePath) === FALSE) {
            $output->writeln('Export file is not writable');
            return Command::FAILURE;
        }

        $command = new ProductConvertCommand($importFilePath, $exportFilePath);

        $violations = $this->handler->validate($command);
        if ($violations->count()) {
            $output->writeln('Файлы не прошли проверку');
            foreach ($violations as $violation) {
                $output->writeln($violation->getMessage());
            }
            return Command::FAILURE;
        }

        $this->handler->convert($command);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('import-file', InputArgument::REQUIRED, 'Path to import file relative to var/tmp/')
            ->addArgument('export-file', InputArgument::REQUIRED, 'Export file path relative to var/tmp/');
    }
}