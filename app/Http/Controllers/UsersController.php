<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    public function create(){

        return view('users.create');
    }

    public  function show(User $user){
        return view('users.show',compact('user'));
    }

    public function store(Request $request){
        $result=$this->validate($request,['name'=>'required','email'=>'required|email|unique:users|max:255','password'=>'required|confirmed|min:6']);
        //$request->all(); 获取所有请求字段
        $user=User::create(['name'=>$request->name,'email'=>$request->email,'password'=>bcrypt($request->password)]);
        Auth::login($user);
        //添加注册成功的会话提醒:
        session()->flash('success','欢迎,您将开启一段新的旅程~');
        return redirect()->route('users.show',$user);
        /**
         * 注意这里是一个『约定优于配置』的体现，此时 $user 是 User 模型对象的实例。route() 方法会自动获取 Model 的主键，也就是数据表 users 的主键 id，以上代码等同于：
         * redirect()->route('users.show', [$user->id]);
         * redirect()->route('users.show', $user);也可以
         *
         */

    }
}
