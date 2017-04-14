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
use Swoolcon\Http\Response\Cookies as SwoolconCookies;

/**
 * \App\Common\Library\Providers\ResponseServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SwooleCookiesServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'cookies';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared(
            $this->serviceName,
            function () use($di){
                $cookies = new SwoolconCookies();
                $cookies->setDI($di);
                $config = $this->get('config')->application->debug;
                if($config === true){
                    $cookies->useEncryption(false);

                }else{

                    $cookies->useEncryption(true);
                }
                return $cookies;
            }
        );
    }
}
