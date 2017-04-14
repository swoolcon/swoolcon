<?php
/**
 * @brief 由于swoole 要一直运行在内存中，所以一些静态属性就不能用了。。。，现将他们保存到这个类里面
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-24
 * Time: 下午1:33
 */
namespace Swoolcon;
class SwoolconStaticProperty{

    private $_values = [];

    private static function getKey($className,$prop)
    {
        return $className . '::' . $prop;
    }

    public function get($className,$prop,$default='')
    {
        $values = $this->_values;
        $key = self::getKey($className, $prop);
        return isset($values[$key]) ? $values[$key] : $default;
    }

    public function set($className,$prop,$value)
    {
        $key = self::getKey($className, $prop);
        $this->_values[$key] = $value;
        return $this;
    }
}