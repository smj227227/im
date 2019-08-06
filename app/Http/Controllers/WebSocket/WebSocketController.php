<?php
/*
    *author:123
    *time 2019/4/22 3:05 PM
    *All rights reserved
*/


namespace App\Http\Controllers\WebSocket;


use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\User\UserController;
use App\Jobs\OfflineMsg;
use GatewayWorker\Lib\Gateway;
use Illuminate\Support\Facades\Log;


class WebSocketController extends  Controller
{
    public static function onConnect($client_id){
        Gateway::sendToClient($client_id,json_encode(['a'=>123]));

    }

    public static function onMessage($client_id, $data)
    {
    Log::info($data);
        if(!is_array($data)){
            $data = json_decode($data,true);
            $status = UserController::checkToken($data);
            if($status){
                switch ($data['type']){
                    case 0:
                            //绑定uid,更改上线状态,通知该用户所有的好友
                            Gateway::bindUid($client_id,$data['uid']);
                            GroupController::bindUserGroup($data['uid']);
                            //dispatch(new OfflineMsg(json_encode(['uid'=>$data['uid']])))->onQueue('offlineMsg');
                        break;
                    case 1:
                        ChatController::assArray($data);
                        break;
                    case 2:
                        ChatController::peerToGroup($data);
                        break;
                    case 4:
                        //心跳 忽略
                        break;
                    case 5: //群组消息

                        break;
                    case 7:
                        //客户端申请通过要求准备推送群组消息

                    default :

                }
            }


        }
        echo "new connection from ip123 " . $client_id;

    }

    public static function isUidOnline($uid){
        if(Gateway::isUidOnline($uid)){
            return true;
        }
        return false;
    }

    public static function sendUid($uid,$data){
        Gateway::sendToUid($uid,json_encode($data,320));
    }

    public static function sendGroup($gid,$data){
        $client_id = Gateway::getClientIdByUid($data['mine_id']);
        Gateway::sendToGroup($gid,json_encode($data,JSON_UNESCAPED_UNICODE),$client_id);
    }

    public static function userBindGroup($uid,$group_id){

        $client_id = Gateway::getClientIdByUid($uid);
        if($client_id[0]){
            Gateway::joinGroup($client_id[0],$group_id);
        }

    }


    public static function delUserGroup($uid,$gid){
        $client_id = Gateway::getClientIdByUid($uid);
        if($client_id[0]){
            Gateway::leaveGroup($client_id[0],$gid);
        }
    }

    public static function sendAll($content = 'Test'){
        $data = [
            'username'=>"系统消息",
            'avatar'=>"http://www.qqpk.cn/Article/UploadFiles/201501/20150113145724330.jpg",
            'id'=>"10000",
            'type'=>"friend",
            'content'=>"这是来自系统的消息：".$content,
            'msgType' => 1
        ];
        Gateway::sendToAll(json_encode($data,320));
    }

    public static function onClose($client_id){

    }



}