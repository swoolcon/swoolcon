<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午5:15
 */

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Flash\Direct as Flash;

/**
 * Shared configuration service
 */
$di->setShared('config', function () {
    return include config_path('Config.php');
});

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

/**
 * Setting up the view component
 */
$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ]);

            return $volt;
        },
        '.phtml' => PhpEngine::class

    ]);

    return $view;
});


$di->setShared('eventsManager',function(){
    $manager = new \Phalcon\Events\Manager();
    return $manager;
});
/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});


/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

/**
 * Register the session flash service with the Twitter Bootstrap classes
 */
$di->set('flash', function () {
    return new Flash([
        'error'   => 'alert alert-danger',
        'success' => 'alert alert-success',
        'notice'  => 'alert alert-info',
        'warning' => 'alert alert-warning'
    ]);
});


$di->setShared('dispatcher',function(){
    $dispatcher = new \Phalcon\Mvc\Dispatcher();

    return $dispatcher;
});










$di->setShared('request', function () use ($di) {
    $request = new \Swoolcon\Http\Request();
    $request->setDi($di);
    return $request;
});

//
$di->setShared('response', function () use ($di) {
    $response = new \Swoolcon\Http\Response();
    $response->setDi($di);
    return $response;
});

//
$di->setShared('cookies', function () use ($di) {
    $cookies = new \Swoolcon\Http\Response\Cookies();
    $cookies->useEncryption(false);
    $cookies->setDI($di);
    return $cookies;
});


//cookie
$di->set('Phalcon\\Http\\Cookie', function ($name, $value = null, $expire = 0, $path = "/", $secure = null, $domain = null, $httpOnly = null) {
    $cookie = new \Swoolcon\Http\Cookie($name, $value, $expire, $path, $secure, $domain, $httpOnly);
    return $cookie;
});

//session , 用cache 改的
$di->setShared('session', function () use($di){

    $session = new \Swoolcon\Session\Adapter\Files(['uniqueId' => 'sessId']);
    $session->setDI($di);
    $session->start();
    return $session;

});


$di->setShared('router',function() use($di){
    /** @var \Phalcon\Mvc\Router $router */
    include config_path('Router.php');

    $router = get_router(new \Swoolcon\Mvc\Router());

    $router->setDI($di);
    $router->handle();
    return $router;
});