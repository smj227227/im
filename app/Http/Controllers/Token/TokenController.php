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
        $data = $request->all();
        if(!empty($data['uid']) && !empty($data['phone'])){
            return ['code'=>200,'data'=>self::Token($data['uid'],$data['phone'])];
        }else{
            return ['code'=>parent::$RequestParameterError];
        }
    }


    public static function Token($uid,$phone){
        $token  = md5($phone.time());
        $expiration_time = time()+self::$time;
        Redis::setex('token:'.$token,self::$time,$uid);
        return ['token'=>$token,'expiration_time'=>$expiration_time];
    }
}