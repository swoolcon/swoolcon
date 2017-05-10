<?php

namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Swoolcon\SwoolconStaticProperty;


class SwooleStaticPropServiceProvider extends ServiceProvider
{
    //有没有必要全部用这个
    const SERVICE_NAME = 'swooleStaticProperty';
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'swooleStaticProperty';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $di = $this->di;
        $di->setShared(
            $this->serviceName,
            function () use($di){

                return new SwoolconStaticProperty();
            }
        );
    }
}
