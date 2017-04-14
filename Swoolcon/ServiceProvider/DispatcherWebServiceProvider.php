<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;

use Phalcon\Cli\Dispatcher as CliDi;
use Swoolcon\Mvc\Dispatcher as MvcDi;
use Swoolcon\Events\DispatcherListener;
use Phalcon\Events\EventInterface;
use Phalcon\Events\ManagerInterface;

/**
 * \Phanbook\Common\Library\Providers\MvcDispatcherServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class DispatcherWebServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'dispatcher';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName, function () use ($di) {
            $dispatcher   = new MvcDi();
            /** @var ManagerInterface $eventManager */
            $eventManager = $di->get('eventsManager');
            $eventManager->attach('dispatch', new DispatcherListener($this));

            $dispatcher->setDI($di);
            $dispatcher->setEventsManager($eventManager);

            return $dispatcher;
        });
    }
}
