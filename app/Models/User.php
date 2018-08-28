<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Status;
use Auth;
use Illuminate\Support\Facades\DB;

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

       $user_ids=$this->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
       $data = Status::whereIn('user_id',$user_ids)->orderBy('created_at','desc');
        return $data;

    }

    /**
     * @detail 获取用户粉丝
     * @author mgg
     */
    public function followers(){

        return $this->belongsToMany(User::class,'followers','user_id','follower_id');

    }

    /**
     * @detail 获取用户关注的人;
     * @author mgg
     */
    public function followings(){
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    /**
     * 在我们为用户和粉丝模型进行了多对多关联之后，便可以使用 Eloquent 模型为多对多提供的一系列简便的方法。
     * 如使用 attach 方法或 sync 方法在中间表上创建一个多对多记录，使用 detach 方法在中间表上移除一个记录，
     * 创建和移除操作并不会影响到两个模型各自的数据，所有的数据变动都在 中间表 上进行。attach, sync(与attach相比为不重复添加), detach 这几个方法都允许传入 id 数组参数
     * 如:>>> $user = App\Models\User::find(1)
     * >>> $user->followings()->attach([2, 3])
     * >>> $user->followings()->allRelatedIds()->toArray()
     * allRelatedIds 是 Eloquent 关联关系提供的 API，用来获取关联模型的 ID 集合。
     */

    /**
     * @detail 关注用户
     * @param $user_id
     * @author mgg
     */
    public function follow($user_id){
        if(!is_array($user_id)){
            $user_id=compact('user_id');
        }
        //attach可重复添加不去重, sync(与attach相比为不重复添加,去重)
        $this->followings()->sync($user_id,false);

    }

    /**
     * @detail 取消关注
     * @param $user_id
     * @author mgg
     */
    public function unfollow($user_id){

        if(!is_array($user_id)){
            $user_id=compact('user_id');
        }
        //取消关注
        $this->followings()->detach($user_id);
    }

    /**
     * @detail 是否关注了用户$userid
     * @param $userid
     * @author mgg
     * @return mixed
     */
    public function  isFollowing($userid){

        /**此处为什么用 $this->followings而不是 $this->followings();
         * $this->followings()
         * 1. 返回的是一个 HasMany 对象
         * $this->followings
         * 2. 返回的是一个 Collection 集合
         * 3. 第2个其实相当于这样
         * $this->followings()->get()
         * 如果不需要条件直接使用 2 那样，写起来更短
         * 4.因为contains方法是Collection类的一个方法，
         * $this->followings返回的是一个Collection类的实例，也就是一个集合，
         * 但是$this->followings()返回的是一个Relations，没有contains方法，所以不能加括号。
         *进一步解释:
         * 这里需要注意的是 Auth::user()->followings 的用法。我们在 User 模型里定义了关联方法 followings()，关联关系定义好后，我们就可以通过访问 followings 属性直接获取到关注用户的 集合。这是 Laravel Eloquent 提供的「动态属性」属性功能，我们可以像在访问模型中定义的属性一样，来访问所有的关联方法。
         *还有一点需要注意的是 $user->followings 与 $user->followings() 调用时返回的数据是不一样的， $user->followings 返回的是 Eloquent：集合 。而 $user->followings() 返回的是 数据库请求构建器 ，followings() 的情况下，你需要使用：
         *$user->followings()->get()
         *或者 ：
         *$user->followings()->paginate()
         *方法才能获取到最终数据。可以简单理解为 followings 返回的是数据集合，而 followings() 返回的是数据库查询语句。如果使用 get() 方法的话：
         *$user->followings == $user->followings()->get() // 等于 true
         */
        return $this->followings->contains($userid);
    }


}
