<?php
/*
    *author:123
    *time 2019/4/29 2:45 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Chat;


use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WebSocket\WebSocketController;
use App\Jobs\ChatLogJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;


class ChatController extends Controller
{

    public static function peerToPeer($data){
        if(WebSocketController::isUidOnline($data['to_id'])){
            WebSocketController::sendUid($data['to_id'],$data);
            dispatch(new ChatLogJob(json_encode($data)))->onQueue('chatLog');
        }else{
            Redis::rpush('offline_msg:'.$data['to_id'],json_encode($data));
        }
    }


    public static function userAddFriend($data){
        if(WebSocketController::isUidOnline($data['to_id'])){
            WebSocketController::sendUid($data['to_id'],$data);
        }else{
            Redis::rpush('offline_msg:'.$data['to_id'],json_encode($data));
        }
    }

    public static function assArray($data){
        switch ($data['type']){
            case 0: //下发互加好友通知
                $mine = UserController::getUser($data['uid']);
                $to = UserController::getUser($data['friend_id']);
                $newArr['msgType'] = 0;
                $newArr['showType'] = 2;
                $newArr['type'] = 'friend';
                $newArr['id'] = $mine['id'];
                $newArr['username'] = $mine['username'];
                $newArr['avatar'] = $mine['avatar'];
                $newArr['to_id'] = $to['id'];
                self::userAddFriend($newArr);
                $newArr['to_id'] = $mine['id'];
                $newArr['id'] = $to['id'];
                $newArr['username'] = $to['username'];
                $newArr['avatar'] = $to['avatar'];
                self::userAddFriend($newArr);

                break;
            case 1: //点对点
                $message = $data['mine'];
                $message['msgType'] = 1;
                $message['to_id'] = $data['to']['id'];
                self::peerToPeer($message);
                break;
        }

    }

    public static function peerToGroup($data){
        $message['msgType'] = 2;
        $message['username'] = $data['mine']['username'];
        $message['avatar'] = $data['mine']['avatar'];
        $message['mine_id'] = $data['mine']['id'];
        $message['content'] = $data['mine']['content'];
        $message['group_id'] = $data['to']['id'];
        WebSocketController::sendGroup($message['group_id'],$message);
        dispatch(new ChatLogJob(json_encode($message)))->onQueue('chatLog');
    }


    public function friendLog($id){
        $uid = UserController::getUserId();
        $uuidArr = [$id,$uid];
        arsort($uuidArr);
        $uuid = implode('',$uuidArr);
        $data = Redis::lrange('friend_log:'.$uuid,0,99);
        foreach ($data as $k=>$v){
            $arr[] = json_decode($v,true);
        }
        return view('Mobile.Chat.chatFriend',['data'=>$arr]);
    }

    public function groupLog($id){
        $data = Redis::lrange('group_log:'.$id,0,1000);
        if(!empty($data)){
            foreach ($data as $k=>$v){
                $arr[] = json_decode($v,true);
            }
        }
        return view('Mobile.Chat.chatGroup',['data'=>$arr??[]]);
    }
}