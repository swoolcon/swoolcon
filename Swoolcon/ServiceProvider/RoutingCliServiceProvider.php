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
use Phalcon\Mvc\Router as MvcRouter;
use Phalcon\Cli\Router as CliRouter;
use Phalcon\Mvc\Router\GroupInterface;

/**
 * \Phanbook\Common\Library\Providers\RoutingServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class RoutingCliServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'router';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName,function() use($di){


            $router = require config_path('CliRouter.php');
            $router->setDI($di);
            $router->handle();
            return $router;
        });
    }
}
