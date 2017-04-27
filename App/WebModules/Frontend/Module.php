<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午5:20
 */
namespace App\WebModules\Frontend;

use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Swoolcon\Events\ViewListener;

class Module extends \Swoolcon\Module implements ModuleDefinitionInterface
{

    public function getHandlersNamespace()
    {
        return Controllers::class;
    }

    public function registerAutoloaders(DiInterface $di = null)
    {

    }

    public function registerServices(DiInterface $di = null)
    {
        $eventsManager = $di->getShared('eventsManager');
        $eventsManager->attach('view:notFoundView', new ViewListener($di));
        /** @var View $view */
        $view = $di->getShared('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}