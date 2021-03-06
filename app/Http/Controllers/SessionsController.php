<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        //只允许登录用户访问之外
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

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
        if(Auth::attempt($credentials, $request->has('remember'))){
            if(Auth::user()->activated){
                session()->flash('success', '欢迎回来');
//            return redirect()->route('users.show', [Auth::user()]);
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
            }else{
                $userCon = new UsersController();
                $userCon->sendEmailConfirmationTo(Auth::user());

                Auth::logout();
                session()->flash('warning','您的账户未激活，邮件已重发，请检测邮箱中的注册邮件进行激活');
                return redirect('/');
            }


        }else{
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    //退出登录
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
