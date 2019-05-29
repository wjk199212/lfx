<?php

namespace app\index\controller;

use app\admin\controller\image;
use app\admin\model\article;
use app\admin\model\category;
use app\admin\model\images;
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
//        print_r($category);
        foreach ($category as $v){
            $categories[] =$v['id'];
        }
        if ($id){

            $categoryInfo =category::where('id',$id)->find();
            $this->assign('categoryInfo',$categoryInfo);


            $list = article::where('category_id','in',$id)
                ->where('status',1)
                ->order('create_time DESC')
                ->paginate(10);
//            print_r($list);
//            print_r($categories);







        }else{
            $this->assign('categoryInfo','');
            $list = article::where('category_id','in',$categories)
                ->where('status',1)
                ->order('create_time DESC')
                ->paginate(10);
//            print_r($categories);
        }
        $this->assign('list',$list);
//        print_r($list);
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
        $id = $this->request->param('id',15);
        $this->categoryList('15');
        $info = article::where('category_id',$id)->find();
        $this->assign('info',$info);
        $this->assign('id',$id);
        return $this->fetch();
    }


    public function image(){
        $id = $this->request->param('id',20);
        $this->assign('id',$id);
        $category = $this->categoryList(19);
        if (empty($id)){
            $where = [];
        }else{
            $where['category_id'] = $id;
        }
        $list = images::where($where)->select();
        $this->assign('list',$list);
        $categoryList = category::where('type',2)->select();

        $this->assign('categoryList',$categoryList);
        $images = images::where('category_id',$id)->all();
        $this->assign('images',$images);
        return $this->fetch();
    }
    public function master(){
        return $this->fetch();
    }


}
