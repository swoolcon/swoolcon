<?php

namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use PDO;
//use Phalcon\Db\Adapter\Pdo\Mysql;
use Swoolcon\Db\Adapter\Pdo\Mysql;
use App\Common\Library\Events\DbListener;


class SwooleWebDatabaseServiceProvider extends ServiceProvider
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
        $di = $this->di;
        $di->set($this->serviceName,function () use($di){
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
                        ],
                        'di' => $di,

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
