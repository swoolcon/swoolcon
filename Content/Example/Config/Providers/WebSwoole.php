<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 3/12/17
 * Time: 5:49 PM
 */
use Swoolcon\ServiceProvider;
return [
    ServiceProvider\SwooleCookieServiceProvider::class,
    ServiceProvider\SwooleCookiesServiceProvider::class,
    ServiceProvider\SwooleRequestServiceProvider::class,
    ServiceProvider\SwooleResponseServiceProvider::class,
    ServiceProvider\DispatcherWebServiceProvider::class,
    ServiceProvider\RoutingWebServiceProvider::class,
    ServiceProvider\SwooleSessionServiceProvider::class,
    ServiceProvider\SwooleWebDatabaseServiceProvider::class,
    ServiceProvider\SwooleStaticPropServiceProvider::class
];