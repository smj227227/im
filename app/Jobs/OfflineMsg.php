<?php
/*
    *author:123
    *time 2019/5/3 7:03 PM
    *All rights reserved
*/


namespace App\Jobs;


use App\Http\Controllers\Chat\ChatController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class OfflineMsg extends Job
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(){
        $data = json_decode($this->data,true);
        $leng = Redis::llen('offline_msg:'.$data['uid']);
        if($leng != 0){
            $msg = Redis::lrange('offline_msg:'.$data['uid'],0,$leng-1);
            Redis::del('offline_msg:'.$data['uid']);
            foreach ($msg as $k=>$v){
                Log::info($v);
                $v = json_decode($v,true);
                switch ($v['msgType']){
                    case 0:
                        ChatController::userAddFriend($v);
                        break;
                    case 1:
                        ChatController::peerToPeer($v);
                        break;
                }


            }
        }

    }
}