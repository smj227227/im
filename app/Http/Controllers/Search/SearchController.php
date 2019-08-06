<?php


namespace App\Http\Controllers\Search;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\User\UserController;
use App\Http\Model\User;
use Illuminate\Support\Facades\Redis;

class SearchController extends Controller
{
    public function checkUser($phone){
        $data = self::phoneToUser($phone);
        if($data){
            return ['code'=>parent::$UserExist];
        }

        return ['code'=>parent::$UserIsNull];
    }

    public function searchFriend($phone){
        $data = self::phoneToUser($phone);
        if($data){
            return ['code'=>200,'data'=>$data];
        }
        return ['code'=>parent::$UserIsNull];
    }

    public function searchGroup($id){
        $data = GroupController::searchGroup($id);
        if($data){
            return ['code'=>200,'data'=>$data];
        }else{
            return ['code'=>parent::$GroupIsNull];
        }
    }

    public static function phoneToUser($phone){
        $uid = Redis::hget('phone_to_user',$phone);
        if($uid){
            return self::getUser($uid);
        }else{
            $user = User::getPhone($phone);
            if($user){
                Redis::hset('phone_to_user',$phone,$user['id']);
                return $user;
            }
        }
        return false;
    }


    public static function getUser($uid){
        return UserController::getUser($uid);
    }
}