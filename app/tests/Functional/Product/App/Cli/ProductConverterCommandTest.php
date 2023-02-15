<?php
declare(strict_types=1);

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

final class ProductConverterCommandTest extends WebTestCase
{
    private static string $assetDirectory;
    private static string $tmpFileDirectory;

    public static function setUpBeforeClass(): void
    {
        $kernel = self::bootKernel();
        self::$assetDirectory = $kernel->getProjectDir() . '/tests/Assets/ProductFileConverter/';
        self::$tmpFileDirectory = $kernel->getProjectDir() . '/var/tmp/';
        mkdir(self::$tmpFileDirectory);

        copy(self::$assetDirectory . 'input.csv', self::$tmpFileDirectory . 'input.csv');
        copy(self::$assetDirectory . 'input.csv', self::$tmpFileDirectory . 'input.xml');
    }

    public function testCorrect(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:product:convert-file');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'input.csv',
            'export-file' => 'export-test.json',
        ]);

        $commandTester->assertCommandIsSuccessful($commandTester->getDisplay());

        $this->assertJsonFileEqualsJsonFile(self::$assetDirectory . 'output.json', self::$tmpFileDirectory . 'export-test.json');
    }

    public function testNotValidData(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);
        $fileDirectory = self::$tmpFileDirectory;
        copy($fileDirectory . 'input.csv', $fileDirectory . 'input.xml');

        // File does not exist
        $command = $application->find('app:product:convert-file');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'input2.csv',
            'export-file' => 'export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Файл импорта отсутствует', $output);

        // Export file is not writable
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'input.xml',
            'export-file' => 'testDirectory/export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Невозможно записать в файл для экспорта', $output);

        // Incorrect format
        $result = $commandTester->execute([
            // pass arguments to the helper
            'import-file' => 'input.xml',
            'export-file' => 'export-test.json',
        ]);
        $this->assertSame(Command::FAILURE, $result);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Допустимые форматы входных файлов', $output);
    }
}