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
     * @var Swoolcon\Application\Web
     */
    private $application = null;

    private $time = '';

    public function start()
    {
        $host   = '127.0.0.1';
        $port   = '9999';
        $server = new SwooleServer($host, $port);
        $server->set([
            'max_request' => '50',
            //'worker_num' => '1',
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
        ob_start();


        $request->get['_url'] = $request->server['request_uri'];

        $app = $this->application;
        $app->setSwooleRequest($request)->setSwooleResponse($response)->register();

        $di = $app->getDI();
        echo '<pre>';
        $app->run();


        echo '</pre>';
        $response->end(ob_get_contents());
        ob_clean();





    }


}


(new ServerSwoole())->start();