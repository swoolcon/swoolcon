<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-21
 * Time: 下午4:57
 */
namespace Swoolcon\Cli;
use Phalcon\Cli\Console as PhalconConsole;

class Console extends PhalconConsole
{
    protected $argc;

    protected $argv;

    /**
     * Set application arguments.
     *
     * @param array $argv Array of arguments passed to the application.
     * @param int   $argc The number of arguments passed to the application.
     *
     * @return $this
     */
    public function setArgs($argv, $argc)
    {
        $this->argv = $argv;
        $this->argc = $argc;

        return $this;
    }

    public function run()
    {
        $arguments = $this->getArguments();

        $this->handle($arguments);
    }

    protected function getArguments()
    {
        //$argv = $_SERVER['argv'];
        $argv = $this->argv;

        $arguments = [];
        $params    = [];

        foreach ($argv as $i => $arg) {
            if ($i == 1) {
                $arguments['task'] = $arg;
            } elseif ($i === 2) {
                $arguments['action'] = $arg;
            } elseif ($i >= 3) {
                $params[] = $arg;
            }
        }

        $params = $this->convertParams($params);
        if (count($params)) {
            $arguments['params'] = $params;
        }

        return $arguments;
    }

    protected function convertParams(array $params)
    {
        $result = [];

        foreach ($params as $param) {
            if (preg_match('/^(\-\-|\-)(.*)=(.*)$/', $param, $matches)) {
                $result[$matches[2]] = $matches[3];
            } elseif (preg_match('/^(\-\-|\-)(.*)$/', $param, $matches)) {
                $result[$matches[2]] = true;
            } else {
                $result[] = $param;
            }
        }
        return $result;
    }
}