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
use Phalcon\Security;

/**
 * \Phanbook\Common\Library\Providers\SecurityServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class SecurityServiceProvider extends ServiceProvider
{
    const DEFAULT_WORK_FACTOR = 12;

    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'security';

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

                $security = new Security();

                $workFactor = SecurityServiceProvider::DEFAULT_WORK_FACTOR;
                if (isset($config->application->hashingFactor)) {
                    $workFactor = (int) $config->application->hashingFactor;
                }

                $security->setWorkFactor($workFactor);

                return $security;
            }
        );
    }
}
