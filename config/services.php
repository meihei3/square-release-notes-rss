<?php
declare(strict_types=1);

/*
 * This file is the entry point to configure your own services.
 * Files in the packages/ subdirectory configure your dependencies.
 *
 * Put parameters here that don't need to change on each machine where the app is deployed
 * https://symfony.com/doc/current/best_practices.html#use-parameters-for-application-configuration
 */

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    // 1) parameters セクション（YAMLの "parameters:" に相当）
    $parameters = $configurator->parameters();
    $parameters->set('square_developer_url', '%env(SQUARE_DEVELOPER_URL)%');

    // 2) services セクション（YAMLの "services:" に相当）
    $services = $configurator->services()
        // _defaults:
        ->defaults()
        ->autowire(true)      // Automatically injects dependencies in your services.
        ->autoconfigure(true) // Automatically registers your services as commands, event subscribers, etc.
        ->bind('$squareDeveloperUrl', '%square_developer_url%')
        ->bind('$publicDirectory', '%kernel.project_dir%/docs')
    ;

    // Twig の Environment サービスを定義
    $services->set(FilesystemLoader::class)
        ->args(['%kernel.project_dir%/src/Lib/Templates']);
    $services->set(Environment::class)
        ->args([
            '$loader' => service(FilesystemLoader::class),
        ]);

    // App\ namespace のクラスをサービスとして自動登録
    $services->load('App\\', dirname(__DIR__).'/src/')
        ->exclude([
            dirname(__DIR__).'/src/DependencyInjection/',
            dirname(__DIR__).'/src/Entity/',
            dirname(__DIR__).'/src/Kernel.php',
        ]);
};
