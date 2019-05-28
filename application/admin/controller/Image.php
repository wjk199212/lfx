<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/24
 * Time: 下午7:30
 */
namespace app\admin\controller;



use app\admin\model\category;
use app\admin\model\images;
use think\Controller;

class image extends Controller
{
    public function add(){
         if($this->request->isGet()){

         $lists = category::where('pid', 0)->select();
         $this->assign('list', $lists);
         return $this->fetch();

        }
         if ($this->request->isPost()){

        $thumbs = $this->request->param('xxxx');

        $id = $this->request->param('category');
        $data = [];
        foreach ($thumbs as $v){
            $data[] = ['category_id' => $id, 'location'=> $v];
        }
        $image = new images();
//        var_dump($image);
        if ($image->saveAll($data)){
            $this->success('成功');
        }else{
            $this->error('失败');
        }
        }
    }

     public function lists()
     {
         $id = $this->request->param('id');

         if (empty($id)){
             $where = [];
         }else{
             $where['category_id'] = $id;
         }


         $list = \app\admin\model\images::where($where)->select();
         $this->assign('list', $list);

         $categoryList = category::where('type',2)->select();
         $this->assign('categoryList', $categoryList);
         return $this->fetch();
     }

    public function getImageCategory()
    {
        $id = $this->request->param('id');
        $list = category::where('type', 2)->where('pid', $id)->select();
        return json($list);
    }
    public function pig(){
        return $this->fetch();

    }

}