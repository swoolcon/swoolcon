<?php
/**
 * @brief 这是一个 restful demo ，以swoole_table 内存表来存储数据（服务停止后后清空数据）
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
use Swoole\Table;

class RestfulTask extends Tasks
{

    /**
     * @var \Swoolcon\Application\Micro
     */
    private $application = null;

    /**
     * @var \Swoole\Http\Server
     */
    private $_server = null;

    public function mainAction()
    {
        //swoole_table ,
        $person = new Table(1024);
        $person->column('id', Table::TYPE_INT, 16);
        $person->column('name', Table::TYPE_STRING, 16);
        $person->column('age', Table::TYPE_INT, 2);
        $person->create();

        //保存表的最大id
        $maxId = new Table(1024);
        $maxId->column('name', Table::TYPE_STRING, 16);
        $maxId->column('max', Table::TYPE_INT, 8);
        $maxId->create();

        $host   = '127.0.0.1';
        $port   = '9999';
        $server = new SwooleServer($host, $port);
        $server->set([
            'max_request' => '50'
        ]);
        $server->on('WorkerStart', [$this, 'onWorkerStart']);
        $server->on('Request', [$this, 'onRequest']);

        echo sprintf('server started on %s:%s%s', $host, $port, PHP_EOL);


        // 把table表注入到服务
        $server->person = $person;
        $server->maxId  = $maxId;

        $server->start();
    }

    public function onWorkerStart(SwooleServer $server, $workerId)
    {
        $this->_server     = $server;
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
        /** @var Table $personTable */
        $personTable = $this->_server->person;

        /** @var Table $maxIdTable */
        $maxIdTable = $this->_server->maxId;


        /**
         * @param array $data
         * @param string $msg
         * @return \Phalcon\Http\Response|\Phalcon\Http\ResponseInterface
         */
        $response = function ($data = [], $msg = '') use ($app) {
            if ($msg) {

                return $app->response->setJsonContent([
                    'http'  => true,
                    'error' => true,
                    'msg'   => $msg,
                    'data'  => $data
                ], JSON_UNESCAPED_UNICODE);
            }

            if (empty($data)) {
                $data = [];
            }

            return $app->response->setJsonContent([
                'http'  => true,
                'error' => false,
                'data'  => $data
            ], JSON_UNESCAPED_UNICODE);

        };


        /**
         * Not found handler
         */
        $app->notFound(function () use ($app, $response) {
            $app->response->setStatusCode(404, "Not Found")->sendHeaders();
            return $response([], 'not found');
        });


        $app->get('/', function () use ($app) {
            echo <<<info
<html>
<head>
    <script src="https://cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
    <style>
        #person_list td a{
            margin-right:10px
        }
    </style>
</head>
<body>
<h1>restful</h1>

<h2>list</h2>

<table>
    <thead>
    <tr>
        <th>id</th>
        <th>name</th>
        <th>age</th>
        <th>operation</th>
    </tr>
    </thead>
    <tbody id="person_list">


    </tbody>
</table>

<h2>add</h2>
<div>
    <table>
        <thead>
        <tr>
            <th>name</th>
            <th>age</th>
            <th>operation</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" id="add_name">
            </td>
            <td>
                <input type="text" id="add_age">
            </td>
            <td>
                <a href="javascript:void(0);" id="add_btn">add</a>
            </td>
        </tr>

        </tbody>
    </table>
</div>

<h2>update</h2>
<div>
    <table>
        <thead>
        <tr>
            <th>name</th>
            <th>age</th>
            <th>operation</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <input type="hidden" id="update_id">
            <td>
                <input type="text" id="update_name">
            </td>
            <td>
                <input type="text" id="update_age">
            </td>
            <td>
                <a href="javascript:void(0);" id="update_btn">update</a>
            </td>
        </tr>

        </tbody>
    </table>
</div>

</body>
<script>
    $(function () {

        var getPersonList = function () {
            $.ajax({
                type: "GET",
                url: "/api/persons",
                dataType: "json",
                success: function (data) {
                    data = data.data;

                    $('#person_list').html('');
                    if (data.length <= 0) {
                        return;
                    }

                    data.forEach(function (val, key) {
                        var tr = $('<tr>');
                        tr.append($('<td>').text(val.id));
                        tr.append($('<td>').text(val.name));
                        tr.append($('<td>').text(val.age));

                        var op      = $('<td>');
                        var alterOp = $('<a>').attr('href', 'javascript:void(0);').text('alter');
                        alterOp.on('click', function () {
                            $('#update_id').val(val.id);
                            $('#update_name').val(val.name);
                            $('#update_age').val(val.age);

                            //location

                        });

                        var deleteOp = $('<a>').attr('href', 'javascript:void(0);').text('delete');
                        deleteOp.on('click', function () {
                            var dialog = confirm('are you sure??');
                            if (!dialog) return;

                            $.ajax({
                                type: "DELETE",
                                url: "/api/person/" + val.id,
                                dataType: "json",
                                success: function (data) {
                                    getPersonList();
                                }
                            });
                        });
                        op.append(alterOp).append(deleteOp);
                        tr.append($('<td>').html(op));

                        $('#person_list').append(tr);
                    });

                }
            });
        };

        var addPerson = function () {
            var name = $('#add_name').val();
            var age  = $('#add_age').val();
            if (!name || !age) {
                alert('name and age are not empty');
                return;
            }
            $.ajax({
                type: "POST",
                url: "/api/person",
                dataType: "json",
                data: {
                    name: name,
                    age: age
                },
                success: function (data) {
                    if (data.http && !data.error) {
                        $('#add_name').val('');
                        $('#add_age').val('');
                        getPersonList();
                        return;
                    }
                    alert(data.msg || 'unknown error')
                }
            });
        };

        var updatePerson = function () {
            var id   = $('#update_id').val();
            var name = $('#update_name').val();
            var age  = $('#update_age').val();

            id = parseInt(id);
            if (isNaN(id) || id <= 0) return;

            $.ajax({
                type: "PUT",
                url: "/api/person/" + id,
                dataType: "json",
                data: {
                    name: name,
                    age: age
                },
                success: function (data) {
                    if (data.http && !data.error) {
                        $('#update_id').val('0');
                        $('#update_name').val('');
                        $('#update_age').val('');
                        getPersonList();
                        return;
                    }
                    alert(data.msg || 'unknown error')
                }
            });
        };


        $('#add_btn').on('click', function () {
            addPerson();
        });
        $('#update_btn').on('click', function () {
            updatePerson();
        });


        getPersonList();
    });
</script>

</html>

info;
        });

        //get all
        $app->get('/api/persons', function () use ($app, $personTable, $response) {

            $list = [];
            foreach ($personTable as $person) {
                $list[] = $person;
            }
            return $response($list);
        });

        //get a person
        $app->get('/api/person/{id:[0-9]+}', function ($id) use ($app, $personTable, $response) {
            $id = intval($id);
            if ($id <= 0) return $response([], 'id 错误');

            $person = $personTable->get($id);
            if (!$person) return $response([], '没有找到该人');

            return $response($person);
        });

        //add
        $app->post('/api/person', function () use ($app, $personTable, $maxIdTable, $response) {

            $name = $app->request->getPost('name', 'string', '');
            $age  = $app->request->getPost('age', 'int', 0);

            if (!$name || !$age) {
                return $response([], 'name and age are not empty');
            }

            $newId = $maxIdTable->incr('person', 'max');
            if (!$newId) {
                $newId = 1;
                $maxIdTable->set('person', ['max' => $newId]);
            }

            $data = [
                'id'   => $newId,
                'name' => $name,
                'age'  => $age
            ];
            $res  = $personTable->set($newId, $data);
            if (!$res) {
                return $response([], '保存失败');
            }
            return $response($data);

        });

        //update
        $app->put('/api/person/{id:[0-9]+}', function ($id) use ($app, $personTable, $response) {

            $id = intval($id);
            if ($id <= 0) return $response([], 'id 错误');

            $person = $personTable->get($id);
            if (!$person) return $response([], '没有找到该人');


            $name = $app->request->getPut('name', 'string', false);
            $age  = $app->request->getPut('age', 'int', 0);

            if ($name !== false) $person['name'] = $name;

            if ($age > 0) $person['age'] = $age;

            $res = $personTable->set($id, $person);

            if (!$res) return $response([], '保存失败');
            return $response($person);

        });

        //delete
        $app->delete('/api/person/{id:[0-9]+}', function ($id) use ($app, $personTable, $response) {
            $id = intval($id);
            if ($id <= 0) return $response([], 'id 错误');

            $person = $personTable->get($id);
            if (!$person) return $response([], '没有找到该人');

            $res = $personTable->del($id);
            if (!$res) {
                return $response([], '删除失败');
            }
            return $response([
                'id' => $id
            ]);
        });
    }
}