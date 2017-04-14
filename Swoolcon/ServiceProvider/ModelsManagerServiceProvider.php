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
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\ManagerInterface;

/**
 * \Phanbook\Common\Library\Providers\ModelsManagerServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class ModelsManagerServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'modelsManager';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared($this->serviceName,function () use($di){
            /** @var ManagerInterface $this */
            $modelsManager = new Manager($di);
            $modelsManager->setDI($di);
            $modelsManager->setEventsManager($di->getShared('eventsManager'));

            return $modelsManager;
        });
    }
}
