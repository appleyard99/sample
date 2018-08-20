<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Status;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request){

        $this->validate($request,['content'=>'required|max:140']);
        //因为创建微博的用户始终为当前用户，借助 Laravel 提供的 Auth::user() 方法我们可以获取到当前用户实例。在创建微博的时候，我们需要对微博的内容进行赋值，因此最终的创建方法如下：
        Auth::user()->statuses()->create(['content'=>$request['content']]);

        return redirect()->back();
    }

    /**
     * @detail 微博删除
     * @param Status $status
     * @author mgg
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
//前端传的是id,这里我们使用的是『隐性路由模型绑定』功能，Laravel 会自动查找并注入对应 ID 的实例对象 $status，如果找不到就会抛出异常。
    public function destroy(Status $status){
        $this->authorize('destroy',$status);//授权策略;前端也用@can('destroy',)控制了
        $status->delete();
        session()->flash('success','微博已被成功删除!');
        return redirect()->back();
    }
}
