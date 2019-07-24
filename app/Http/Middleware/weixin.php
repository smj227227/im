<?php
/*
    *author:123
    *time 2019/4/22 4:49 PM
    *All rights reserved
*/


namespace App\Http\Middleware;


use App\Http\Controllers\User\UserController;
use Closure;

class weixin
{
    public function handle($request, Closure $next)
    {
       if(!self::is_weixin()){
           return $next($request);
       }
        return view('Mobile.weixin');
    }

    public static function is_weixin(){
        if ( strpos($_SERVER['HTTP_USER_AGENT'],

                'MicroMessenger') !== false ) {

            return true;

        }

        return false;
    }
}