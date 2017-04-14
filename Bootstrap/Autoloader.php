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
    'App\\Common'  => BASE_PATH . '/App/Common',
    'Swoolcon'     => BASE_PATH . '/Swoolcon',
])->register();


require 'DefaultConfig.php';

//require BASE_PATH . '/vendor/autoload.php';
