<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //更新
    public function update(User $loginUser, User $user)
    {
        return $loginUser->id === $user->id;
    }

    //删除
    public function destroy(User $loginUser, User $user)
    {
        return $loginUser->is_admin && $loginUser->id !== $user->id;
    }

    //自己不能关注自己。故新增授权策略方法取名 follow()：
    public function follow(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id;
    }
}
