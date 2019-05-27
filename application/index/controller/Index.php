<?php

namespace app\index\controller;

use app\admin\model\article;
use app\admin\model\category;
use think\Controller;
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

class Index extends Controller
{
    public function news()
    {
        $id = $this->request->param('id',0);
//        print_r($id);
        $this->assign('id',$id);
//        查出分类中的所有子类信息
        $category=$this->categoryList(1);
        $categories = [];
        foreach ($category as $v){
            $categories[] =$v['id'];
        }
        if ($id){
            $categoryInfo =category::where('id',$id)->find();
            $this->assign('categoryInfo',$categoryInfo);
//            return $this->fetch();
            $list = article::where('category_id','in',$categories)
                ->where('status',1)
                ->order('create_time DESC')
                ->paginate(2);

        }else{
            $this->assign('categoryInfo','');
            $list = article::where('category_id','in',$categories)
                ->where('status',1)
                ->order('create_time DESC')
                ->paginate(2);
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    public function detail(){
        $category = $this->categoryList(1);
//        文章ID
        $id =$this->request->param('id');
        $info = article::get($id);
        $this->assign('info',$info);
//        更新阅读次数
        $info->setInc('hits');
        return $this->fetch();
    }
//    分类列表
    protected function categoryList($id){

        $category = category::where('pid',$id)->select();
        $this->assign('category',$category);
        return $category;
    }
//    关于我们
    public function about(){
//        分类ID
        $id = $this->request->param('id');
        $this->categoryList('5');
        $info = article::where('category_id',$id)->find();
        $this->assign('info',$info);
        $this->assign('id',$id);
        return $this->fetch();
    }
}
