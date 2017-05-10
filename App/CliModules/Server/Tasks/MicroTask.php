<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-28
 * Time: 上午9:33
 */
namespace App\CliModules\Server\Tasks;

use App\CliModules\Server\Tasks;
use Swoolcon\Application\Micro;
use Swoole\Http\Server as SwooleServer;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;

class MicroTask extends Tasks
{

    /**
     * @var \Swoolcon\Application\Micro
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
        $this->application = new Micro();
        $this->application->setRouter(require config_path('Router.php'))
            ->setServiceProviderList(require config_path('ProvidersWeb.php'))
            ->setModules(require config_path('ModuleWeb.php'));

    }

    public function onRequest(SwooleRequest $request, SwooleResponse $response)
    {
        $application          = $this->application;
        $request->get['_url'] = $request->server['request_uri'];
        $application->setSwooleRequest($request)->setSwooleResponse($response)->register();
        /** @var \Phalcon\Mvc\Micro $app */
        $app = $application->getApplication();

        $this->routerRegister($app);
        ob_start();
        $application->run();
        $response->end(ob_get_contents());
        ob_clean();

    }

    protected function routerRegister(\Phalcon\Mvc\Micro $app)
    {
        /**
         * Not found handler
         */
        $app->notFound(function () use ($app) {
            $app->response->setStatusCode(404, "Not Found")->sendHeaders();
            echo json_encode([
                'error' => 'true',
                'code'  => 404
            ], JSON_UNESCAPED_UNICODE);
        });



        $app->get('/', function () use ($app) {
            echo 'hello swoolcon micro';
        });
        $app->post('/', function () use ($app) {
            echo 'this is post' , PHP_EOL;
            var_dump($app->request->getPost());
        });

        $app->put('/',function() use($app){
            echo 'this is put', PHP_EOL;

            var_dump($app->request->getPut());
        });

        $app->delete('/', function () use ($app) {
            echo 'this is delete';

        });
    }
}