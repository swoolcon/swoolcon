Swoolcon
========

为了让swoole作为服务器运行phalcon的框架，现强行将 phalcon 和 swoole 组合在在一起，基本完成了http server。

## 安装

### 安装phalcon 扩展

参考[phalcon](https://github.com/phalcon/cphalcon)

    $ git clone --depth=1 git://github.com/phalcon/cphalcon.git
    $ cd cphalcon/build
    $ sudo ./install
        
### 安装swoole 扩展

参考[swoole](https://github.com/swoole/swoole-src)

    $ git clone https://github.com/swoole/swoole-src.git
    $ cd swoole-src
    $ phpize
    $ ./configure
    $ make && make install
        
### 安装swoolcon

    $ git clone https://github.com/swoolcon/swoolcon
    
    $ cd swoolcon
        
### 运行程序

1. 简单运行

        #即swoole server 的启动脚本写在 swoole_swoole.php 这个文件夹里面，仅作为一个参考
        
        $ php swoole_swoole.php
        
2. 用phalcon 的命令行应用，即task脚本
    * 默认
        这种方式是phalcon 的多模块应用，启动脚本在 [App/CliModules/Server/Tasks/MainTask.php](https://github.com/swoolcon/swoolcon/blob/master/App/CliModules/Server/Tasks/MainTask.php)
    
            php server.php
            
    * Micro
      
        这种方式是phalcon的微应用，启动脚本在 [App/CliModules/Server/Tasks/MicroTask.php](https://github.com/swoolcon/swoolcon/blob/master/App/CliModules/Server/Tasks/MicroTask.php)
    
            php server.php micro
            
    * restful demo
    
        基于micro写的一个restful demo，启动脚本在 [App/CliModules/Server/Tasks/RestfulTask.php](https://github.com/swoolcon/swoolcon/blob/master/App/CliModules/Server/Tasks/RestfulTask.php)
        
            php server.php restful
            
3. 浏览器输入 host:port 即可正常使用。本机在默认情况下 localhost:9999 就可以运行

## 静态资源文件的处理

默认不处理静态文件，静态文件会被当成uri解析。静态文件可以交给nginx处理，动态请求通过nginx转发给swoolcon。

以下为nginx的配置文件：

    server {
        root /path/to/swoolcon/Public/;
        server_name swoolcon.dev;
    
        location / {
            proxy_http_version 1.1;
            proxy_set_header Connection "keep-alive";
            proxy_set_header X-Real-IP $remote_addr;
            if (!-f $request_filename) {
                 proxy_pass http://127.0.0.1:9999;
            }
        }
    }

## 文档

请参照 [phalcon](https://phalcon.link/docs) 和 [swoole](https://wiki.swoole.com/) 的官方文档

本产品还在以爬行的速度开发中，可能会出现一些神奇的问题，建议看官以参考为主

目前还在整理开发中遇到的坑。


## 参考代码

* [phanbook](https://github.com/phanbook/phanbook)
    
    参考了她对application的封装
    
    