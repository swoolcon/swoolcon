<?php
/**
 * @brief
 * Created by PhpStorm.
 * User: zy&cs
 * Date: 17-3-29
 * Time: 下午5:35
 */
namespace App\Modules\Frontend\Controllers;
class IndexController extends ControllerBase
{

    public function indexAction()
    {

        var_dump('hello swoolcon');
        return false;
    }

    public function testAction()
    {
        var_dump('test');
        return false;
    }
}