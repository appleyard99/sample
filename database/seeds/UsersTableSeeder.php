<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * 通过定义的factory 生成相应User数据
     * times 和 make 方法是由 FactoryBuilder 类 提供的 API。times 接受一个参数用于指定要创建的模型数量，make 方法调用后将为模型创建一个 集合。
     * makeVisible 方法临时显示 User 模型里指定的隐藏属性 $hidden，接着我们使用了 insert 方法来将生成假用户列表数据批量插入到数据库中。
     * 最后我们还对第一位用户的信息进行了更新，方便后面我们使用此账号登录。
     * 下一步要在DatabaseSeeder.php中调用call方法指定要运行假数据填充的文件;
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //User::class->指定UserFactory类;times()指定次数
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password','remember_token'])->toArray());

        //同时修改第一个用户信息;
        $user = User::find(1);
        $user->name ='apple';
        $user->email='apple_mgg@sina.com';
        $user->password=bcrypt('123456');
        $user->is_admin=true;
        $user->save();
    }
}
