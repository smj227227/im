<?php
/*
    *author:123
    *time 2019/4/22 4:51 PM
    *All rights reserved
*/


namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Friend\FriendController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Help\HelpController;
use App\Http\Controllers\Token\TokenController;
use App\Http\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function login(Request $request){

        $data = $request->all();
        if(strlen($data['phone']) == 11 && strlen($data['password']) >=6){
            $data['password'] = md5($data['password']);
            $user = User::select('id','phone','avatar','username')->where($data)->first();
            if($user){
                $token = TokenController::Token($user->id,$user->phone);
                return ['code'=>200,'data'=>$token];
            }
        }
        return ['code'=>parent::$RequestParameterError];
    }


    public function reg(Request $request){
        $data = $request->all();
        if(strlen($data['phone']) == 11 &&  strlen($data['password']) >=6 && strlen($data['code']) == 4){
            $code = Redis::get('code:'.$data['phone']);
            if($data['code'] == $code){
                $user = new User();
                $user->username = $data['phone'];
                $user->phone = $data['phone'];
                $user->password = md5($data['password']);
                $user->avatar = 'https://im.cdn.caomei520.com/null.jpg!100x100png';
                $user->save();
                if($user){
                    return ['code'=>200];
                }
            }else{
                return ['code'=>parent::$SmsCodeError];
            }
        }else{
            return ['code'=>parent::$RequestParameterError];
        }

    }

    public function sendRegSms($phone){
       $user = self::phoneToUser($phone);
       if(!$user){
           $status =  self::checkPhoneSms($phone);
           if($status){
               $smsStatus = self::code($phone);
               if($smsStatus){
                   return ['code'=>200];
               }
           }else{
               ['code'=>parent::$RequestTooFast];
           }
       }
        return ['code'=>parent::$UserExist];
    }


    public static function checkPhoneSms($phone){
        $time = date('Ymd');
        $status = Redis::hget("checkPhoneSms:".$time,$phone);
        if(empty($status) || $status < 4){
            Redis::hincrby("checkPhoneSms:".$time,$phone,1);
            return true;
        }else{
            return false;
        }
    }


    public function my(){
        $data = self::getUser();
        return view('Mobile.User.my',['data'=>$data]);
    }

    public function update(Request $request){
        $uid = self::getUserId();
        $data = $request->all();
        foreach ($data as $k=>$v){
            if(empty($v)){
                unset($data[$k]);
            }
        }
        $status = User::where('id',$uid)->update($data);
        if($status>0){
            $user = self::reloadUser($uid);
            return ['code'=>200,'data'=>$user];
        }
        return ['code'=>400];

    }



    public static function reloadUser($id){
        Redis::hdel('user',$id);
        $user = User::getUser($id);
        return $user;
    }


    public function searchFriend($phone){
        $data = self::phoneToUser($phone);
        if($data){
            return ['code'=>200,'data'=>$data];
        }
        return ['code'=>400];;
    }

    public function searchGroup($id){
        $data = GroupController::searchGroup($id);
        if($data){
            return ['code'=>200,'data'=>$data];
        }else{
            return ['code'=>400];
        }
    }


    public static function code($phone){
        $code = rand(1000,9999);
        Redis::setex('code:'.$phone,600,$code);
        $status = HelpController::sendSms($phone,507616,['code'=>$code]);
        if($status){
            return true;
        }
        return false;


    }



    public function addFriend($friend_id){
        $uid = self::getUserId();
        $status = Redis::sismember('friend:'.$uid,$friend_id);
        if($status == 0 && $uid != $friend_id){
            Redis::sadd('add_friend:'.$friend_id,$uid);
            FriendController::addFriend($uid,$friend_id);
            return ['code'=>200];
        }else{
            return ['code'=>400];
        }
    }

    public function addGroup($gid){
        $status = GroupController::addUserGroup($gid);
        if($status){
            return ['code'=>200];
        }
        return ['code'=>400];

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


    public static function getUserId(){
        $uid = Redis::get('token:'.$_COOKIE['token']);
        return $uid;
    }


    public static function getUser($uid = ''){
        if ($uid == ''){
            $uid = self::getUserId();
        }
        $data = User::getUser($uid);
        return $data;
    }




    public static function checkToken($data){
        if(!empty($data['token'])){
            $uid = Redis::get('token:'.$data['token']);
            if($uid){
                if($data['uid'] == $uid){
                    return true;
                }
            }
        }
        return false;
    }
}