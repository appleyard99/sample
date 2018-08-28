<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
Route::get('/','StaticPagesController@home')->name('home');
Route::get('/help','StaticPagesController@help')->name('help');
Route::get('/about','StaticPagesController@about')->name('about');
Route::get('signup','UsersController@create')->name('signup');// laravl自动适配/*和 * 都可以

Route::resource('users','UsersController');
//resource()等同于以下路由集合:
/*
Route::get('/users','UsersController@index')->name('users.index');//获取用户列表
Route::get('/users/create','UsersController@create')->name('users.create');//获取用户创建页面
Route::get('/users/{user}','UsersController@show')->name('users.show');//获取用户个人信息
Route::post('/users','UsersController@store')->name('users.store');//添加用户
Route::get('/users/{user}/edit','UsersController@edit')->name('users.edit');//编辑用户页面信息
Route::patch('/users/{user}','UsersController@update')->name('users.update');//更新用户信息;
Route::delete('/users/{user}','UsersController@destroy')->name('users.destroy');//删除用户
*/

Route::get('login','SessionsController@create')->name('login');//显示登录页面;
Route::post('login','SessionsController@store')->name('login');//创建会话登录
Route::delete('logout','SessionsController@destroy')->name('logout');//退出登录(销毁会话)

Route::get('signup/confirm/{token}','UsersController@confirmEmail')->name('confirm_email');

//Laravel 的通知系统默认支持邮件频道的通知方式，我们只需要稍作配置即可。ForgotPasswordController,ResetPasswordController laravel自带的密码重置功路由中配置一下,vendor里notifications文件用pushlishcopy的可用目录下该功能就可以使用了;
Route::get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//微博管理路由配置;使用resource方法,用only指定只生成新建和删除的路由;
Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

//获取关注的人列表;
Route::get('users/{user}/followings','UsersController@followings')->name('users.followings');
//获取粉丝列表
Route::get('/users/{user}/followers','UsersController@followers')->name('users.followers');

//关注用户
Route::post('/users/followers/{user}','FollowersController@store')->name('followers.store');
//取消关注
Route::delete('users/followers/{user}','FollowersController@destroy')->name('followers.destroy');

