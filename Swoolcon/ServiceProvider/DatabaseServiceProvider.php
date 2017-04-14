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

use PDO;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Swoolcon\Events\DbListener;

/**
 * \Phanbook\Common\Library\Providers\DatabaseServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'db';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->set(
            $this->serviceName,
            function () {
                /** @var \Phalcon\DiInterface $this */
                $config = $this->getShared('config');

                $connection = new Mysql(
                    [
                        'host'     => $config->database->mysql->host,
                        'username' => $config->database->mysql->username,
                        'password' => $config->database->mysql->password,
                        'dbname'   => $config->database->mysql->dbname,
                        'options'  => [
                            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $config->database->mysql->charset
                        ]
                    ]
                );

                $eventsManager = $this->getShared('eventsManager');
                $eventsManager->attach('db', new DbListener($this));

                $connection->setEventsManager($eventsManager);

                return $connection;
            }
        );
    }
}
