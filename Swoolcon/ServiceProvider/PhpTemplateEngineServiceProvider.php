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
use Phalcon\DiInterface;
use Phalcon\Mvc\ViewBaseInterface;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;

/**
 * \Phanbook\Common\Library\Providers\PhpTemplateEngineServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class PhpTemplateEngineServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'phpEngine';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function (ViewBaseInterface $view, DiInterface $di = null) {
                return new PhpEngine($view, $di ?: $this);
            }
        );
    }
}
