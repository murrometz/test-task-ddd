<?php

declare(strict_types=1);

use App\Product\Domain\FileConverter\Command\ProductConvertCommandHandler;
use App\Product\Domain\FileConverter\ProductsParserInterface;
use App\Product\Domain\FileConverter\ProductsWriterInterface;
use Doctrine\Migrations\Version\DbalMigrationFactory;
use Doctrine\Persistence\ObjectManager;
use IsPlan\Auth\Domain\Company\Department\Command\Handler\SearchDepartmentCommandHandler;
use IsPlan\Auth\Domain\Role\RoleRepository;
use IsPlan\HealthCheck\Domain\Command\HealthCheckCommandHandler;
use IsPlan\HealthCheck\Infrastructure\Checker\CamundaChecker;
use IsPlan\HealthCheck\Infrastructure\Checker\DoctrineChecker;
use IsPlan\HealthCheck\Infrastructure\Checker\MessengerChecker;
use IsPlan\HealthCheck\Infrastructure\Checker\RedisChecker;
use IsPlan\Infrastructure\Doctrine\Fixtures\FileFixtures;
use IsPlan\Infrastructure\Doctrine\Migration\MigrationFactoryDecorator;
use IsPlan\Infrastructure\Fs\File\FileStorage;
use IsPlan\Infrastructure\Import\BudgetItemResolver;
use IsPlan\Infrastructure\Import\CompanyIdMapping;
use IsPlan\Infrastructure\Import\CompanyIdResolver;
use IsPlan\Infrastructure\Intergration\Kesl\KeslClientService;
use IsPlan\Infrastructure\Intergration\Keycloak\KeycloakClientService;
use IsPlan\Infrastructure\Intergration\Keycloak\KeycloakCredentials;
use IsPlan\Infrastructure\Logger\ClientIpProcessor;
use IsPlan\Infrastructure\Logger\UniqueRequestIdProcessor;
use IsPlan\Infrastructure\Logger\UserIdProcessor;
use IsPlan\Infrastructure\Security\Authenticator;
use IsPlan\Infrastructure\Security\ExternalAuthenticator;
use IsPlan\Infrastructure\Security\ExternalServiceAuthenticator;
use IsPlan\Infrastructure\Security\ServiceUserProvider;
use IsPlan\Infrastructure\UniqueProcessId;
use IsPlan\ProductionNeed\Domain\FileInspection\Command\InspectFilesCommandHandler;
use IsPlan\ProductionNeed\Domain\Lot\Command\Handler\WithdrawLotCommandHandler;
use IsPlan\ProductionNeed\Domain\Lot\Command\SendLotToReconciliationCommandHandler;
use IsPlan\ProductionNeed\Domain\ProductionNeed\Command\ReconcileProductionNeedCommandHandler;
use IsPlan\ProductionNeed\Domain\ProductionNeed\Command\WithdrawProductionNeedCommandHandler;
use IsPlan\ProductionNeed\Infrastructure\Intergration\Lot\LotBpmProcessManager;
use IsPlan\Reconciliation\Domain\ReconciliationTask\Command\UpdateStatusReconciliationTaskCommandHandler;
use IsPlan\Reconciliation\Infrastructure\Integration\ReconciliationTask\CamundaProductionNeedBPMProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;


return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters()
        ->set('fileDirectory', '%kernel.project_dir%/var/tmp')
    ;

    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->bind('$fileDirectory', '%fileDirectory%')
    ;
    $services->load('App\\', __DIR__ . '/../src/*')
        ->exclude([
            __DIR__ . '../src/DependencyInjection/',
            __DIR__ . '../src/Tests/',
            __DIR__ . '../src/Entity/',
            __DIR__ . '../src/Kernel.php',
        ])
    ;

    $services->instanceof(ProductsParserInterface::class)
        ->tag('app.product.parser')
    ;

    $services->instanceof(ProductsWriterInterface::class)
        ->tag('app.product.writer')
    ;

    $services->get(ProductConvertCommandHandler::class)
        ->args([
            Configurator\tagged_iterator('app.product.parser'),
            Configurator\tagged_iterator('app.product.writer'),
        ]);
};
