<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-21
 * Time: 下午5:05
 */
namespace App\CliModules\Server;

use Phalcon\Cli\Dispatcher;
use Phalcon\Loader;
use Phalcon\DiInterface;
use App\Common\Module as BaseModule;
use Phalcon\Mvc\ModuleDefinitionInterface;


/**
 * \App\Frontend\Module
 *
 * @package Phanbook\Error
 */
class Module extends BaseModule implements ModuleDefinitionInterface
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getHandlersNamespace()
    {
        return Tasks::class;
    }

    /**
     * Registers an autoloader related to the module.
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {

    }

    /**
     * Registers services related to the module.
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        //dispatcher
        /** @var Dispatcher $dispatcher */
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace($this->getHandlersNamespace());
    }
}
