<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 3/27/17
 * Time: 11:20 PM
 */

namespace Swoolcon\Http;
use Phalcon\DiInterface;
use Phalcon\Http\Request\Exception as RequestException;
use Phalcon\Http\Response\Exception as ResponseException;
use Swoole\Http\Request as SwooleRequest;
use Swoole\Http\Response as SwooleResponse;

/**
 * Class SwooleHttpTrait
 * @package PhalconPlus\Http
 * @property DiInterface $_dependencyInjector
 */
trait HttpTrait
{

    /**
     * @var DiInterface
     */
    //protected $_dependencyInjector;

    /**
     * @var SwooleRequest
     */
    protected $_swooleRequest = null;

    /**
     * @var SwooleResponse
     */
    protected $_swooleResponse = null;

    /**
     * @param $di
     * @return DiInterface
     */
    private function _getDi($di)
    {
        if(!$di || !($di instanceof DiInterface)){
            return $this->_dependencyInjector;
        }

        return $di;
    }

    /**
     * @param null $di
     * @return mixed|Request
     * @throws RequestException
     */
    protected function getSwooleRequest($di =null)
    {
        if($this->_swooleRequest) return $this->_swooleRequest;

        $di = $this->_getDi($di);

        $this->_swooleRequest = $di->get('swooleRequest');

        if (!$this->_swooleRequest) {
            throw new RequestException('swoole request is empty',2314131);
        }

        return $this->_swooleRequest;
    }

    /**
     * @param null $di
     * @return mixed|Response
     * @throws ResponseException
     */
    protected function getSwooleResponse($di=null)
    {
        if($this->_swooleResponse) return $this->_swooleResponse;

        $di = $this->_getDi($di);
        $this->_swooleResponse = $di->get('swooleResponse');
        if (!$this->_swooleResponse) {
            throw new ResponseException('swoole response is empty',2314131);
        }

        return $this->_swooleResponse;
    }
}