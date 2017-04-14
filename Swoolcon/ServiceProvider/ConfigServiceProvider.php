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
        $this->checkApplicationConfig();
        $merge = $this->mergeCallback();

        $this->di->setShared(
            $this->serviceName,
            function () use ($merge) {
                /** @noinspection PhpIncludeInspection */
                $config = include config_path('Config.php');

                if (!$config || !is_array($config)) {
                    trigger_error('Could not detect config file', E_USER_ERROR);
                }

                $config = new Config($config);

                $merge($config, content_path('Options/Options.php'));

                return $config;
            }
        );
    }

    protected function checkApplicationConfig()
    {
        $configPath = config_path('Config.php');
        if (!file_exists($configPath)) {
            throw new RuntimeException(
                sprintf(
                    'The Application config not found. Please make sure that the file "%s" is present',
                    $configPath
                )
            );
        }
    }

    protected function mergeCallback()
    {
        return function (&$config, $path) {
            if (file_exists($path)) {
                /** @noinspection PhpIncludeInspection */
                $toMerge = require $path;

                if (is_array($toMerge)) {
                    $toMerge = new Config($toMerge);
                }

                if ($toMerge instanceof Config) {
                    /** @var Config $config */
                    $config->merge($toMerge);
                }
            }
        };
    }
}
