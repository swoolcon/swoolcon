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
use Phalcon\Config;
use RuntimeException;


class ConfigServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'config';

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
                /** @noinspection PhpIncludeInspection */
                $config = include config_path('Config.php');

                //if (!$config || !is_array($config)) {
                if (!$config || !($config instanceof \Phalcon\Config)) {
                    trigger_error('Could not detect config file', E_USER_ERROR);
                }


                return $config;
            }
        );
    }


}
