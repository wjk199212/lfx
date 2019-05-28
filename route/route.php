<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//用户登录的路由
Route::rule('login', 'admin/Login/in')->method('GET,POST');

Route::group('', function (){

    Route::get('logout', 'admin/Login/out');

//后台首页
    Route::get('admin$', 'admin/Index/index');

//console页
    Route::get('admin-console', 'admin/Index/console');

    Route::rule('admin-add-category', 'admin/Index/addCategory')->method('GET,POST');
    Route::get('admin-list-category', 'admin/Index/categoryList');
    Route::get('admin-tree-category', 'admin/Index/categoryTree');
//添加文章
    Route::rule('admin-article-add', 'admin/Article/add')->method('GET,POST');
//ajax获取文章分类
    Route::post('admin-article-category', 'admin/Article/ajaxCategory');
    Route::post('admin-article-change-status', 'admin/Article/changeStatus');
    Route::post('admin-article-upload-image', 'admin/Article/uploadImage');
    Route::rule('admin-article-ueupload', 'admin/Article/ueUpload')->method('GET,POST');
    Route::rule('admin-article-list', 'admin/Article/lists')->method('GET,POST');

    Route::rule('admin-image/[:id]$', 'admin/Image/lists')->method('GET,POST');
    Route::rule('admin-image-add', 'admin/Image/add')->method('GET,POST');
    Route::rule('admin-image-category', 'admin/Image/getImageCategory')->method('GET,POST');

})->middleware('Login');







Route::get('news/[:id]$', 'Index/index/news');
//限制变量的规则，可选参数不受规则限制
//Route::get('news/:id', 'Index/index/news')->pattern(['id'=>'\d+']);
//Route::get('news/:id', 'Index/index/news', [], ['id'=>'\d+']);
Route::get('news/detail/[:id]', 'Index/index/detail');
Route::get('about/:id', 'Index/index/about');
//Route::get('image/[:id]$', 'Index/index/image');