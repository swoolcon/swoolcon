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
use Swoolcon\Http\Request as SwoolconRequest;

/**
 * \App\Common\Library\Providers\ResponseServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SwooleRequestServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'request';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, SwoolconRequest::class);
    }
}
