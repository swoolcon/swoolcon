<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午4:18
 */
$router = new Phalcon\Cli\Router();
$router->setDefaultTask('main');
$router->setDefaultAction('main');

return $router;