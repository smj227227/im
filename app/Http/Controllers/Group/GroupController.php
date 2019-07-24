<?php
/*
    *author:123
    *time 2019/4/24 3:38 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Group;


use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\WebSocket\WebSocketController;
use App\Http\Model\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GroupController extends Controller
{

    public function lists(){
        $data = self::getGroup();
        return view('Mobile.Group.group',['data'=>$data]);
    }

    public function message($id){
        $uid = UserController::getUserId();
        $group = json_decode(Redis::hget('group',$id),true);
        $userRes = Redis::SRANDMEMBER('group:'.$id,10);
        $user = Redis::hmget('user',$userRes);
        foreach ($user as $k=>$v){
            if(!$v){
                $arr[$k] = UserController::getUser($userRes[$k]);
             }else{
                $arr[$k] = json_decode($v,true);
            }
        }
        return view('Mobile.Group.groupMessage',['user'=>$arr,'group'=>$group,'admin_id'=>$uid]);
    }


    public function create(Request $request){
        $uid = UserController::getUserId();
        $group_name = $request->get('group_name');
        $status = Group::create($uid,$group_name);
        if($status){
            $message['msgType'] = 0;
            $message['showType'] =3;
            $message['groupname']  = $group_name;
            $message['avatar'] = 'https://im.cdn.caomei520.com/null.jpg';
            $message['id'] = $status;
            WebSocketController::userBindGroup($uid,$status);
            WebSocketController::sendUid($uid,$message);
            return ['code'=>200,'data'=>['group_name'=>$group_name,'gid'=>$status]];
        }
        return ['code'=>400];

    }

    public static function getGroup(){
        $uid = UserController::getUserId();
        $data = Group::getUserGroup($uid);

        return $data;
    }


    public static function bindUserGroup($uid){
        $data = Group::getUserGroup($uid);
        foreach ($data as $k=>$v){
            WebSocketController::userBindGroup($uid,$v['id']);
        }
    }




    public static function searchGroup($id){
         return  Group::getGroup($id);
    }


    public static function addUserGroup($gid){
        $uid = UserController::getUserId();
        $status = Group::addUserGroup($uid,$gid);
        $group = self::searchGroup($gid);
        if($status){
            $message['msgType'] = 0;
            $message['showType'] = 3;
            $message['groupname']  = $group['groupname'];
            $message['avatar'] = 'https://im.cdn.caomei520.com/null.jpg';
            $message['id'] = $gid;
            WebSocketController::sendUid($uid,$message);
            WebSocketController::userBindGroup($uid,$gid);
            return true;
        }
        return false;

    }


    public function del($gid){
        $uid = UserController::getUserId();
        $status = Group::del($uid,$gid);
        if($status){
            $message['msgType'] = 0;
            $message['showType'] = 3;
            $message['id'] = $gid;
            WebSocketController::sendUid($uid,$message);
            WebSocketController::delUserGroup($uid,$gid);
            return ['code'=>200];
        }
        return ['code'=>400];

    }
}