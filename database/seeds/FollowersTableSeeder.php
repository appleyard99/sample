<?php

use Illuminate\Database\Seeder;
use App\Models\User;
class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users= User::all();
        $user = $users->first();
        $user_id = $user->id;
        //slice(1)去掉id=1 的用户
        $followers = $users->slice(1);
        //获取集合中id字段并转化为数组;
        $follower_ids = $followers->pluck('id')->toArray();

        //id=1的用户关注除了他自己外的所有也用户;
        $user->follow($follower_ids);

        //id=1以外的用户都来关注id=1的用户(结合上面的即为与id=1的用户相互关注)
        foreach($followers as $follower){
            $follower->follow($user_id);
        }

    }
}
