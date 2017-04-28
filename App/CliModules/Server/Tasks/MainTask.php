<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-27
 * Time: 上午10:58
 */
namespace App\CliModules\Server\Tasks;

use App\CliModules\Server\Tasks;
use Swoole\Http\Server as SwooleServer;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;
class MainTask extends Tasks
{


    /**
     * @var \Swoolcon\Application\Web
     */
    private $application = null;

    public function mainAction()
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
        //先这么做，以后可能有优化，把 app 放 workerstart 里面，而不是每次请求都new 一个
        $app               = new \Swoolcon\Application\Web();
        $this->application = $app;

    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {
        //目前不处理静态文件，当成动态文件处理，静态文件建议交给nginx


        //动态脚本处理 request_uri
        $app = $this->application;
        $request->get['_url'] = $request->server['request_uri'];


        ob_start();
        $app->setSwooleRequest($request)->setSwooleResponse($response)->register();
        echo $app->run();
        $response->end(ob_get_contents());
        ob_clean();

        //先把超全局unset掉，否则可能串请求
        unset($_SESSION);
        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
        unset($_FILES);
        unset($_ENV);
    }
}