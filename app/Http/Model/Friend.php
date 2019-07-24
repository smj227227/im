<?php
/*
    *author:123
    *time 2019/4/23 4:36 PM
    *All rights reserved
*/


namespace App\Http\Model;


use App\Http\Controllers\User\UserController;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Friend extends Model
{
    public $table = 'friend';

    public $dateFormat = 'U';

    public static $arr;




    public static function addFriend($uid,$friend_id){
        $addFriend = new AddFriend();
        $addFriend->uid = $friend_id;
        $addFriend->friend_id = $uid;
        $addFriend->save();
        if($addFriend){
            return $addFriend->id;
        }else{
            return false;
        }
    }



    /**
     * @return mixed
     * @author mjShu
     */
    public static function getUserFriendId($uid){
         $friend = Redis::SMEMBERS('friend:'.$uid);
         if($friend){
             return $friend;
         }else{
             $friend = Friend::select('fid')->where('uid',$uid)->get();
             if($friend){
                 $friend = $friend->toArray();
                 self::$arr['uid'] = $uid;
                 self::$arr['arr'] = array_column($friend,'fid');
                 Redis::pipeline(function ($pipe){
                     foreach (self::$arr['arr'] as $k=>$v){
                         $pipe->sadd('friend:'.self::$arr['uid'],$v);
                     }
                 });
                 return self::$arr['arr'];
             }

             return false;
         }
    }


    public static function getUserFriendMsg(array $data){
        if($data){
            $data = Redis::hmget('user',$data);
            if($data){
                $newArr = [];
                foreach ($data as $k=>$v){
                    $newArr[$k] = json_decode($v,true);
                }
                return $newArr;
            }
        }


        return [];
    }


    public static function getRequestManage($uid){
        $friend_res = Redis::smembers('add_friend:'.$uid);
        if($friend_res){
            $data = Redis::hmget('user',$friend_res);
            $newArr = [];
            foreach ($data as $k=>$v){
                if(empty($v)){
                    $newArr[$k] = UserController::getUser($friend_res[$k]);
                }else{
                    $newArr[$k] = json_decode($v,true);
                }

            }
            return $newArr;
        }
        return [];
    }
}
