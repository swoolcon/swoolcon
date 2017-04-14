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



class Server
{

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

    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {

        $uri = $request->get['_url'] = $request->server['request_uri'];


        /**
         * 处理静态文件，建议交给nginx 吧，就不用过多处理了
         */
        //
        $fileName = BASE_PATH . '/Public' . $request->get['_url'];
        if (file_exists($fileName) && !is_dir($fileName)) {
            //header ...
            $response->end(file_get_contents($fileName));
            return false;
        }
        $di = new Phalcon\Di();

        Phalcon\Di::setDefault($di);
        ob_start();
        try {
            //service
            $this->registerSwooleService($di, $request, $response);

            //application
            $app     = new \Phalcon\Mvc\Application($di);
            $modules = require app_path('Register/Modules.php');

            $app->registerModules($modules);
            $res = $app->handle();
            echo $res->getContent();

        } catch (Exception $ex) {
            echo $ex->getTraceAsString();
        }
        $response->end(ob_get_contents());
        ob_flush();

        //想一下，用不用超全局全量，目前先unset 了再说，免得被跨请求使用
        unset($di);
        unset($_SESSION);
        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
        unset($_FILES);
        unset($_ENV);

    }

    private function registerSwooleService(\Phalcon\DiInterface $di, $request, $response)
    {

        $di->setShared('swooleRequest', $request);
        $di->setShared('swooleResponse', $response);


        require app_path('Register/SwoolconService.php');
    }
}


(new Server())->start();