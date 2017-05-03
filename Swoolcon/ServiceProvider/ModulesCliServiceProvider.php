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

use App\CliModules\Main\Module as CliModule;


class ModulesCliServiceProvider extends ModulesServiceProvider
{

    protected $moduleConfig = 'ModuleCli.php';

    protected function modulesDefault()
    {
        return [
            'main'  => [
                'className' => CliModule::class,
                'path'      => modules_cli_path('Main/Module.php'),
                'router'    => '',
            ],

        ];
    }


    protected function registerModules($modules)
    {
        $di = $this->di;
        /** @var \Phalcon\Mvc\Application $application */
        $application = $di->getShared('bootstrap')->getApplication();

        $application->registerModules($this->modules);
    }
}