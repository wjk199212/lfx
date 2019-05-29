<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/21
 * Time: 下午4:29
 */
namespace app\admin\controller;
use app\admin\model\admin;
use think\Controller;
use app\admin\model\category;
class Article extends Controller
{
//添加文章
    public function add()
    {
     $re = $this->request;
//     处理POST请求
     if ($re->isPost()){
//         验证信息
         $data = $re->only(['title','category_id','author','content','status','thumb','minthumb']);
        $rule = [
            'title'      =>'require|length:1,50',
            'category_id'=>'require|min:1',
//            gt 也可以验证  >
            'author'     =>'require|length:2,10',
            'content'    =>'require|length:10,65535',
            'status'     =>'in:0,1'
        ];

        $msg =[
            'title.require'      =>'标题为必填项',
            'title.length'       =>'标题应在1-50个字符之间',
            'category_id.require'=>'请选择正确的分类信息',
            'category_id.min'    =>'请选择正确的分类信息',
            'ayrhor.require'     =>'请输入正确的署名',
            'author.length'      =>'署名应该在2-10之间',
            'content.require'    =>'文章内容为必填项',
            'content.length'     =>'文章内容过短或者过长',
            'status.in'          =>'文章状态有误'

        ];
         $check=$this->validate($data,$rule,$msg);
//         var_dump($check);
//         exit();
//         如果数据验证失败
//         if (category::value('id',0)){
//             $this->error($check);
//         }

         if ($check!==true){
             $this->error($check);
         }

//         记录session
         $data['aid']=session('adminLoginVal')->id;


//         入库
//         create 写入数据 （data） 写入数据库数据

         if (\app\admin\model\article::create($data)){
//             入库成功并跳转页面
             $this->success('添加成功',url('admin/Article/lists'));
         }else{
             $this->error('添加失败');
         }

     }
//     处理get请求
     if ($re->isGet()){
//        获取分类信息
         $all = category::where('pid',0)->all();
//         assign 模板变量赋值
         $this->assign('all',$all);
         return $this->fetch();

     }
    }


//    用Ajax获取文章分类
     public function ajaxCategory()
     {
      $pid = $this->request->param('id',0);
      $data = category::where('pid',$pid)->select();
      return json($data);
     }





//     文章列表
      public function lists()
      {
//order 排序  paginate 分液器  with 关联数据库

         $list= \app\admin\model\article::with('category')->order('create_time DESC')->paginate(2);
//          $list=  \app\admin\model\article::with('category')->order('create_time DESC')->paginate(1);
//            $list = \app\admin\model\article::get(1);
         $this->assign('list',$list);
         return $this->fetch();

      }




      public function changeStatus()
      {
       $id = $this->request->param('id');
       if (empty($id)){
           return $this->error('非法操作');
       }
       $obj = \app\admin\model\article::get($id);
       if (empty($obj)){
           return $this->error('非法操作');
       }
       $obj->status = abs($obj->status-1);
       if ($obj->save()){
           return $this->success('成功','',$obj->status);
       }else{
           return $this->error('失败');
       }
      }
//      删除
    public function delete()
    {

        $id = $this->request->param('id');

        if (empty($id)) {
            $this->error('失败');
        }
        $a=\app\admin\model\article::where('id',$id)->delete();
        if(empty($a)) {
            return $this->error('失败');
        }else{
            return $this->success('成功');
        }
    }
//修改
    public function upset()
    {
        $re = $this->request;
//        获取post请求
        if ($re->isPost()) {
            $id = $this->request->param('id');
//            验证信息
            $data = $re->only(['title', 'category_id', 'author', 'content', 'status']);
            $rule = [
                'title' => 'require|length:1,50',
                'category_id' => 'require|min:1',
                'author' => 'length:2,10',
                'content' => 'require|length:10,65535',
                'status' => 'in:0,1'
            ];
            $msg = [
                'title.require' => '文章标题为必填项',
                'title.length' => '文章标题应在1-50字之间',
                'category_id.require' => '请选择正确的分类信息',
                'category_id.min' => '请选择正确的分类信息',
                'author.length' => '署名长度应在2-10个字之间',
                'content.require' => '文章内容为必填项',
                'content.length' => '文章内容过短或者过长',
                'status.in' => '文章状态有误'
            ];
            $check = $this->validate($data, $rule, $msg);
//            如果验证信息不正确则输出错误提示
            if ($check !== true) {
                $this->error($check);
            }
            $aa =\app\admin\model\article::where('id',$id)->update($data);
            if ($aa) {
                $this->success('修改成功');
            } else {
                $this->error('修改失败');
            }

        }

//        获取get请求
        if ($re->isGet()) {

            $id = $this->request->param('id');
            $all = category::where('pid',0)->all();
            $this->assign('all',$all);
            //            把对象转换成数组  toArray
            $b= \app\admin\model\article::where('id',$id)->find()->toArray();
            $this->assign('b', $b);
            return $this->fetch();
        }
    }
//    图片上传
    public function uploadImage()
    {
        $image = $this->request->file('file');
        $res = $image->validate(['size'=>1048576, 'ext'=>'jpg,png,gif,jpeg'])->move('static/upload/');

        if ($res){
            //获取文件的保存路径
            //$res->getPath();
            //获取文件的保存文件名
            //$res->getFilename();

            //含有路径信息的文件名
            $path = $res->getPathname();
            //缩略图保存路径
            $min = $res->getPath().'/min'.$res->getFilename();

            $im = \think\Image::open($path);
            //裁剪
            //$im->crop(60, 60, \think\Image::THUMB_CENTER)->save($res->getPath().'/min'.$res->getFilename());
            //生成缩略图
            $im->thumb(60, 60, \think\Image::THUMB_CENTER)->save($min);
            return json(['code'=>1, 'thumb'=> $path, 'min'=> $min]);
        }else{
            return json(['code'=>0, 'info'=>$image->getError()]);
        }
    }
    public function umUploadImage()
    {
        if ($this->request->isGet()){
            $configData = file_get_contents("static/ui/library/ue/config.json");
            $config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", $configData), true);
            return json_encode($config);
        }

        if ($this->request->isPost()){
            $image = $this->request->file('upfile');
            $res = $image->validate(['size'=>1048576, 'ext'=>'jpg,png,gif,jpeg'])->move('static/upload/');
            if ($res){

                $info =  [
                    "originalName" => $res->getFilename() ,
                    "name" => $res->getSaveName() ,
                    "url" => $res->getPathname() ,
                    "size" => $res->getSize() ,
                    "type" => $res->getExtension() ,
                    "state" => 'SUCCESS'
                ];

                return json_encode($info);
            }
        }

    }
}