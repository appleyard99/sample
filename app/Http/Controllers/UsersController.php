<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Mail;
class UsersController extends Controller
{
    //使用中间件过滤
    public function __construct()
    {
        $this->middleware('auth',['except'=>['show','create','store','index','confirmEmail']]);//Laravel 提供的 Auth 中间件在过滤指定动作时，如该用户未通过身份验证（未登录用户），默认将会被重定向到 /login 登录页面
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
        //Auth::login($user);//用户登录;
        $this->sendEmailConfirmationTo($user);

        //添加注册成功的会话提醒:
        session()->flash('success','验证邮件已发送到您邮箱,请注意查收~');
        //return redirect()->route('users.show',$user);
        return redirect('/');//验证邮件后才可以登录,此处返回到首页;
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

    /**
     * @detail 删除用户接口
     * @param User $user
     * @author mgg
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user){
        $this->authorize('destroy',$user);//应用授权策略;
        $user->delete();
        session()->flash('success','操作成功');
        return back();//重定向到上一次进行删除操作的页面，即用户列表页
    }

    /**
     * @detail 注册成功后向用户邮箱发送邮件;
     * @param $user
     * @author mgg
     */
    public function sendEmailConfirmationTo($user){
        header("Content-type: text/html; charset=utf-8");
        $view="emails.confirm";
        $data=compact('user');
        $from="apple_mgg@sina.com";
        $name='Apple';
        $to=$user->email;
        $subject="感谢注册sample,请确认你的邮箱";

        Mail::send($view,$data,function($message) use($from,$name,$to,$subject){
            $message->from($from,$name)->to($to)->subject($subject);
        });
    }

    /**
     * @detail 邮箱激活账户
     * @author mgg
     */
    public  function confirmEmail($token){
        //根据token值查找账户信息;
        /**
         * Eloquent 的 where 方法接收两个参数，第一个参数为要进行查找的字段名称，第二个参数为对应的值，查询结果返回的是一个数组，因此我们需要使用 firstOrFail 方法来取出第一个用户，
         * 在查询不到指定用户时将返回一个 404 响应。在查询到用户信息后，
         * 我们会将该用户的激活状态改为 true，激活令牌设置为空。
         */
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated=true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success','恭喜您,激活成功');

        return redirect()->route('users.show',[$user]);
    }




}
