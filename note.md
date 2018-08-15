###### Laravel 5.1 速查表
https://cs.laravel-china.org/

######Laravel tink 快速生成数据
php artisan tinker 进入tinker 命令行;
命令使用如下:
>>> App\Models\User::create(['name'=> 'Summer', 'email'=>'summer@yousails.com','password'=>bcrypt('password')])
在命令行使用模型相关方法如:create,find等;

#######Laravel 默认为我们集成了 Faker 扩展包，使用该扩展包可以让我们很方便的生成一些假数据。
假数据的生成分为两个阶段：
1.对要生成假数据的模型指定字段进行赋值 - 『模型工厂』；
2.批量生成假数据模型 - 『数据填充』；
模型工厂:
Laravel 默认为我们集成了 Faker 扩展包，使用该扩展包可以让我们很方便的生成一些假数据。
数据填充:
在 Laravel 中我们使用 Seeder 类来给数据库填充测试数据。所有的 Seeder 类文件都放在 database/seeds 目录下，
文件名需要按照『驼峰式』来命名，且严格遵守大小写规范。
#生成Seeder类命令:
php artisan make:seeder UsersTableSeeder
Laravel 默认为我们定义了一个 DatabaseSeeder 类，我们可以在该类中使用 call 方法来运行其它的 Seeder 类，以此控制数据填充的顺序
接着我们还需要在 DatabaseSeeder 中调用 call 方法来指定我们要运行假数据填充的文件(即运行其他的Seeder类,此类可看作是个总入口)。

#命令行执行数据填充:
$ php artisan migrate:refresh #在运行生成假数据的命令之前，我们需要使用 migrate:refresh 命令来重置数据库
$ php artisan db:seed #使用 db:seed 执行数据填充。
如果我们要单独指定执行 UserTableSeeder (即单独执行某个Seeder类,上面则某人执行databaseSeeder中call中所有的seeder类)数据库填充文件，则可以这么做：
$ php artisan migrate:refresh
$ php artisan db:seed --class=UsersTableSeeder
===>等效于 php artisan migrate:refresh --seed;
######分页
1.控制器中:
$users = User::paginate(10);//每页10条获取数据;
return view('users.index', compact('users'));
2.view中使用render()渲染:
{!! $users->render() !!} #固定格式
由 render 方法生成的 HTML 代码默认会使用 Bootstrap 框架的样式，渲染出来的视图链接也都统一会带上 ?page 参数来设置指定页数的链接。另外还需要注意的一点是，
渲染分页视图的代码必须使用 {!! !!} 语法，而不是 {{　}}，这样生成 HTML 链接才不会被转义。
######PHP artisan vendor:publish --tag=Laravel-notifications 这段命令是什么意思?
项目通常有很多扩展，而每个扩展可能用到一些配置文件 View 之类的资源
我们 composer require 安装完成一个扩展，这个扩展是存在vendor 目录的，这个目录是.gitignore，所以需要把资源Copy或者说发布到正确的地方。
所以，需要用到这个命令。
但是使用这个命令之前 ，通常要把provider进行配置关联，如 config/app.php



