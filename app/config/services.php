<?php

declare(strict_types=1);

use App\Product\Domain\FileConverter\Command\ProductConvertCommandHandler;
use Symfony\Component\DependencyInjection\Loader\Configurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;


return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters()
        ->set('fileDirectory', '%kernel.project_dir%/var/tmp/')
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
            __DIR__ . '../src/**/Exception.php',
        ])
    ;

    $services->get(ProductConvertCommandHandler::class)
        ->args([
            Configurator\tagged_iterator('app.product.parser'),
            Configurator\tagged_iterator('app.product.writer'),
        ]);

};
