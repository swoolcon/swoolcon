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
use Phalcon\Tag;

/**
 * \Phanbook\Common\Library\Providers\TagServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class TestTestServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'test';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, function(){
            $a = new \stdClass();
            $a->name = 'name';
            $a->age = 12;
            $a->time = time();

            var_dump('instance');

            return $a;
        });
    }
}
