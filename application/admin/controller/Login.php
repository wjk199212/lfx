<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/20
 * Time: 下午8:00
 */
namespace app\admin\controller;

use think\Controller;

class Login extends Controller{
//    退出登录
    public function out(){

    }





//    登录
    public function in(){
        $res = $this->request;
//        处理post请求
        if ($res->isPost()){
//            接收数据
          $data=$res->only(['mobile','password']);
//          验证数据

           $rule=[
               'mobile'=>'require|mobile',
               'password'=>'require|length:2,12'
           ];
           $msg=[
               'mobile.require'=>'请输入手机号码',
               'mobile.mobile'=>'请输入正确的手机号',
               'password.require'=>'请输入密码',
               'password.length'=>'密码长度应该在2-12位之间',

           ];
         $val=$this->validate($data,$rule, $msg);
//         判断是否登录成功
         if ($val !==true){
             return $this->error($val);

         }

        }
//        处理get请求
        if ($res->isGet()){
//            输出
            return $this->fetch();

        }






    }

}