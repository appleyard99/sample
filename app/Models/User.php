<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * 指定表名
     * @var string
     */
    protected $table='users';
    /**
     * The attributes that are mass assignable.
     * 只有以下属性才能被更新;
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *以下属性将在对用户密码或其它敏感信息在用户实例通过数组或 JSON 显示时进行隐藏
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Eloquent 模型默认提供了多个事件，我们可以通过其提供的事件来监听到模型的创建，更新，删除，保存等操作。
     * creating 用于监听模型被创建之前的事件，created 用于监听模型被创建之后的事件。
     * @author mgg
     */
    public static function boot()//boot 方法会在用户模型类完成初始化之后进行加载，因此我们对事件的监听需要放在该方法中。
    {
        parent::boot();

        static::creating(function($user){
            $user->activation_token = str_random(30);
        });

    }

    /**
     * @detail 指定用户与微博的一对多的关系
     * @param int $size
     * @author mgg
     * @return string
     */
     public function statuses(){
         return $this->hasMany(Status::class);
     }


    //获取用户头像;
    public function gravatar($size=100){
        $hash=md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    public function sendPasswordResetNotification($token)
    {

        $this->notify(new ResetPassword($token));
    }

    /**
     * @detail 获取用户发布的微博列表
     * @author mgg
     */
    public function feed(){

        return $this->statuses()->orderBy('created_at','desc');
    }
}
