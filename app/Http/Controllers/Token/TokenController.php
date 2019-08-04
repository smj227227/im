<?php
/*
    *author:123
    *time 2019/8/2 5:21 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Token;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TokenController extends Controller
{

    public static $time = 3600000;

    public function reload(Request $request){
        $uid = $request->header('x-uid');
        return ['code'=>200,'data'=>self::Token($uid)];
        }



    public static function Token($uid){
        $token  = md5($uid.time());
        $expiration_time = time()+self::$time;
        Redis::setex('token:'.$token,self::$time,$uid);
        return ['token'=>$token,'expiration_time'=>$expiration_time,'uid'=>$uid];
    }
}