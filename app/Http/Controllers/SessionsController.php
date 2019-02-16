<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    //登录页面
    public function create()
    {
        return view('sessions.create');
    }

    //登录提交表单
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email'=>'required|email|max:191',
            'password'=>'required'
        ]);
        //attempt 验证并登录
        if(Auth::attempt($credentials)){
            session()->flash('success', '欢迎回来');
            return redirect()->route('users.show', [Auth::user()]);
        }else{
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
        return ;
    }
}