<?php
/**
 * Phanbook : Delightfully simple forum and Q&A software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @author  Phanbook <hello@phanbook.com>
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
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
