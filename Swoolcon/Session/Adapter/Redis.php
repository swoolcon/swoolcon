<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-16
 * Time: 下午1:46
 */
namespace Swoolcon\Session\Adapter;

use Phalcon\Cache\Backend\Redis as RedisCache;
use Phalcon\Cache\Frontend\Data;
use Swoolcon\Session\Adapter;

class Redis extends Adapter
{
    private $_prefixRoot = 'swoolcon::';
    private $_prefixDefault = 'swoole_session';

    protected function createCacheAdapter()
    {
        $lifeTime = $this->getSessionLifeTime();

        //文件缓存
        $options = $this->_options;

        //prefix
        if(!isset($options['prefix'] )) $options['prefix'] = $this->_prefixDefault;
        $options['prefix'] = $this->_prefixRoot . $options['prefix'];
        //

        $this->_cache = new RedisCache(new Data(['lifetime' => $lifeTime]), $options);

    }
}