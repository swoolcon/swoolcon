<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-14
 * Time: 下午3:27
 */
namespace Swoolcon\Application;

use Phalcon\Di;
use Phalcon\DiInterface;
use Swoolcon\Application;
use Swoolcon\Exception;
use Swoolcon\Http\Request;
use Swoolcon\ServiceProvider;
use Swoole\Http\Response as SwooleResponse;
use Swoole\Http\Request as SwooleRequest;

class Web extends Application
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
        $response = $this->app->handle();
        return $response->getContent();
    }

    protected function registerProviders()
    {
        //注入服务,暂时不用，留以后用
        /*$this->initializeServiceProviders([
            ServiceProvider\EventManagerServiceProvider::class,
            ServiceProvider\ConfigServiceProvider::class,
            ServiceProvider\CryptServiceProvider::class,
            ServiceProvider\DatabaseServiceProvider::class,
            ServiceProvider\DataCacheServiceProvider::class,
            ServiceProvider\TestTestServiceProvider::class
        ], $this->diPreLoad);*/


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
        $this->app = new \Phalcon\Mvc\Application($di);

        //swoole request response 注入到di
        if (!$this->swooleRequest) throw new Exception('swoole request is not empty');
        if (!$this->swooleResponse) throw new Exception('swoole response is not empty');
        $di->setShared('swooleRequest', $this->swooleRequest);
        $di->setShared('swooleResponse', $this->swooleResponse);

        //这一部分放到config 文件夹里面
        /*$this->initializeServiceProviders([
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
        ], $di);*/

        $this->initializeServiceProviders($this->getServiceProviderList(),$di);


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