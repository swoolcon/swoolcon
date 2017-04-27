<?php
/**
 * Phanbook : Delightfully simple forum and Q&A software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @author  Phanbook <hello@phanbook.com>
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
namespace Swoolcon\Modules\Error\Controllers;

use Phalcon\DispatcherInterface;
use Phalcon\Mvc\View;
use Swoolcon\Modules\Error\Controllers;

/**
 * \Phanbook\Error\Controllers\IndexController
 *
 * @property \Phalcon\Config $config
 *
 * @package Phanbook\Error\Controllers
 */
class IndexController extends Controllers
{
    /**
     * Triggered before executing the controller/action method.
     *
     * @param  DispatcherInterface $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute(DispatcherInterface $dispatcher)
    {
        if ($dispatcher->hasParam('message')) {
            $message = $dispatcher->getParam('message');
        } else {
            $message = "Sorry! We can't seem to find the page you're looking for.";
        }

        $this->view->setVars([
            'message' => $message,
        ]);

        return true;
    }

    public function initialize()
    {
        $this->view->disableLevel([
            View::LEVEL_MAIN_LAYOUT => true,
            View::LEVEL_LAYOUT      => true
        ]);

    }

    public function show400Action()
    {
        $this->response->setStatusCode(400);
        $this->view->pick('index/show_error');
    }

    public function show401Action()
    {
        $this->response->setStatusCode(401);
        $this->response->setHeader('WWW-Authenticate', 'Digest realm="Access denied"');
        $this->view->pick('index/show_error');
    }

    public function show403Action()
    {
        $this->response->setStatusCode(403);
        $this->view->pick('index/show_error');
    }

    public function show404Action()
    {
        $this->response->setStatusCode(404);
        $this->view->pick('index/show_error');
    }

    public function show500Action()
    {
        $this->response->setStatusCode(500);
        $this->view->pick('index/show_error');
    }

    public function show503Action()
    {
        $this->response->setStatusCode(503);
        $this->view->pick('index/show_error');
    }
}
