#!/usr/bin/env php
<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-21
 * Time: 下午5:00
 */
require __DIR__ . '/Bootstrap/Autoloader.php';

// Create the Application
$console = new \Swoolcon\Application\Command();
$console->setRouter(function () {
    $router = new Phalcon\Cli\Router();
    $router->setDefaultTask('main');
    $router->setDefaultAction('main');
    return $router;
})->setModules(require config_path('ModuleCli.php'))
    ->setServiceProviderList(require config_path('ProvidersCli.php'))
    ->setDefaultModule('server')->register();


/** @var \Swoolcon\Cli\Console $app */
$app = $console->getApplication();
$app->setArgs($argv, $argc);

try {
    $console->run();
} catch (Exception $e) {
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
}
