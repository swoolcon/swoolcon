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
    ServiceProvider\UrlResolverServiceProvider::class,
    ServiceProvider\CollectionManagerServiceProvider::class,
    ServiceProvider\ModelsManagerServiceProvider::class,
    ServiceProvider\DataCacheServiceProvider::class,
    ServiceProvider\ViewCacheServiceProvider::class,
    ServiceProvider\VoltTemplateEngineServiceProvider::class,
    ServiceProvider\ViewServiceProvider::class,
    ServiceProvider\PhpTemplateEngineServiceProvider::class,
    ServiceProvider\FlashSessionServiceProvider::class,
    ServiceProvider\CryptServiceProvider::class,
    ServiceProvider\TagServiceProvider::class,
    ServiceProvider\FilterServiceProvider::class,
    ServiceProvider\SecurityServiceProvider::class,
    //ServiceProvider\ModelsCacheServiceProvider::class,
    ServiceProvider\ModelsMetadataServiceProvider::class,
    ServiceProvider\LoggerServiceProvider::class,
    ServiceProvider\EscaperServiceProvider::class,
    ServiceProvider\RandomServiceProvider::class,
    ServiceProvider\RedisServiceProvider::class,
    ServiceProvider\ModulesServiceProvider::class,

    ServiceProvider\SwooleCookieServiceProvider::class,
    ServiceProvider\SwooleCookiesServiceProvider::class,
    ServiceProvider\SwooleRequestServiceProvider::class,
    ServiceProvider\SwooleResponseServiceProvider::class,
    ServiceProvider\DispatcherWebServiceProvider::class,
    //ServiceProvider\RoutingWebServiceProvider::class,
    ServiceProvider\RouterServiceProvider::class,

    ServiceProvider\SwooleSessionServiceProvider::class,
    ServiceProvider\SwooleWebDatabaseServiceProvider::class,
    ServiceProvider\SwooleStaticPropServiceProvider::class
];