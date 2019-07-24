<?php
/*
    *author:123
    *time 2019/4/22 4:54 PM
    *All rights reserved
*/


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class User extends Model
{
    public  $dateFormat = 'U';
    public $table ='user';

    public static function getUser($id){
        $user = Redis::hget('user',$id);
        if ($user){
            return json_decode($user,true);
        }

        $user = User::select('id','username','phone','avatar')->where('id',$id)->first();
        if($user){
            $user = $user->toArray();
            Redis::hset('user',$user['id'],json_encode($user,JSON_UNESCAPED_UNICODE));
            return $user;
        }
            return false;

    }


    public static function getPhone($phone){
        $user = User::select('id','username','phone','avatar')->where('phone',$phone)->first();
        if($user){
            return $user->toArray();
        }else{
            return false;
        }
    }
}