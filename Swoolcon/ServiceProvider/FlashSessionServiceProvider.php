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

use Phalcon\Flash\Session;

/**
 * \Phanbook\Common\Library\Providers\FlashSessionServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class FlashSessionServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'flashSession';

    protected $bannerStyle = [
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning',
    ];

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $bannerStyle = $this->bannerStyle;

        $this->di->setShared(
            $this->serviceName,
            function () use ($bannerStyle) {
                $flash = new Session();

                $flash->setAutoescape(true);
                $flash->setDI($this);
                $flash->setCssClasses($bannerStyle);

                return $flash;
            }
        );
    }
}
