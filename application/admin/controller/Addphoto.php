<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/25
 * Time: 上午8:55
 */
namespace app\admin\controller;


use think\Controller;

class Addphoto extends Controller
{
//添加文章
    public function add()
    {
        $re = $this->request;
//     处理POST请求
        if ($re->isPost()) {
//         验证信息
            $data = $re->only(['title', 'category_id', 'author', 'content', 'status', 'thumb', 'minthumb']);
            $rule = [
                'title' => 'require|length:1,20',
                'category_id' => 'require|min:1',
//            gt 也可以验证  >
                'author' => 'require|length:2,10',
                'content' => 'require|length:10,65535',
                'status' => 'in:0,1'
            ];

            $msg = [
                'title.require' => '标题为必填项',
                'title.length' => '标题应在1-20个字符之间',
                'category_id.require' => '请选择正确的分类信息',
                'category_id.min' => '请选择正确的分类信息',
                'ayrhor.require' => '请输入正确的署名',
                'author.length' => '署名应该在2-10之间',
                'content.require' => '文章内容为必填项',
                'content.length' => '文章内容过短或者过长',
                'status.in' => '文章状态有误'

            ];
            $check = $this->validate($data, $rule, $msg);
//         var_dump($check);
//         exit();
//         如果数据验证失败
//         if (category::value('id',0)){
//             $this->error($check);
//         }

            if ($check !== true) {
                $this->error($check);
            }

//         记录session
            $data['aid'] = session('adminLoginVal')->id;


//         入库
//         create 写入数据 （data） 写入数据库数据

            if (\app\admin\model\article::create($data)) {
//             入库成功并跳转页面
                $this->success('添加成功', url('admin/Article/lists'));
            } else {
                $this->error('添加失败');
            }

        }
//     处理get请求
        if ($re->isGet()) {
//        获取分类信息
            $all = category::where('pid', 0)->all();
//         assign 模板变量赋值
            $this->assign('all', $all);
            return $this->fetch();

        }
    }

//    用Ajax获取文章分类
    public function ajaxCategory()
    {
        $pid = $this->request->param('id', 0);
        $data = category::where('pid', $pid)->select();
        return json($data);
    }


//     文章列表
    public function lists()
    {
//order 排序  paginate 分液器  with 关联数据库
        $list = \app\admin\model\article::with('category')->order('create_time DESC')->paginate(2);
//          $list=  \app\admin\model\article::with('category')->order('create_time DESC')->paginate(1);
//            $list = \app\admin\model\article::get(1);
        $this->assign('list', $list);
        return $this->fetch();

    }
}