#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 3/24/17
 * Time: 8:48 PM
 */

use Swoole\Http\Server as SwooleServer;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
use Phalcon\Di\FactoryDefault;

use Phalcon\Mvc\Application;

error_reporting(E_ALL);

defined('BASE_PATH') || define('BASE_PATH', __DIR__);

require BASE_PATH . '/Bootstrap/Autoloader.php';


class ServerSwoole
{

    /**
     * @var Swoolcon\Application
     */
    private $application = null;

    private $time = '';

    public function start()
    {
        $host   = '127.0.0.1';
        $port   = '9999';
        $server = new SwooleServer($host, $port);
        $server->set([
            'max_request' => '50'
        ]);
        $server->on('WorkerStart', [$this, 'onWorkerStart']);
        $server->on('Request', [$this, 'onRequest']);

        echo sprintf('server started on %s:%s%s', $host, $port, PHP_EOL);
        $server->start();
    }

    public function onWorkerStart(SwooleServer $server, $workerId)
    {

        $app               = new Swoolcon\Application\Web();
        $this->application = $app;


    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {

        $request->get['_url'] = $request->server['request_uri'];

        $app = $this->application;
        $app->register();

        $di = $app->getDI();


        ob_start();

        echo '<pre>';

        $a = $di->get('test');
        var_dump(get_class($a));

        echo '??' . time() . PHP_EOL;

        var_dump($this->time);

        $loader = new Phalcon\Loader();
        $loader->registerDirs([__DIR__ . '/tmp/']);
        $loader->register();
        echo '</pre>';
        $response->end(ob_get_contents());
        ob_clean();


    }


}


(new ServerSwoole())->start();