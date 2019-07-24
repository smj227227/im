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
        $token = $request->cookie('token');
        $user = $request->cookie('user');
        if(!empty($token) && !empty($user)){
            $data['uid'] = json_decode($user,true)['id'];
            $data['token'] = $token;
            $status = UserController::checkToken($data);
            if($status){
                return $next($request);
            }
        }
        return redirect('/login');

    }
}