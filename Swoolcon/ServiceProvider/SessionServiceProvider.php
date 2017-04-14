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
use Phalcon\Session\Adapter\Files;

/**
 * \App\Common\Library\Providers\SessionServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SessionServiceProvider extends ServiceProvider
{
    const UNIQUE_ID = 'hkexaq_';

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
        $this->di->setShared(
            $this->serviceName,
            function () {
                /** @var \Phalcon\DiInterface $this */
                $config = $this->getShared('config');

                if (isset($config->application->session->adapter) || $config->application->session->adapter != 'Files') {
                    $sessionAdapter = '\Phalcon\Session\Adapter\\' . $config->application->session->adapter;
                    if (class_exists($sessionAdapter)) {
                        $config = $config->application->session->toArray();
                        unset($config['adapter']);

                        /** @var \Phalcon\Session\AdapterInterface $session */
                        $session = new $sessionAdapter($config);
                        $session->start();

                        return $session;
                    }
                }


                $session = new Files(['uniqueId' => SessionServiceProvider::UNIQUE_ID]);
                $path    = content_path('Session');

                if (!is_dir($path)) {
                    mkdir($path, 0755);
                }

                session_save_path($path);
                $session->start();

                return $session;
            }
        );
    }
}