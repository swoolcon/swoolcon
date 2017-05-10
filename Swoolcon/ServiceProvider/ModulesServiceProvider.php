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

use App\WebModules\Frontend\Module as FrontendModule;
use Swoolcon\Modules\Error\Module as DefaultErrorModule;


class ModulesServiceProvider extends ServiceProvider
{
    protected $serviceName = 'modules';

    protected $modules = [];

    protected $moduleConfig = 'ModuleWeb.php';


    protected function modulesDefault()
    {
        return [

            'Error' => [
                'className' => DefaultErrorModule::class,
                'path'      => app_path('Swoolcon/Modules/Error/Module.php'),
                'router'    => app_path('Swoolcon/Modules/Error/Config/Routing.php'),
            ],

        ];
    }

    public function configure()
    {
        //default
        $moduleDefault = $this->modulesDefault();
        $modules = $this->di->get('bootstrap')->getModules();

        $this->modules = array_merge($moduleDefault, $this->modules,$modules);
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

            $di->setShared($module['className'], $modules[$key]);
        }

        //在应用中注册模块

        $this->registerModules($modules);

    }

    protected function registerModules($modules)
    {
        $di = $this->di;
        /** @var \Phalcon\Mvc\Application $application */
        $application = $di->getShared('bootstrap')->getApplication();

        $application->registerModules($modules);
    }
}