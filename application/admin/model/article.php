<?php
/**
 * Created by PhpStorm.
 * User: qingyun
 * Date: 19/5/21
 * Time: 下午4:31
 */
namespace app\admin\model;

use think\Model;

class article extends Model
{
    protected $autoWriteTimestamp = true;
    public function category()
    {
        return $this->belongsTo('category','category_id');
    }

}