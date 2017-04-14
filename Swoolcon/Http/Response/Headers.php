<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 16-11-16
 * Time: 下午2:22
 */
namespace Swoolcon\Http\Response;
use Phalcon\DiInterface;
use Phalcon\Http\Response\Headers as PhalconHeaders;
use Swoolcon\Http\HttpTrait;
use Phalcon\Http\Response\HeadersInterface;
use Phalcon\Di\InjectionAwareInterface;

class Headers extends PhalconHeaders implements HeadersInterface,InjectionAwareInterface{

    use HttpTrait;
    protected $_dependencyInjector;


    public function getDI()
    {
        return $this->_dependencyInjector;
    }

    public function setDI(DiInterface $dependencyInjector) {
        $this->_dependencyInjector = $dependencyInjector;

    }


    public function send()
    {
        //swoole 里面不晓得有没有用,貌似没用。。。
        //暂时不用
        /*if(headers_sent()){
            return false;
        }*/


        $response = $this->getSwooleResponse();

        foreach($this->_headers as $header=>$value){

            $response->header($header, $value);
        }
        return true;
    }


}