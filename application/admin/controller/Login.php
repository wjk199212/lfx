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
//        退出登录 置空session 并跳转到登录页面
        session('adminLoginVal',null);
        $this->redirect('admin/Login/in');
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
//            当验证信息有误时输出错误提示
         if ($val !==true){
             return $this->error($val);
         }
//         DB获取数据库
           $admin= \think\Db::table('admin')->where('mobile',$data['mobile'])->find();
//         $admin= admin::where('mobile',$data['mobile'])->find();
//         如果在数据库中没找到用户名就输出错误信息
         if (!$admin){
             $this->error('您输入的账户密码有误');
         }
//         密码验证成功，借助session 登录成功并跳转登录后的页面
            if (password_verify($data['password'],$admin['password'])){
//             用session 记录当前登录状态
             session('adminLoginVal',$admin);
             $this->success('登录成功',url('admin/Index/index'));
         }else{
             $this->error('您输入的账户密码有误');
         }

        }
//        处理get请求
        if ($res->isGet()){
//            输出
            return $this->fetch();

        }
    }

}