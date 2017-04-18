<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-14
 * Time: 下午1:58
 */
namespace Swoolcon;

use Phalcon\Di;
use Phalcon\Di\Injectable;
use Phalcon\Di\ServiceInterface;
use Phalcon\Application as AbstractApplication;
use Phalcon\DiInterface;
use Swoolcon\ServiceProviderInterface;
use Swoolcon\ServiceProvider\EventManagerServiceProvider;



abstract class Application
{

    /**
     * @var ServiceProviderInterface[]
     */
    protected $serviceProviders = [];

    /**
     *
     * @var ServiceInterface[]
     */
    protected $services = [];

    /**
     * the phalcon application
     * @var AbstractApplication
     */
    protected $app;

    /**
     * @var DiInterface
     */
    protected $diPreLoad = null;    //进程重启前，会常驻内存

    /**
     * @var DiInterface
     */
    protected $di = null;


    public function __construct()
    {

        $di = new Di();

        $di->setShared('bootstrap', $this);

        $this->diPreLoad = $di;

        $this->registerProviders();
    }


    /**
     * @brief
     * @param string $providersFile 相对 config 的文件路径
     */
    protected function requireProviders($providersFile)
    {
        $path = config_path($providersFile);
        if (!is_file($path)) return;

        /** @noinspection PhpIncludeInspection */
        $providers = require $path;
        if (is_array($providers)) {
            $this->initializeServiceProviders($providers);
        }
    }


    protected function initializeServiceProvider(ServiceProviderInterface $serviceProvider)
    {
        $serviceProvider->register();
        $serviceProvider->boot();
        $this->serviceProviders[$serviceProvider->getName()] = $serviceProvider;
        return $this;
    }

    /**
     * Initialize Services in the Dependency Injector Container.
     *
     * @param  string[] $providers
     * @param  DiInterface $di
     * @return $this
     */
    protected function initializeServiceProviders(array $providers,DiInterface $di=null)
    {
        if(!$di) $di = $this->getDI();

        foreach ($providers as $name => $class) {
            $this->initializeServiceProvider(new $class($di));
        }

        return $this;
    }

    public function getApplication()
    {
        return $this->app;
    }

    public function getServiceProviders()
    {
        return $this->serviceProviders;
    }

    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return DiInterface
     */
    public function getDi()
    {
        return $this->di;
    }

    public function setDi(DiInterface $di)
    {
        $this->di = $di;
        return $this;
    }


    abstract protected function registerProviders();


    abstract public function run();


    abstract public function register();

}