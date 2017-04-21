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

/**
 * \Phanbook\Common\Library\Providers\RoutingServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class RoutingWebServiceProvider extends ServiceProvider
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
            /** @var MvcRouter $router */
            $router = require config_path('Router.php');
            $router->removeExtraSlashes(true);

            $router->setDI($di);
            return $router;
        });
    }
}
