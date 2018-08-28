<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Auth;

Use App\Models\User;

class FollowersController extends Controller
{
    public function store(User $user){
        if(Auth::user()->id==$user->id){
            return redirect('/');
        }

        if(!Auth::user()->isFollowing($user->id)){
            Auth::user()->follow($user->id);
        }

        return redirect()->route('users.show',$user->id);
    }



    /**
     * @detial 取消关注
     * @param User $user
     * @author mgg
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(User $user){
        if(Auth::user()->id==$user->id){
            return redirect('/');
        }


        if(Auth::user()->isFollowing($user->id)){//是否已关注过

            Auth::user()->unFollow($user->id);//取消关注
        }

        return redirect()->route('users.show',$user->id);//跳转回$user的详情页
    }
}
