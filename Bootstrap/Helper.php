<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午3:03
 */

if (!function_exists('app_path')) {
    /**
     * 相对项目根目录的文件/目录
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return BASE_PATH . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config_path')) {

    /**
     * 配置文件夹
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app_path('Config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('content_path')) {

    /**
     * @param string $path
     * @return string
     */
    function content_path($path = '')
    {
        return app_path('Content') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}


if (!function_exists('logs_path')) {

    /**
     * 日志文件夹
     * @param string $path
     * @return string
     */
    function logs_path($path = '')
    {
        return content_path('Logs') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('cache_path')) {

    /**
     *
     * @param string $path
     * @return string
     */
    function cache_path($path = '')
    {
        return content_path('Cache') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('modules_web_path')) {
    /**
     * Get the modules path.
     *
     * @param  string $path
     * @return string
     */
    function modules_web_path($path = '')
    {
        return app_path('App' . DIRECTORY_SEPARATOR . 'WebModules') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('modules_cli_path')) {
    /**
     * Get the modules path.
     *
     * @param  string $path
     * @return string
     */
    function modules_cli_path($path = '')
    {
        return app_path('App' . DIRECTORY_SEPARATOR . 'CliModules') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('content_modules_path')) {

    function content_modules_path($path = '')
    {
        return content_path('Modules') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
