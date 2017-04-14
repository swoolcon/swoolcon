<?php
/**
 * Created by PhpStorm.
 * User: debian
 * Date: 3/24/17
 * Time: 10:11 PM
 */
namespace Swoolcon\Mvc;

use Phalcon\Mvc\Router\Exception;
use Swoolcon\Http\Request;

class Router extends \Phalcon\Mvc\Router
{
    public function getRewriteUri()
    {

        /** @var Request $request */
        $request = $this->_dependencyInjector->getShared('request');

        if (!$request || !($request instanceof Request)) {
            throw new Exception('there is no swoole request');
        }
        /**
         * By default we use $_GET["url"] to obtain the rewrite information
         */
        if ($this->_uriSource) {

            if ($url = $request->getQuery('_url')) {
                return $url;
            }

        } else {
            /**
             * Otherwise use the standard $_SERVER["REQUEST_URI"]
             */

            if($url = $request->getServer('request_uri')){
                list($realUri) = explode('?', $url);
                if(!empty($realUri)){
                    return $realUri;
                }
            }
        }
        return "/";
    }
}
