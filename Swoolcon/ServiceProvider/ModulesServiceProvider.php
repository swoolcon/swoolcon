<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-2-27
 * Time: 下午3:54
 */
namespace App\Common\Library\Providers;

use Phalcon\Cli\Console;
use Phalcon\Registry;

use App\Modules\Frontend\Module as FrontendModule;
use App\Modules\Error\Module as ErrorModule;
use App\Modules\Tester\Module as TesterModule;
use App\Modules\Tools\Module as ToolsModule;
use App\Cli\Module as CliModule;
use App\SwooleCli\Module as SwooleCliModule;
use App\Modules\Backend\Module as BackendModule;

class ModulesServiceProvider extends ServiceProvider
{
    protected $serviceName = 'modules';

    protected $modules = [];

    public function configure()
    {
        try {

            $directory = new \RecursiveDirectoryIterator(content_modules_path() . '/');
        } catch (\Exception $exception) {
            $directory = [];
        }

        foreach ($directory as $item) {
            $name = ucfirst($item->getFilename());

            if (!$item->isDir() || $name[0] == '.') {
                continue;
            }

            $this->modules[$name] = [
                'className' => 'App\\' . $name . '\\Module',
                'path'      => content_modules_path("{$name}/Module.php"),
                'router'    => content_modules_path("{$name}/Config/Routing.php"),
            ];
        }

        $app = [
            'Backend'   => [
                'className' => BackendModule::class,
                'path'      => modules_path('Backend/Module.php'),
                'router'    => modules_path('Backend/Config/Routing.php'),
            ],
            'Error'     => [
                'className' => ErrorModule::class,
                'path'      => modules_path('Error/Module.php'),
                'router'    => modules_path('Error/Config/Routing.php'),

            ],
            'Frontend'  => [
                'className' => FrontendModule::class,
                'path'      => modules_path('Frontend/Module.php'),
                'router'    => modules_path('Frontend/Config/Routing.php'),

            ],
            'Tester'    => [
                'className' => TesterModule::class,
                'path'      => modules_path('Tester/Module.php'),
                'router'    => modules_path('Tester/Config/Routing.php'),
            ],
            'Tools'     => [
                'className' => ToolsModule::class,
                'path'      => modules_path('Tools/Module.php'),
                'router'    => modules_path('Tools/Config/Routing.php'),
            ],


            /**
             * cli 和swoole server 单独列出来
             */
            'Cli'       => [
                'className' => CliModule::class,
                'path'      => application_path('Cli/Module.php'),
                'router'    => application_path('Cli/Config/Routing.php'),
            ],
            'SwooleCli' => [
                'className' => SwooleCliModule::class,
                'path'      => application_path('SwooleCli/Module.php'),
                'router'    => application_path('SwooleCli/Config/Routing.php'),
            ],
            /**/


            /*
            'oauth' => [
                'className' => oAuth::class,
                'path'      => modules_path('oauth/Module.php'),
                'router'    => modules_path('oauth/config/routing.php'),

            ],*/


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

                /** @var \App\Common\ModuleInterface $moduleBootstrap */
                $moduleBootstrap = new $moduleClass($di);
                $moduleBootstrap->initialize();

                return $moduleBootstrap;
            };


            $this->getDI()->setShared($module['className'], $modules[$key]);
        }

        //在应用中注册模块

        $application = $di->getShared('bootstrap')->getApplication();

        if ($application instanceof Console) {
            $application->registerModules($this->modules);
        } else {
            $application->registerModules($modules);
        }

    }
}