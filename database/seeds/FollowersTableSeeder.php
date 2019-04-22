<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //第一个用户关注所有

        $users = User::all();
        $user = $users->first();
        $user_id = $user->id;
            //slice 方法返回集合从指定索引开始的一部分切片：
        $followers = $users->slice(1);

        $follower_ids = $followers->pluck('id')->toArray();
        $user->follow($follower_ids);


        //所有用户关注第一个用户
        foreach ($followers as $follower){
            $user->follow($user_id);
        }

    }
}
