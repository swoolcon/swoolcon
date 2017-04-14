<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-14
 * Time: 下午2:36
 */
namespace Swoolcon;
use Phalcon\Di\InjectionAwareInterface;

interface ServiceProviderInterface extends InjectionAwareInterface{


    /**
     *
     * 注册服务
     *
     * @return void
     */
    public function register();


    /**
     * 启动方法
     *
     * @return void
     */
    public function boot();


    /**
     *
     * 设置当前服务
     * @return mixed
     */
    public function configure();


    /**
     *
     *
     * @return mixed
     */
    public function getName();
}

