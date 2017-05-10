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
        'adapter'  => env('DB_ADAPTER'),
        'host'     => env('DB_HOST'),
        'username' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'dbname'   => env('DB_DBNAME'),
        'charset'  => env('DB_CHARSET'),
    ],
    'application' => [
        'appDir'        => app_path('/'),
        'modelsDir'     => app_path('App/Common/Models'),
        'migrationsDir' => content_path('Migrations'),
        'cacheDir'      => content_path('Cache'),
        'viewsDir'      => '',
        'baseUri'       => '/',
        'debug'         => env('APP_DEBUG'),
        'logger'        => [
            'path'   => logs_path(),
            'format' => '',
            'level'  => '',
        ],
    ],
]);