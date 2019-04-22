<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //获取用户头像
    public function gravatar($size = '100')
    {
        //通过 $this->attributes['email'] 获取到用户的邮箱；
        //类似 $this->email    底层是 attributes['email']
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    //boot 方法会在用户模型类完成初始化之后进行加载
    public static function boot()
    {
        parent::boot();
        //created 用于监听模型被创建之后的事件
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    //一人可拥有多条微博
    public function statuses()
    {
        return $this->hasMany(Status::class, 'user_id', 'id');
    }

    public function feed()
    {
        return $this->statuses()->orderBy('created_at', 'desc');
    }

    //获取粉丝关系列
    //多对多
//    $this->belongsToMany(关联的模型类名, 中间表表名,
//    当前模型在中间模型中的外键名称, 关联模型在中间模型的外键名称);
    //用户拥有的所有粉丝 (我关注了谁)
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    //谁关注了我
    public function followings()
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    //关注用户
    public function follow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    //取消关注用户
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    //是否关注
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }

}
