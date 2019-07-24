<?php
/*
    *author:123
    *time 2019/4/24 3:28 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Index;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Friend\FriendController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\User\UserController;


class IndexController extends Controller
{
    public static function index(){
        $friend = json_encode(FriendController::getFriend());
        $group =json_encode(GroupController::getGroup());
        return view('Mobile.Index.index',['friend'=>$friend,'group'=>$group]);
    }
}