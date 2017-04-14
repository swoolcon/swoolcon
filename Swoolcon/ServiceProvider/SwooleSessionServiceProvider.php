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
use Swoolcon\Session\Adapter;
use Swoolcon\Session\Adapter\Files;

/**
 * \App\Common\Library\Providers\SessionServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SwooleSessionServiceProvider extends ServiceProvider
{
    const UNIQUE_ID = 'swc_';

    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'session';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName, function () use($di){

            $config = $di->getShared('config');

            if (isset($config->application->session->adapter) || $config->application->session->adapter != 'Files') {
                $sessionAdapter = '\PhalconPlus\Session\Adapter\\' . $config->application->session->adapter;
                if (class_exists($sessionAdapter)) {
                    $config = $config->application->session->toArray();
                    unset($config['adapter']);

                    /** @var Adapter $session */
                    $session = new $sessionAdapter($config);
                    $session->setDI($di);
                    $session->start();
                    return $session;
                }
            }

            $session = new Files(['uniqueId' => SessionServiceProvider::UNIQUE_ID]);
            $session->setDI($di);
            $session->start();
            return $session;

        });
    }
}