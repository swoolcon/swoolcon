<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午2:56
 */
use Phalcon\Loader;

// Load constants
require 'Constant.php';
require 'Helper.php';

(new Loader)->registerNamespaces([
    'App'      => BASE_PATH . '/App',
    'Swoolcon' => BASE_PATH . '/Swoolcon',
])->register();

require BASE_PATH . '/vendor/autoload.php';

//env
$dotEnv = new Dotenv\Dotenv(BASE_PATH);
$dotEnv->load();