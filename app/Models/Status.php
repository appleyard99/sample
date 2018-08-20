<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    /**
     * 指定表名
     * @var string
     */
    protected $table='statuses';

    /**
     * 创建微博时会报 MassAssignmentException - 批量赋值异常，这是因为我们未在微博模型中定义 fillable 属性，
     * 来指定在微博模型中可以进行正常更新的字段，Laravel 在尝试保护。解决的办法很简单，
     * 在微博模型的 fillable 属性中允许更新微博的 content 字段即可
     */
    protected  $fillable=['content'];

    /**
     * 指定微博与用户多对一的关系
     * @author mgg
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class);
    }

}
