<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{

    //一条微博属于一个用户
    public function user()
    {
        $this->belongsTo(User::class);
    }

}
