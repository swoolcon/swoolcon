<?php

namespace Swoolcon\ServiceProvider;
use Swoolcon\ServiceProvider;
use Swoolcon\Http\Cookie as SwoolconCookie;


class SwooleCookieServiceProvider extends ServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'Phalcon\\Http\\Cookie';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->set($this->serviceName, function ($name, $value = null, $expire = 0, $path = "/", $secure = null, $domain = null, $httpOnly = null){
            $cookie = new SwoolconCookie($name, $value, $expire, $path, $secure, $domain, $httpOnly);
            return $cookie;

        });
    }
}
