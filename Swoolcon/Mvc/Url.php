<?php
/*
 +------------------------------------------------------------------------+
 | Phalcon Framework                                                      |
 +------------------------------------------------------------------------+
 | Copyright (c) 2011-2016 Phalcon Team (https://phalconphp.com)       |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to license@phalconphp.com so we can send you a copy immediately.       |
 +------------------------------------------------------------------------+
 | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
 |          Eduar Carvajal <eduar@phalconphp.com>                         |
 +------------------------------------------------------------------------+
 */

namespace Swoolcon\Mvc;

use Phalcon\DiInterface;
//use Phalcon\Mvc\Router;
use Phalcon\Mvc\UrlInterface;
use Phalcon\Mvc\Url\Exception;
use Phalcon\Di\InjectionAwareInterface;

/**
 * Phalcon\Mvc\Url
 *
 * This components helps in the generation of: URIs, URLs and Paths
 *
 *<code>
 * // Generate a URL appending the URI to the base URI
 * echo $url->get("products/edit/1");
 *
 * // Generate a URL for a predefined route
 * echo $url->get(
 *     [
 *         "for"   => "blog-post",
 *         "title" => "some-cool-stuff",
 *         "year"  => "2012",
 *     ]
 * );
 *</code>
 */
class Url extends \Phalcon\Mvc\Url implements UrlInterface, InjectionAwareInterface
{

	private static $_scheme = [
		'https' => true,
		'http'  => true,
	];

	/**
	 * Generates a URL
	 *
	 *<code>
	 * // Generate a URL appending the URI to the base URI
	 * echo $url->get("products/edit/1");
	 *
	 * // Generate a URL for a predefined route
	 * echo $url->get(
	 *     [
	 *         "for"   => "blog-post",
	 *         "title" => "some-cool-stuff",
	 *         "year"  => "2015",
	 *     ]
	 * );
	 *
	 * // Generate a URL with GET arguments (/show/products?id=1&name=Carrots)
	 * echo $url->get(
	 *     "show/products",
	 *     [
	 *         "id"   => 1,
	 *         "name" => "Carrots",
	 *     ]
	 * );
	 *
	 * // Generate an absolute URL by setting the third parameter as false.
	 * echo $url->get(
	 *     "https://phalconphp.com/",
	 *     null,
	 *     false
	 * );
	 *</code>
	 */
	/**
	 * @param null $uri
	 * @param null $args
	 * @param null $local
	 * @param null $baseUri
	 * @return mixed
	 * @throws Exception
	 */
	public function get($uri = null, $args = null, $local = null, $baseUri = null)
	{
		$uriNew = parent::get($uri, $args, $local, $baseUri);

		if(isset($uri['for'])){

		    /** @var Router $router */
			$router = $this->_router;
			$hostName = ((string)$router->getRouteByName($uri['for'])->getHostname());
			if($hostName){
				if(isset($uri['scheme']) && isset(self::$_scheme[$uri['scheme']])){
					$hostName = $uri['scheme'] .'://'. $hostName;
				}else{
					$hostName = '//' . $hostName;
				}
			}

			$uriNew =  $hostName. $uriNew;
		}
		return $uriNew;
	}


}

