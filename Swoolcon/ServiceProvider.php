<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-2-27
 * Time: 下午3:21
 */
namespace Swoolcon;
use Phalcon\Di\Injectable;
use Phalcon\DiInterface;

abstract class ServiceProvider extends Injectable implements ServiceProviderInterface
{
    protected $serviceName;


    final public function __construct(DiInterface $di)
    {
        if(!$this->serviceName){
            throw new \LogicException(
                sprintf('The service provider defined in "%s" cannot have an empty name.', get_class($this))
            );
        }

        $this->setDI($di);

        $this->configure();

    }

    public function getName()
    {
        return $this->serviceName;
    }

    public function boot(){}

    public function configure(){}

}