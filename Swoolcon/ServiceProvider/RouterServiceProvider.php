<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-5-4
 * Time: 下午5:38
 */
namespace Swoolcon\ServiceProvider;
use Swoolcon\Exception;
use Swoolcon\ServiceProvider;

class RouterServiceProvider extends ServiceProvider
{
    protected $serviceName = 'router';

    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName,function() use($di){

            /** @var \Phalcon\Cli\Router|\Phalcon\Mvc\Router $router */
            $router = $di->get('bootstrap')->getRouter();
            //$router = require config_path('Router.php');
            if(!$router){
                throw new Exception('router is not empty,please execute setRouter() in '. \Swoolcon\Application::class);
            }

            $router->setDI($di);
            return $router;
        });
    }
}