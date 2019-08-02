<?php
/*
    *author:123
    *time 2019/4/22 4:49 PM
    *All rights reserved
*/


namespace App\Http\Middleware;


use App\Http\Controllers\User\UserController;
use Closure;

class login
{
    public function handle($request, Closure $next)
    {
        $data['token'] = $request->header('token');
        $data['uid'] = $request->header('uid');
        if(!empty($data['token']) && !empty($data['uid'])){
            $status = UserController::checkToken($data);
            if($status){
                return $next($request);
            }
        }
        return ['code'=>32001];

    }
}