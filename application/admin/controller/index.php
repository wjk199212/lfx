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
//        处理post请求
        if ($res->isPost()){
            $name = $res->param('name');
            $pid = $res->param('pid',0);
//判断字符在2-10之间
            if (mb_strlen($name,'utf-8')>10 || mb_strlen($name, 'utf-8')<2){
                $this->error('分类名称长度应在2-10位之间');
            }
//            同一个父级下不能重名
            $where = ['pid'=>$pid,'name'=>$name];
//            判断是否重名
            if (category::where($where)->find()){
                $this->error('该分类已存在');
            }
            $parent = category::where('id',$pid)->find();
//            入库操作
            $data = [
                'name'=>$name,
                'pid'=>$pid,
                'level'=>$parent->level + 1
            ];
            if (category::create($data)){
                $this->success('成功');
            }else{
                $this->error('失败');
            }

        }

    }
//    分类列表
     public function categoryList(){
//        如果使用的Ajax请求
        if ($this->request->isAjax()){
            $pid = $this->request->param('id',0);
            $list = category::where('pid',$pid)->select();
            $str = '';
            foreach($list as $v){
                $space = '';
                for ($i=0;$i<$v['level'];$i++){
                    $space .= '&nbsp;&nbsp;&nbsp;&nbsp';
                }
                $url = url('admin/Index/addCategory',['id'=>$v['id']]);
//                $str.=
            }
        }

     }






























}

