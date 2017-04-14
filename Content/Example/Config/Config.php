<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午2:54
 */


return new \Phalcon\Config([
    'database'    => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '',
        'dbname'   => 'test',
        'charset'  => 'utf8',
    ],
    'application' => [
        'appDir'        => app_path('/'),
        'modelsDir'     => app_path('App/Common/Models'),
        'migrationsDir' => content_path('Migrations'),
        'cacheDir'      => content_path('Cache'),
        'viewsDir'      => content_path('Cache'),
        'baseUri'       => '/',
    ]
]);
