<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-21
 * Time: 下午4:57
 */
namespace Swoolcon\Application;
use Phalcon\Di;
use Swoolcon\Application;
use Swoolcon\Exception;
use Swoolcon\ServiceProvider;

class Command extends Application
{

    private $_defaultModule = 'main';

    public function run()
    {
        return $this->app->run();
    }

    public function registerProviders()
    {
        
    }

    public function register()
    {
        $di = new Di();
        $this->setDI($di);

        $di->setShared('bootstrap', $this);

        $this->app = new \Swoolcon\Cli\Console($di);


        //这一部分放到config 文件夹里面
        $this->initializeServiceProviders([
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
            ServiceProvider\RoutingCliServiceProvider::class,
            ServiceProvider\DispatcherCliServiceProvider::class,
            ServiceProvider\ModulesCliServiceProvider::class,
        ], $di);

        $this->getDi()->get('router')->setDefaultModule($this->_defaultModule);
        return $this;
    }

    /**
     * @param string $moduleName
     * @return $this
     */
    public function setDefaultModule($moduleName='')
    {
        $this->_defaultModule = $moduleName;
        return $this;
    }
}