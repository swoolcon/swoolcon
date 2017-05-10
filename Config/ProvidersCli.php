<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-5-9
 * Time: 上午10:43
 */
use Swoolcon\ServiceProvider;
return [
    ServiceProvider\EventManagerServiceProvider::class,
    ServiceProvider\ConfigServiceProvider::class,
    ServiceProvider\CollectionManagerServiceProvider::class,
    ServiceProvider\ModelsManagerServiceProvider::class,
    ServiceProvider\DataCacheServiceProvider::class,
    ServiceProvider\CryptServiceProvider::class,
    ServiceProvider\FilterServiceProvider::class,
    ServiceProvider\SecurityServiceProvider::class,
    ServiceProvider\ModelsMetadataServiceProvider::class,
    ServiceProvider\LoggerServiceProvider::class,
    ServiceProvider\EscaperServiceProvider::class,
    ServiceProvider\RandomServiceProvider::class,
    ServiceProvider\RedisServiceProvider::class,
    ServiceProvider\ModulesServiceProvider::class,
    ServiceProvider\SwooleStaticPropServiceProvider::class,
    //ServiceProvider\RoutingCliServiceProvider::class,
    ServiceProvider\RouterServiceProvider::class,
    ServiceProvider\DispatcherCliServiceProvider::class,
    ServiceProvider\ModulesCliServiceProvider::class,
];