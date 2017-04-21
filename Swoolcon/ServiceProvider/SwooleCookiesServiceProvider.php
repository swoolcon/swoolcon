<?php

namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Swoolcon\Http\Response\Cookies as SwoolconCookies;


class SwooleCookiesServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'cookies';

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
                $cookies = new SwoolconCookies();
                $cookies->setDI($di);
                $config = $this->get('config')->application->debug;
                if($config === true){
                    $cookies->useEncryption(false);

                }else{

                    $cookies->useEncryption(true);
                }
                return $cookies;
            }
        );
    }
}
