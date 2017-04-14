<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-2-27
 * Time: 下午3:32
 */
namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Phalcon\Events\Manager;

class EventManagerServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'eventsManager';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function () {
                /** @var \Phalcon\DiInterface $this */
                $em = new Manager();
                $em->enablePriorities(true);    //启用优先级

                return $em;
            }
        );
    }
}