<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_ids=['1','51','52'];
        //等同于:use Faker\Generator as Faker; 用app() 方法来获取一个 Faker 容器 的实例，并借助 randomElement 方法来取出用户 id 数组中的任意一个元素并赋值给微博的 user_id，使得每个用户都拥有不同数量的微博。
        $faker = app(Faker\Generator::class);

        $statuses=factory(Status::class)->times(50)->make()->each(function ($status) use($faker,$user_ids){
            $status->user_id=$faker->randomElement($user_ids);//随机指定$user_ids中的任意id;
        });

        Status::insert($statuses->toArray());
    }
}
