<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/21
 * Time: 上午8:35
 */
namespace app\admin\controller;

use think\Controller;

class index extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
    public function console()
    {
        return  $this->fetch();
    }
//    添加分类
    public function addCategory()
    {
        $res = $this->request;
//        处理get请求
        if ($res->isGet()) {

            $pid = $res->param('id',0);

            if (empty($pid)){
                $this->assign('parentName','顶级分类');

            }else{
                $parentName = category::where('id',$pid)->value('name');
                if (!$parentName){
                    $this->error('非法操作');
                }
                $this->assign('parentName',$parentName);
            }
            $this->assign('pid',$pid);
            return $this->fetch();
        }

















    }





























}

