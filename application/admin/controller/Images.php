<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/24
 * Time: 下午7:30
 */
namespace app\admin\controller;



use think\Controller;

class images extends Controller
{
    public function pig()
    {
     $re = $this->request;
//    处理POST请求
     if ($re->isPost()){
//         验证数据

















     }
     if ($re->isGet()){
         return $this->fetch();
     }




































    }
}