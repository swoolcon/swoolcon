<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-4-27
 * Time: 上午10:58
 */
namespace App\CliModules\Main\Tasks;

use App\CliModules\Server\Tasks;

class MainTask extends Tasks
{
    public function mainAction()
    {
        fwrite(STDOUT, 'welcome to swoolcon cli' . PHP_EOL);
    }

    public function helloAction()
    {
        var_dump('say hello');
    }
}