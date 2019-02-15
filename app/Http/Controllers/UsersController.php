<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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

        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }


}
