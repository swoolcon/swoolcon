<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-5-3
 * Time: 下午4:56
 */

return [
    'frontend'  => [
        'className' => App\WebModules\Frontend\Module::class,
        'path'      => modules_web_path('Frontend/Module.php'),
        'router'    => modules_web_path('Frontend/Config/Routing.php'),
    ],

    'Error'  => [
        'className' => Swoolcon\Modules\Error\Module::class,
        'path'      => app_path('Swoolcon/Modules/Error/Module.php'),
        'router'    => app_path('Swoolcon/Modules/Error/Config/Routing.php'),
    ],

];
