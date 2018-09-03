<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{

    public function __construct()
    {
        /**
         * 只让未登录用户访问注册页面：已登录的用户laravel会默认跳转到,/home 因我们并没有此页面，所以会报错找不到页面。我们需要修改下中间件里的 redirect() 方法调用，并加上友好的消息提醒：
         *   app/Http/Middleware/RedirectIfAuthenticated.php 中修改handle()方法;
         */
        $this->middleware('guest',['only'=>['create']]);//我们除了可通过 Auth 中间件的 auth 属性来对控制器的一些动作进行过滤，只允许已登录用户访问之外。还可以使用 Auth 中间件提供的 guest 选项，用于指定一些只允许未登录用户访问的动作;
    }


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
    /**
     * @SWG\Post(
     *     path="/login",
     *     summary="登录接口",
     *     tags={"Session"},
     *     @SWG\Parameter(
     *         name="email",
     *         in="header",
     *         description="账户名(邮箱)",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="It's ok"
     *     ),
     *     @SWG\Response(
     *         response="default",
     *         description="unexpected error"
     *     )
     * )
     */
    public function store(Request $request){
        $credentials = $this->validate($request,['email'=>'required|email|max:244','password'=>'required']);
        //验证成功时$credentials返回包含所有入参的关联数组,验证失败返回false;
        if(Auth::attempt($credentials,$request->has('remember'))){//该方法验证第一个参数验证用户是否存在,密码是否正确;成功登录设置用户会话信息,第二个参数是是否设置记住我布尔类型;需要引入 Auth;
            if(Auth::user()->activated) {//验证通过且账户已激活才能正常登陆
                session()->flash('success', '欢迎回来~');

                return redirect()->intended(
                    route('users.show', [Auth::user()])
                );//提升友好性通过route()->intended()方法重定向到上次访问的路由,intended()接收参数为当上次路由为空时默认要跳转的路由;
                //return redirect()->route('users.show',[Auth::user()]);//重定向页面并将数据传送给路由 Auth::user()方法来获取当前登录用户的信息。
            }else{//账户还未激活,提醒激活
                Auth::logout();
                session()->flash('warning','该账户还未激活,请检测邮箱中的激活邮件进行激活.');
                return redirect('/');
            }
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
