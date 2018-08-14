<?php

use Faker\Generator as Faker;

/*
 * 定于用户数据生成工厂
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
   $date_time = $faker->date.' '.$faker->time;//使用生成的假日期对用户的创建时间和更新时间进行赋值。
    static $password;

    return [
        'name' => $faker->name,
        //'email' => $faker->unique()->safeEmail,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password=bcrypt('secret'), // secret
        'remember_token' => str_random(10),
        'is_admin'=>false,
        'created_at'=>$date_time,
        'updated_at'=>$date_time,
    ];
});
