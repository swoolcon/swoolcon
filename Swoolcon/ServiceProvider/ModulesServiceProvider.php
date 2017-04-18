<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-2-27
 * Time: 下午3:54
 */
namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Phalcon\Cli\Console;
use Phalcon\Registry;

use App\Modules\Frontend\Module as FrontendModule;




class ModulesServiceProvider extends ServiceProvider
{
    protected $serviceName = 'modules';

    protected $modules = [];

    public function configure()
    {


        $app = [

            'frontend'  => [
                'className' => FrontendModule::class,
                'path'      => modules_path('Frontend/Module.php'),
                'router'    => modules_path('Frontend/Config/Routing.php'),

            ],



        ];

        $this->modules = array_merge($app, $this->modules);
    }

    public function register()
    {
        $modules = $this->modules;

        $this->di->setShared($this->serviceName, function () use ($modules) {
            $moduleRegistry = new Registry();
            foreach ($modules as $key => $module) {
                $moduleRegistry->offsetSet($key, (object)$module);
            }
            return $moduleRegistry;
        });
    }

    public function boot()
    {
        $modules = [];

        $di = $this->di;

        //将每个模块注册至 di
        foreach ($this->modules as $key => $module) {
            $modules[$key] = function () use ($module, $di) {
                $moduleClass = $module['className'];
                if (!class_exists($moduleClass)) {
                    /** @noinspection PhpIncludeInspection */
                    include_once $module['path'];
                }

                /** @var \Swoolcon\ModuleInterface $moduleBootstrap */
                $moduleBootstrap = new $moduleClass($di);
                $moduleBootstrap->initialize();

                return $moduleBootstrap;
            };


            $this->getDI()->setShared($module['className'], $modules[$key]);
        }

        //在应用中注册模块

        /** @var \Phalcon\Mvc\Application $application */
        $application = $di->getShared('bootstrap')->getApplication();
        $application->registerModules($modules);


    }
}