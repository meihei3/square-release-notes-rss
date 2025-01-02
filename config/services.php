<?php

/*
 * This file is the entry point to configure your own services.
 * Files in the packages/ subdirectory configure your dependencies.
 *
 * Put parameters here that don't need to change on each machine where the app is deployed
 * https://symfony.com/doc/current/best_practices.html#use-parameters-for-application-configuration
 */

use App\Lib\SquareReleaseNotesFetchClient;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

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
        ->bind('$publicDirectory', '%kernel.project_dir%/public')
    ;

    // App\ namespace のクラスをサービスとして自動登録
    $services->load('App\\', dirname(__DIR__).'/src/')
        ->exclude([
            dirname(__DIR__).'/src/DependencyInjection/',
            dirname(__DIR__).'/src/Entity/',
            dirname(__DIR__).'/src/Kernel.php',
        ]);
};
