<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午4:18
 */

$router = new Swoolcon\Mvc\Router();
$router->removeExtraSlashes(true);

$frontend = new \Phalcon\Mvc\Router\Group([
    'module'     => 'frontend',
    'namespace'  => App\WebModules\Frontend\Controllers::class,
    'controller' => 'index',
    'action'     => 'index',
]);
$frontend->setPrefix('');

$frontend->add('[/]?', [
    'action' => 'index',
]);
$frontend->add('/:controller[/]?', [
    'controller' => 1,
]);
$frontend->add('/:controller/:action[/]?', [
    'controller' => 1,
    'action'     => 2,
]);

$router->mount($frontend);

return $router;