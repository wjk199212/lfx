<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/25
 * Time: 上午8:55
 */
namespace app\admin\controller;


use think\Controller;

class Addphoto extends Controller
{
    public function photo()
    {
        return $this->fetch();
    }
}