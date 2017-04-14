<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-15
 * Time: 下午5:07
 */

/*
 +------------------------------------------------------------------------+
 | Phalcon Framework                                                      |
 +------------------------------------------------------------------------+
 | Copyright (c) 2011-2016 Phalcon Team (https://phalconphp.com)          |
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

namespace Swoolcon\Session;

use Phalcon\Cache\BackendInterface;
use Phalcon\DiInterface;
use Phalcon\Events\Manager;
use Phalcon\Http\RequestInterface;
use Phalcon\Http\Response\CookiesInterface;
use Phalcon\Http\ResponseInterface;
use Phalcon\Security\Random;
use Phalcon\Session\AdapterInterface;
use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Session\Exception;
use Swoolcon\Http\Response\Cookies;

/**
 * Phalcon\Session\Adapter
 *
 * Base class for Phalcon\Session adapters
 */
abstract class Adapter implements AdapterInterface, InjectionAwareInterface
//abstract class SwooleAdapter implements AdapterInterface
{
    const SESSION_ACTIVE = 2;

    const SESSION_NONE = 1;

    const SESSION_DISABLED = 0;

    protected $cookieLifetime = 86400000;

    protected $cookieDomain = null;

    protected $cookiePath = '/';

    protected $sessionLifetime = 0;

    protected $_uniqueId;

    protected $_started = false;

    protected $_options;

    /**
     * @var RequestInterface
     */
    protected $_swooleRequest = null;

    /**
     * @var ResponseInterface
     */
    protected $_swooleResponse = null;

    /**
     * @var CookiesInterface
     */
    protected $_swooleCookie = null;

    private $_sessionName = 'SWSESSID';

    private $_sessionId = '';

    /**
     * @var DiInterface
     */
    protected $di = null;

    protected $_sessionData = [];

    /**
     * @var BackendInterface
     */
    protected $_cache = null;


    /**
     * Phalcon\Session\Adapter constructor
     *
     * @param array options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }

        $this->createCacheAdapter();
    }

    public function setDI(DiInterface $diInterface)
    {
        $this->di = $diInterface;
    }

    public function getDI()
    {
        return $this->di;
    }


    private function checkRequireService()
    {
        if (!$this->di || !($this->di instanceof DiInterface)) {
            throw new Exception('A dependency injection object is required to access the "SwooleSession" service');
        }

        //cookies
        $cookies = $this->di->getShared('cookies');
        if (!$cookies || !($cookies instanceof Cookies)) {
            throw new Exception('The "' . Cookies::class . '" is not in the dependency injection object');
        }

        $this->_swooleCookie = $cookies;

    }

    private function getRandomId()
    {
        $random = new Random();
        return $random->base58(40);
    }

    /**
     * Starts the session (if headers are already sent the session will not be started)
     */
    public function start()
    {
        if ($this->_started === true) {
            return false;
        }

        //检查需求
        $this->checkRequireService();

        //先看有没有设置
        if (!$this->_sessionId) {
            //再看有没有cookie 里面有没有 sessionid
            $sessionId = $this->_swooleCookie->get($this->_sessionName)->getValue('string', '');

            if (!$sessionId) {
                $this->setId($this->getRandomId());
            } else {
                $this->_sessionId = $sessionId;
            }
        }

        $this->saveToCookie();

        //cache get,save to array _sessionData
        $this->sessionReadFromCache();
        $this->_started = true;

        /** @var Manager $eventManager */
        $eventManager = $this->di->getShared('eventsManager');
        $eventManager->attach('response:beforeSendCookies', function () {
            $this->sessionSaveToCache();
        });
        return true;
    }


    private function saveToCookie()
    {
        $this->_swooleCookie->set($this->_sessionName, $this->_sessionId, time() + $this->cookieLifetime, $this->cookiePath, null, $this->cookieDomain);
    }

    /**
     * Sets session's options
     *
     *<code>
     * $session->setOptions(
     *     [
     *         "uniqueId" => "my-private-app",
     *         'savaPath' => ''
     *     ]
     * );
     *</code>
     */
    public function setOptions(array $options)
    {
        if (isset($options['uniqueId'])) {
            $this->_uniqueId = $options['uniqueId'];
        }
    }

    /**
     * Get internal options
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * Set session name
     */
    public function setName($name)
    {
        //session_name(name);
        $this->_sessionName = $name;
    }

    /**
     * Get session name
     */
    public function getName()
    {
        //return session_name();
        return $this->_sessionName;
    }

    /**
     * {@inheritdoc}
     */
    public function regenerateId($deleteOldSession = true)
    {
        //session_regenerate_id($deleteOldSession);
        if ($deleteOldSession === true) {
            $this->_cache->delete($this->_sessionId);
        }
        $this->_sessionId = $this->getRandomId();
        $this->saveToCookie();

        //cache

        return $this;
    }

    /**
     * Gets a session variable from an application context
     *
     *<code>
     * $session->get("auth", "yes");
     *</code>
     *
     * @param string $index
     * @param null $defaultValue
     * @param bool $remove
     * @return null
     */
    public function get($index, $defaultValue = null, $remove = false)
    {
        $uniqueId = $this->_uniqueId;
        if (!empty ($uniqueId)) {
            $key = $uniqueId . "#" . $index;
        } else {
            $key = $index;
        }

        if (isset($this->_sessionData[$key])) {
            if ($remove) {
                unset($this->_sessionData[key]);
            }
            return $this->_sessionData[$key];
        }
        return $defaultValue;
    }

    /**
     * Sets a session variable in an application context
     *
     *<code>
     * $session->set("auth", "yes");
     *</code>
     * @param string $index
     * @param mixed $value
     */
    public function set($index, $value)
    {

        $uniqueId = $this->_uniqueId;
        if (!empty ($uniqueId)) {
            $this->_sessionData[$uniqueId . "#" . $index] = $value;
            return;
        }

        $this->_sessionData[$index] = $value;
    }

    /**
     * Check whether a session variable is set in an application context
     *
     *<code>
     * var_dump(
     *     $session->has("auth")
     * );
     *</code>
     * @param string $index
     * @return bool
     */
    public function has($index)
    {
        $uniqueId = $this->_uniqueId;
        if (!empty ($uniqueId)) {
            return isset($this->_sessionData[$uniqueId . "#" . $index]);
        }

        return isset ($this->_sessionData[$index]);
    }

    /**
     * Removes a session variable from an application context
     *
     *<code>
     * $session->remove("auth");
     *</code>
     * @param string $index
     */
    public function remove($index)
    {
        $uniqueId = $this->_uniqueId;
        if (!empty ($uniqueId)) {
            unset($this->_sessionData[$uniqueId . "#" . $index]);
            return;
        }

        unset ($this->_sessionData[$index]);
    }

    /**
     * Returns active session id
     *
     *<code>
     * echo $session->getId();
     *</code>
     */
    public function getId()
    {
        return $this->_sessionId;
        //return session_id();
    }

    /**
     * Set the current session id
     *
     *<code>
     * $session->setId($id);
     *</code>
     * @param $sessionId
     */
    public function setId($sessionId)
    {
        $this->_sessionId = $sessionId;
    }

    /**
     * Check whether the session has been started
     *
     *<code>
     * var_dump(
     *     $session->isStarted()
     * );
     *</code>
     */
    public function isStarted()
    {
        return $this->_started;
    }

    /**
     * Destroys the active session
     *
     *<code>
     * var_dump(
     *     $session->destroy()
     * );
     *
     * var_dump(
     *     $session->destroy(true)
     * );
     *</code>
     * @param bool $removeData
     * @return bool
     */
    public function destroy($removeData = false)
    {
        $this->_sessionData = [];

        $this->_started = false;
        //return session_destroy();
        return true;
    }

    /**
     * Returns the status of the current session.
     *
     *<code>
     * var_dump(
     *     $session->status()
     * );
     *
     * if ($session->status() !== $session::SESSION_ACTIVE) {
     *     $session->start();
     * }
     *</code>
     */
    public function status()
    {
        $status = session_status();
        if ($status === PHP_SESSION_DISABLED) {
            return self::SESSION_DISABLED;
        }

        if ($this->_started === true) {
            return self::SESSION_ACTIVE;
        }

        return self::SESSION_NONE;
    }

    protected function getSessionLifeTime()
    {
        $lifeTime = intval($this->sessionLifetime);
        if ($lifeTime <= 0) {
            $lifeTime = 1440;
        }
        return $lifeTime;
    }

    /**
     * @return mixed
     */
    abstract protected function createCacheAdapter();


    protected function sessionSaveToCache()
    {
        $this->_cache->save($this->getId(), $this->_sessionData);
    }

    protected function sessionReadFromCache()
    {
        $this->_sessionData = $this->_cache->get($this->getId());
    }

    /**
     * Alias: Gets a session variable from an application context
     * @param $index
     * @return null
     */
    public function __get($index)
    {
        return $this->get($index);
    }


    public function __set($index, $value)
    {
        return $this->set($index, $value);
    }


    public function __isset($index)
    {
        return $this->has($index);
    }


    public function __unset($index)
    {
        return $this->remove($index);
    }

    public function __destruct()
    {
        if ($this->_started) {
            //session_write_close();
            $this->_started = false;
        }
    }
}
