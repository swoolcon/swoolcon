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
use Swoolcon\ServiceProvider\EventManagerServiceProvider;
use Swoolcon\ServiceProvider\TestTestServiceProvider;
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

    public function run()
    {

    }

    protected function registerProviders()
    {
        $this->initializeServiceProviders([
            EventManagerServiceProvider::class,
            TestTestServiceProvider::class
        ], $this->diPreLoad);


    }

    public function register()
    {
        $di = $this->getDi();
        if (!$di || !($di instanceof DiInterface)) {
            $di = new Di();
            $this->setDI($di);
        }

        if(!$this->swooleRequest){
            throw new Exception('swoole request is not empty');
        }

        if(!$this->swooleResponse){
            throw new Exception('swoole response is not empty');
        }

        $this->initializeServiceProviders([
            //TestTestServiceProvider::class
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