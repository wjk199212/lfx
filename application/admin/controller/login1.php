<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/23
 * Time: 下午7:09
 */
namespace app\admin\controller;


use app\admin\model\admin;
use think\Controller;

class Login extends Controller
{
//    退出登录
    public function out()
    {

    }
//    登录
    public function in()
    {
        $re=$this->request;
//        处理GET请求
     if ($re->isGet()){
         return $this->request;

     }
//     处理POST请求
     if ($re->isPost()){
//         接收需要验证的信息
         $data = $re->only(['mobile','password']);
//         验证信息
         $rule =[
             'mobile' =>'require|mobile',
             'password' =>'require|length:2,12'
         ];
         $msg = [
             'mobile.require'=>'请输入账号',
             'mobile.mobile'=>'请输入正确的手机号',
             'password.require'=>'请输入密码',
             'password.length'=>'密码应在2-12位之间'
         ];
        $val= $this->validate($data,$rule,$msg);
//        判断是否登录成功
         if ($val !==true){
             return $this->error($val);
         }
         $admin = admin::where('mobile',$data['mobile'])->find();
         if (!$admin){
             $this->error('您输入的账号密码有误');
         }
         if (password_verify($data['password'],$admin['password'])){
             session('adminLoginVal',$admin);
             $this->success('登录成功',url('admin/Index/index'));
         }else{
             $this->error('您输入的账户密码有误');
         }

     }

    }
}