<?php
/*
    *author:123
    *time 2019/5/1 1:55 PM
    *All rights reserved
*/


namespace App\Jobs;


use App\Http\Model\ChatFriendLog;
use App\Http\Model\ChatGroupLog;
use App\Http\Model\LogContent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ChatLogJob extends Job
{
    public $data;

    public function __construct($data){
        $this->data = $data;
    }

    public function handle(){
        $data = json_decode($this->data,true);
        $key = self::ifKey($data['content']);
        $data['time'] = time();
        $data['type'] = $key['type'];
        $chatContent = new LogContent();
        $chatContent->type = $key['type'];
        $chatContent->content = $key['content'];
        $chatContent->save();

        if($chatContent){
            switch ($data['msgType']){
                case 1:
                    $uuidArr = [$data['id'],$data['to_id']];
                    arsort($uuidArr);
                    $uuid = implode('',$uuidArr);
                    $chatFriendLog = new ChatFriendLog();
                    $chatFriendLog->uid = $data['id'];
                    $chatFriendLog->username = $data['username'];
                    $chatFriendLog->avatar = $data['avatar'];
                    $chatFriendLog->fid = $data['to_id'];
                    $chatFriendLog->cid = $chatContent->id;
                    $chatFriendLog->uuid = $uuid;
                    $chatFriendLog->save();
                    $num = Redis::rpush('friend_log:'.$uuid,json_encode($data,JSON_UNESCAPED_UNICODE));
                    if($num > 100){
                        Redis::lpop('friend_log:'.$uuid);
                    }
                    break;
                case 2:
                    $chatGroupLog = new ChatGroupLog();
                    $chatGroupLog->gid = $data['group_id'];
                    $chatGroupLog->uid = $data['mine_id'];
                    $chatGroupLog->username = $data['username'];
                    $chatGroupLog->avatar = $data['avatar'];
                    $chatGroupLog->cid = $chatContent->id;
                    $chatGroupLog->save();
                    $num = Redis::rpush('group_log:'.$data['group_id'],json_encode($data,JSON_UNESCAPED_UNICODE));
                    if($num >2000){
                        Redis::lpop('group_log:'.$data['group_id']);
                    }
                    break;
            }
        }
    }


    public static function ifKey($content){
        $key = ['1'=>'img[','2'=>'audio[','3'=>'video['];
        $type = 0;
        foreach ($key as $k=>$v){
            if(strstr($content,$v) != false){
                $content = ltrim($content,$v);
                $content = rtrim($content,']');
                $type = $k;
                break;
            }
        }

        return ['type'=>$type,'content'=>$content];

    }
}