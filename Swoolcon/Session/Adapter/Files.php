<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-16
 * Time: 下午1:46
 */
namespace Swoolcon\Session\Adapter;

use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Frontend\Data;
use Swoolcon\Session\Adapter;

class Files extends Adapter
{

    protected function createCacheAdapter()
    {
        if (isset($this->_options['filePath']) && !is_dir($this->_options['filePath'])) {
            $path = $this->_options['filePath'];
        } else {
            $path = content_path('/Session/Swoole');
        }

        if (!is_dir($path)) {
            mkdir($path, 0755);
        }

        $lifeTime = $this->getSessionLifeTime();
        //文件缓存

        $this->_cache = new File(new Data(['lifetime' => $lifeTime]), ['cacheDir' => $path . '/']);
    }
}