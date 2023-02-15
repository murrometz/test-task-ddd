<?php
declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class ProductConverterCommandTest extends WebTestCase
{
    public function testCorrect(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $fileDirectory = $kernel->getContainer()->getParameter('fileDirectory');

        $command = $application->find('app:product:convert-file');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'task/input.csv',
            'export-file' => 'export-test.json',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $this->assertJsonFileEqualsJsonFile($fileDirectory . 'task/output.json', $fileDirectory . 'export-test.json');
    }

    public function testNotValidData(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        // File does not exist
        $command = $application->find('app:product:convert-file');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'task/input2.csv',
            'export-file' => 'test/export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Файл импорта отсутствует', $output);

        // Export file is not writable
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'task/input.xml',
            'export-file' => 'test/export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Невозможно записать в файл для экспорта', $output);

        // Incorrect format
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'task/input.xml',
            'export-file' => 'export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Допустимые форматы входных файлов', $output);
    }
}