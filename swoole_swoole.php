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

require __DIR__ . '/Bootstrap/Autoloader.php';


class ServerSwoole
{

    /**
     * @var Swoolcon\Application\Web
     */
    private $application = null;

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
        $server->on('request', [$this, 'onRequest']);

        echo sprintf('server started on %s:%s%s', $host, $port, PHP_EOL);
        $server->start();
    }

    public function onWorkerStart(SwooleServer $server, $workerId)
    {
        $this->application = new Swoolcon\Application\Web();
        $this->application->setRouter(require config_path('Router.php'))
            ->setServiceProviderList(require config_path('ProvidersWeb.php'))
            ->setModules(require config_path('ModuleWeb.php'));

    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {
        //目前不处理静态文件，当成动态文件处理，静态文件建议交给nginx


        //动态脚本处理 request_uri
        $app                  = $this->application;
        $request->get['_url'] = $request->server['request_uri'];


        ob_start();
        $app->setSwooleRequest($request)->setSwooleResponse($response)->register();
        echo $app->run();
        $response->end(ob_get_contents());
        ob_clean();

        //把超全局unset掉
        unset($_SESSION);
        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
        unset($_FILES);
    }


}

echo <<<swoolcon
--------------------------------------------------------------------------------------------------

   @@@@@@@  @@@           @@@   @@@@@        @@@@@     @@@        @@@@@@      @@@@@     @@@    @@@
  @@@  @@@@  @@@   @@@   @@@   @@@  @@@     @@@  @@@   @@@       @@@  @@@    @@@  @@@   @@@@   @@@
 @@@    @@@  @@@   @@@   @@@  @@@    @@@   @@@    @@@  @@@      @@@    @@@  @@@    @@@  @@@@   @@@
 @@@         @@@  @@@@@  @@@ @@@      @@@ @@@      @@@ @@@     @@@     @@@ @@@      @@@ @@@@@  @@@
  @@@         @@@ @@@@@  @@@ @@@      @@@ @@@      @@@ @@@     @@@         @@@      @@@ @@@@@  @@@
   @@@@@@     @@@ @@ @@ @@@  @@@      @@@ @@@      @@@ @@@     @@@         @@@      @@@ @@@@@@ @@@
      @@@@@   @@@ @@ @@ @@@  @@@      @@@ @@@      @@@ @@@     @@@         @@@      @@@ @@@ @@@@@@
        @@@   @@@@@@ @@@@@@  @@@      @@@ @@@      @@@ @@@     @@@         @@@      @@@ @@@ @@@@@@
         @@@   @@@@@  @@@@   @@@      @@@ @@@      @@@ @@@     @@@     @@@ @@@      @@@ @@@  @@@@@
 @@@    @@@    @@@@   @@@@    @@@    @@@@  @@@    @@@@ @@@      @@@    @@@  @@@    @@@@ @@@  @@@@@
  @@@   @@@    @@@@   @@@@     @@@  @@@@    @@@  @@@@  @@@       @@@  @@@@   @@@  @@@@  @@@   @@@@
   @@@@@@@     @@@@   @@@@      @@@@@@       @@@@@@    @@@@@@@@   @@@@@@      @@@@@@    @@@    @@@

--------------------------------------------------------------------------------------------------

swoolcon;


(new ServerSwoole())->start();