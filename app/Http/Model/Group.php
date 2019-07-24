<?php
/*
    *author:123
    *time 2019/4/24 3:39 PM
    *All rights reserved
*/


namespace App\Http\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Group extends Model
{
    public $table = 'Group';

    public $dateFormat = 'U';

    public static function getUserGroup($uid){
        $group_res = Redis::smembers('user_group:'.$uid);
        if($group_res){
            $data = Redis::hmget('group',$group_res);
            foreach ($data as $k=>$v){
                if(!$v){
                    $group = Group::where('id',$group_res[$k])->first()->toArray();
                    Redis::hset('group',$group['id'],json_encode($group));
                    $arr[$k] = $group;
                }else{
                    $arr[$k] = json_decode($v,true);
                }
            }

            return $arr;
        }
         return [];

    }

    public static function getGroup($id){
        $group = Redis::hget('group',$id);
        if($group){
            return json_decode($group,true);
        }else{
            $group = Group::where('id',$id)->first();
            Redis::hset('group',$id,json_encode($group,JSON_UNESCAPED_UNICODE));
            if($group){
                return $group->toArray();
            }else{
                return false;
            }
        }
    }

    public static function create($uid,$group_name){
        $group = new Group();
        $group->groupname = $group_name;
        $group->uid = $uid;
        $group->save();
        if($group){
            Redis::hset('group',$group->id,json_encode(['id'=>$group->id,'groupname'=>$group_name,'uid'=>$uid,'avatar'=>'https://im.cdn.caomei520.com/null.jpg'],JSON_UNESCAPED_UNICODE));
            $status = Group::addUserGroup($uid,$group->id);
            if($status){
                return $group->id;
            }
        }
        return false;
    }

    public static function addUserGroup($uid,$groupId){
        $groupToUser = new GroupUser();
        $groupToUser->gid = $groupId;
        $groupToUser->uid = $uid;
        $groupToUser->save();
        if($groupToUser){
            Redis::sadd('user_group:'.$uid,$groupId);
            Redis::sadd('group:'.$groupId,$uid);
            return true;
        }
        return false;
    }


    public static function del($uid,$gid){
        Redis::srem('user_group:'.$uid,$gid);
        Redis::srem('group:'.$gid,$uid);
        $status = GroupUser::where(['uid'=>$uid,'gid'=>$gid])->delete();
        if($status){
            return true;
        }
        return false;

    }

}