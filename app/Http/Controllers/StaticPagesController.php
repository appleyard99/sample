<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class StaticPagesController extends Controller
{

    public function home(){
        $feed_items=[];
        if(Auth::check()){
            $feed_items=Auth::user()->feed()->paginate(100);
           /* $laQuery = DB::getQueryLog();
            var_dump($laQuery);
            DB::disableQueryLog();*/
            //打印完整sql,要先将illuminate\database\Connection中$loggingQueries = false改为$loggingQueries = true
            //然后通过 $laQuery = DB::getQueryLog()获取sql语句及参数绑定的数据信息;

        }
        return view('static_pages/home',compact('feed_items'));
    }

    public function help(){
        return view('static_pages/help');
    }
    public function about(){
        return view('static_pages/about');
    }

}
