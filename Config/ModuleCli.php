<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-5-3
 * Time: 下午4:56
 */
return [
    'server'  => [
        'className' => App\CliModules\Server\Module::class,
        'path'      => modules_cli_path('Server/Module.php'),
        'router'    => '',
    ],
    'demo'  => [
        'className' => App\CliModules\Demo\Module::class,
        'path'      => modules_cli_path('Demo/Module.php'),
        'router'    => '',
    ],

];
