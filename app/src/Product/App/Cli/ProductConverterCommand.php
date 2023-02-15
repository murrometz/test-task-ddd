<?php

namespace App\Product\App\Cli;

use App\Infrastructure\Validator\Exception\ValidationException;
use App\Product\Domain\FileConverter\Command\ProductConvertCommand;
use App\Product\Domain\FileConverter\Command\ProductConvertCommandHandler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $importFilePath = $this->fileDirectory . $input->getArgument('import-file');
        $exportFilePath = $this->fileDirectory . $input->getArgument('export-file');

        $command = new ProductConvertCommand($importFilePath, $exportFilePath);

        try {
            $this->handler->convert($command);
        } catch (ValidationException $exception) {
            $output->writeln('Файлы не прошли проверку:');
            $output->writeln('');
            $output->writeln('Ошибки:');

            foreach ($exception->getViolations() as $violation) {
                $output->writeln($violation->getMessage());
            }

            return Command::FAILURE;
        }


        $output->writeln(PHP_EOL . "Success" . PHP_EOL);


        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('import-file', InputArgument::REQUIRED, 'Path to import file relative to var/tmp/')
            ->addArgument('export-file', InputArgument::REQUIRED, 'Export file path relative to var/tmp/');
    }
}