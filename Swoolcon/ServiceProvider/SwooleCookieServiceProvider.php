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
use Swoolcon\Http\Cookie as SwoolconCookie;

/**
 * \App\Common\Library\Providers\ResponseServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SwooleCookieServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'Phalcon\\Http\\Cookie';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->set($this->serviceName, function ($name, $value = null, $expire = 0, $path = "/", $secure = null, $domain = null, $httpOnly = null){
            $cookie = new SwoolconCookie($name, $value, $expire, $path, $secure, $domain, $httpOnly);
            return $cookie;

        });
    }
}
