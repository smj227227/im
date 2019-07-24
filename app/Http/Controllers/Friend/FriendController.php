<?php
/*
    *author:123
    *time 2019/4/24 3:25 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Friend;


use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WebSocket\WebSocketController;
use App\Http\Model\AddFriend;
use App\Http\Model\Friend;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class FriendController extends Controller
{
    public static function getFriend(){
        $uid = UserController::getUserId();
        $friend_id = Friend::getUserFriendId($uid);
        if(!empty($friend_id)){
            $friend_msg = Friend::getUserFriendMsg($friend_id);
        }
        return $friend_msg??[];
    }

    public function getRequestManage(){
        $uid = UserController::getUserId();
        $data = Friend::getRequestManage($uid);
        return view('Mobile.Friend.search',['data'=>$data]);

    }


    public static function addFriend($uid,$friend_id){
        $status =Friend::addFriend($uid,$friend_id);
        if($status){
           $online =  WebSocketController::isUidOnline($uid);
           if($online){
                $message['msgType'] = 0;
                $message['showType'] = 1;
                WebSocketController::sendUid($friend_id,$message);
           }
           return true;
        }
        return false;
    }




    public function Agree($friend_id){
      $uid = UserController::getUserId();
      Redis::sadd('friend:'.$uid,$friend_id);
      Redis::sadd('friend:'.$friend_id,$uid);
      Redis::srem('add_friend:'.$uid,$friend_id);
      AddFriend::where(['uid'=>$uid,'friend_id'=>$friend_id])->update(['status'=>1]);
      $friend = new Friend();
      $friend->uid = $uid;
      $friend->fid = $friend_id;
      $friend->save();

      $toFriend = new Friend();
      $toFriend->uid = $friend_id;
      $toFriend->fid = $uid;
      $toFriend->save();
      ChatController::assArray(['uid'=>$uid,'friend_id'=>$friend_id,'type'=>0]);
      return ['code'=>200];

    }

    public function Refuse($friend_id){
        $uid = UserController::getUserId();
        Redis::srem('add_friend:'.$uid,$friend_id);
        AddFriend::where(['uid'=>$uid,'friend_id'=>$friend_id])->update(['status'=>2]);
        return ['code'=>200];

    }
}