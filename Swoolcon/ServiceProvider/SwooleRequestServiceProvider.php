<?php

namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Swoolcon\Http\Request as SwoolconRequest;


class SwooleRequestServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'request';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared($this->serviceName, SwoolconRequest::class);
    }
}
