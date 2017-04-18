<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 3/12/17
 * Time: 5:48 PM
 */
use App\Common\Library\Providers;
return [
    Providers\CookiesServiceProvider::class,
    Providers\RequestServiceProvider::class,
    Providers\ResponseServiceProvider::class,
    Providers\DispatcherWebServiceProvider::class,
    Providers\RoutingWebServiceProvider::class,
    Providers\SessionServiceProvider::class,
    Providers\DebugBarServiceProvider::class,
    Providers\DatabaseServiceProvider::class,
];