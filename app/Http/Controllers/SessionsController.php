<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    /**
     * 显示用户登录界面
     * @author mgg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('sessions.create');
    }

    /**
     * 用户登录
     * @author mgg
     */
    public function store(Request $request){
        $credentials = $this->validate($request,['email'=>'required|email|max:244','password'=>'required']);
        //验证成功时$credentials返回包含所有入参的关联数组,验证失败返回false;
        if(Auth::attempt($credentials,$request->has('remember'))){//该方法验证第一个参数验证用户是否存在,密码是否正确;成功登录设置用户会话信息,第二个参数是是否设置记住我布尔类型;需要引入 Auth;
            session()->flash('success','欢迎回来~');
            return redirect()->route('users.show',[Auth::user()]);//重定向页面并将数据传送给路由 Auth::user()方法来获取当前登录用户的信息。
        }else{
            session()->flash('danger','您的邮箱和密码不匹配~');

            return redirect()->back();//验证失败重定向到(上个路由)登录页面;
        }

    }

    public function destroy(){
        Auth::logout();//laravel 自带的退出
        session()->flash('success','您已经退出~');

        return redirect('login');//login 为路由别名
    }
}
