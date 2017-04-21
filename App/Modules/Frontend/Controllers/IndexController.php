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

    }

    public function testAction()
    {
        var_dump('test');
        var_dump($this->request->get());
        return false;
    }
}