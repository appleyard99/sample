<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    //使用中间件过滤
    public function __construct()
    {
        $this->middleware('auth',['except'=>['show','create','store','index']]);//Laravel 提供的 Auth 中间件在过滤指定动作时，如该用户未通过身份验证（未登录用户），默认将会被重定向到 /login 登录页面
        $this->middleware('guest',['only'=>['create']]);//登录页面只允许为登录的用户(guest)访问;
    }

    /**
     * 获取用户列表接口
     * @author mgg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $users = User::paginate(10);//对用户列表进行分页,view中配合使用{!! $users->render() !!}}
        return view('users.index',compact('users'));
    }
    /**用户注册页面
     * @author mgg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        return view('users.create');
    }

    /**
     * 用户信息页
     * @param User $user
     * @author mgg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public  function show(User $user){
        return view('users.show',compact('user'));
    }

    /**用户注册
     * @param Request $request
     * @author mgg
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * 用户编辑界面
     * @param User $user
     * @author mgg
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user){
        $this->authorize('update',$user);//默认的 App\Http\Controllers\Controller 类包含了 Laravel 的 AuthorizesRequests trait。此 trait 提供了 authorize 方法
        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request)
    {
        $this->authorize('update',$user);//第一个参数为策略名,第二个为要应用的用户
        $result = $this->validate($request, ['name' => 'required', 'password' => 'nullable|confirmed|min:6']);//密码可为空即不做修改
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {//密码可不做修改
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success','修改成功~');

        return redirect()->route('users.show',$user->id);
    }

    public function destroy(User $user){
        $this->authorize('destroy',$user);//应用授权策略;
        $user->delete();
        session()->flash('success','操作成功');
        return back();//重定向到上一次进行删除操作的页面，即用户列表页
    }



}
