<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //运行UserTableSeeder
        $this->call(UsersTableSeeder::class);

        Model::reguard();
    }
    //之后通过$ php artisan migrate:refresh $ php artisan db:seed 执行数据生成;
}
