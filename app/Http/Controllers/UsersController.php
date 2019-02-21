<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function __construct()
    {
        //除了此处指定的动作以外，所有其他动作都必须登录用户才能访问
        $this->middleware('auth', [
            'except' => ['create', 'store']
        ]);
        //只允许登录用户访问之外
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    //用户信息展示
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    //用户注册页面
    public function create()
    {
        return view('users.create');
    }

    //用户注册表单提交
    public function store(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users|max:191',
            'password'=>'required|confirmed|min:6'
        ]);


        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password)
        ]);

        Auth::login($user);

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }

    //用户修改界面
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    //用户修改提交 PATCH
    public function update(User $user, Request $request)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show', $user);
    }
}
