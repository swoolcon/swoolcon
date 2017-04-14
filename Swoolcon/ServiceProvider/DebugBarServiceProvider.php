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

use App\Common\Application;
use Snowair\Debugbar\ServiceProvider;

/**
 * \Phanbook\Common\Library\Providers\MvcDispatcherServiceProvider
 *
 * @package App\Common\Library\Providers
 */
class DebugBarServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'phalconDebugBar';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName,function () use($di) {
            /** @var Application $application */
            $application = $di->get('bootstrap');

            $phalconApp = $application->getApplication();

            if (!isset($di->get('config')->application->debug) || true !== $di->get('config')->application->debug) {
                return null;
            }

            if($phalconApp instanceof \Phalcon\Mvc\Application){
                $di->setShared('app', $phalconApp);
                $debugBar = new ServiceProvider(config_path('Debugbar.php'));
                $debugBar->setDI($di);
                $debugBar->start();

                return $debugBar;
            }

            return null;

        });
    }
}
