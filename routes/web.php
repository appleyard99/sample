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