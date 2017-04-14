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
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\ViewBaseInterface;

/**
 * \Phanbook\Common\Library\Providers\VoltTemplateEngineServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class VoltTemplateEngineServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'volt';

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
                /** @var \Phalcon\DiInterface $this */
                $config = $this->getShared('config');

                $volt = new Volt($view, $di ?: $this);

                $volt->setOptions(
                    [
                        'compiledPath'      => $config->application->view->compiledPath,
                        'compiledSeparator' => $config->application->view->compiledSeparator,
                        'compiledExtension' => $config->application->view->compiledExtension,
                        'compileAlways'     => (bool) $config->application->debug,
                    ]
                );

                //$volt->getCompiler()->addExtension(new VoltFunctions());

                return $volt;
            }
        );
    }
}
