<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Auth;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    //验证登录
    public function __construct()
    {
        $this->middleware('auth');
    }

    //添加微博
    public function store(Request $request)
    {

        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);

        session()->flash('success', '发布成功');
        return redirect()->back();
    }

    //删除微博
    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被删除成功');
        return redirect()->back();
    }
}
