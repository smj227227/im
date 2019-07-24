<?php
/*
    *author:123
    *time 2019/4/25 11:29 AM
    *All rights reserved
*/


namespace App\Http\Controllers\Help\QiNiu;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiNiuController extends Controller
{
    static $key = '5v8fsv4p3onecZBNC5jxvO65-UWFrBL3n97U8wb2';
    static $secret = '15ceLam5CCz1nEEmEttj6oU9I4QiR9thIiaOCJCB';
    static $url = 'https://im.cdn.caomei520.com/';
    static $bucket = 'im-oss';
    public $auth;
    public $token;

    public function __construct()
    {
        $this->auth = new Auth(self::$key,self::$secret);
        $this->token = $this->auth->uploadToken(self::$bucket);
    }

    public function upload(Request $request){
        $file = $request->file('file');
        $name = date('Ymd').'/'.uniqid().time().'.'.$this->get_extension($file->getClientOriginalName());
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile(
            $this->token
            ,$name
            ,$file->path());
        if ($err !== null) {
            return ['code'=>1,'msg'=>'error'];
        } else {
            return ['code'=>0,'data'=>['src'=>self::$url.$ret['key'],'name'=>$file->getClientOriginalName()]];
        }
    }

    public function get_extension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    public  function QiNiuToken(){
        return ['code'=>200,'data'=>['token'=>$this->token]];
    }
}