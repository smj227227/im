<?php
/*
    *author:123
    *time 2019/5/2 9:37 PM
    *All rights reserved
*/


namespace App\Http\Controllers\Help;


use Illuminate\Support\Facades\Log;

class HelpController
{
    //507616 验证码

    public static function sendSms($phone,$code_temp,$content){
        $url = "http://api.sms.cn/sms/?ac=send&uid=".env('SMS_NAME')."&pwd=".env('SMS_PASS');
        $mobile = "&mobile=".$phone;
        $temp = '&template='.$code_temp;
        $smsContent = "&content=".urlencode(json_encode($content));
        $apiUrl = $url.$mobile.$temp.$smsContent;
        $status = self::Get($apiUrl);
        Log::info($status);
        if($status['stat'] == 100){
            return true;
        }else{
            return false;
        }
    }

    public static function curl($url,$data,$method='POST'){

        $url = $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $haeder??[]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $document = curl_exec($ch);
        if(curl_errno($ch)){
            Log::info('Curl error: ' . curl_error($ch).'url:'.$url);
        }
        curl_close($ch);
        $document = json_decode($document,true);
        dd($document);

    }



    public static function Get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return self::json_to_array($output);;

    }

    public static function json_to_array($json)
    {
        if (mb_detect_encoding($json, array('ASCII', 'UTF-8', 'GB2312', 'GBK')) != 'UTF-8') {
            $json = iconv('GBK', 'UTF-8', $json);
        }
        return json_decode($json, true);
    }
}