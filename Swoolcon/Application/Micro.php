<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-28
 * Time: 上午10:13
 */

namespace Swoolcon\Application;

use Phalcon\Di;
use Swoolcon\Application;
use Swoolcon\Exception;
use Swoolcon\ServiceProvider;
use Swoole\Http\Response as SwooleResponse;
use Swoole\Http\Request as SwooleRequest;

class Micro extends Application
{
    /**
     * @var SwooleRequest
     */
    protected $swooleRequest;

    /**
     * @var SwooleResponse
     */
    protected $swooleResponse;

    /**
     *
     */
    public function run()
    {
        $this->app->handle();
    }

    protected function registerProviders()
    {
        //注入服务,暂时不用，留以后用


    }

    /**
     * 计划把服务分到两个di中，目前暂时不这么做，先跑起来
     * @return $this
     * @throws Exception
     */
    public function register()
    {

        $di = new Di();
        $this->setDI($di);
        $di->setShared('bootstrap', $this);
        $this->app = new \Phalcon\Mvc\Micro($di);

        //swoole request response 注入到di
        if (!$this->swooleRequest) throw new Exception('swoole request is not empty');
        if (!$this->swooleResponse) throw new Exception('swoole response is not empty');
        $di->setShared('swooleRequest', $this->swooleRequest);
        $di->setShared('swooleResponse', $this->swooleResponse);
        $di->setShared('router', new \Swoolcon\Mvc\Router());

        //这一部分放到config 文件夹里面
        $this->initializeServiceProviders([
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
            ServiceProvider\ModelsMetadataServiceProvider::class,
            ServiceProvider\LoggerServiceProvider::class,
            ServiceProvider\EscaperServiceProvider::class,
            ServiceProvider\RandomServiceProvider::class,
            ServiceProvider\RedisServiceProvider::class,

            ServiceProvider\SwooleCookieServiceProvider::class,
            ServiceProvider\SwooleCookiesServiceProvider::class,
            ServiceProvider\SwooleRequestServiceProvider::class,
            ServiceProvider\SwooleResponseServiceProvider::class,
            ServiceProvider\DispatcherWebServiceProvider::class,
            ServiceProvider\SwooleSessionServiceProvider::class,
            ServiceProvider\SwooleWebDatabaseServiceProvider::class,
            ServiceProvider\SwooleStaticPropServiceProvider::class
        ], $di);

        return $this;
    }

    public function setSwooleRequest(SwooleRequest $request)
    {
        $this->swooleRequest = $request;
        return $this;
    }

    public function setSwooleResponse(SwooleResponse $response)
    {
        $this->swooleResponse = $response;
        return $this;
    }
}