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
use Swoolcon\ServiceProvider\EventManagerServiceProvider;
use Swoolcon\ServiceProvider\TestTestServiceProvider;

class Web extends Application
{
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
        //$di['test'] = $this->diPreLoad['test'];   //可以这么干
        $di->setShared('test',$this->diPreLoad->getShared('test')); //试一下，哪种快点，还有直接new的情况

        $this->initializeServiceProviders([
            //TestTestServiceProvider::class
        ], $di);
    }
}