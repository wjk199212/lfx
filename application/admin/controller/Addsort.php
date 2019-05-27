<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/25
 * Time: 上午9:14
 */
namespace app\admin\controller;

use think\Controller;

class Addsort extends Controller
{
    public function sort()
    {
        return $this->fetch();
       $re = $this->request;
//       $this->request->param();

       if ($re->isGet()){
           $pid = $re->param('id',0);

           if (empty($pid)){
               $this->assign('parentName','顶级分类');
           }else{
               $parentName =\think\Db::table('album')->where('id',$pid)->value('name');
               if (!$parentName){
                   $this->error('非法操作');
               }
               $this->assign('parentName',$parentName);
               $this->assign('pid',$pid);

           }

       }
       if ($re->isPost()){
           $pid = $re->param('pid',0);
           if (mb_strlen($name,'utf-8')>10||mb_strlen($name,'utf-8')<2){
               $this->error('分类名应在2-10位之间');
           }
           $where = ['pid'=>$pid,'name'=>$name];
           $res = \think\Db::table('album')->where($where)->find();
           if ($res){
               $this->error('该分类已存在');
           }
           if ($pid == 0){
               $level = 0;
               $path ='0-';
           }else{
               $parent= \think\Db::table('album')->where('id',$pid)->find();
               if (empty($parent)){
                   $this->error('非法操作');
               }
               $level = $parent->level +1;
               $path = $parent ->path.$pid.'-';
           }
           $data = [
               'name'=>$name,
               'pid'=>$pid,
               'level'=>$level,
               'path'=>$path
           ];

       }
    }
}