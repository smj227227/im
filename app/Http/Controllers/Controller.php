<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public static  $SystemError = 1; //系统繁忙,服务暂时不可用

    public static  $RequestParameterError = 2; //请求参数不合法

    public static  $RequestTooFast = 3; //请求频率过快

    public static  $ApiSuccess = 200; //接口调用成功

    public static  $UserPhoneError = 30001; //没有这个手机号

    public static  $PasswordError  = 30002; //密码错误

    public static  $UserMsgError   = 30003; //用户信息错误

    public static  $UserIsNull     = 30004; //用户不存在

    public static  $UserExist      = 30005; //用户已经存在

    public static  $SmsCodeError   = 31001; //验证码错误

    public static  $TokenErr       = 32001; //token错误

    public static  $GroupIsNull    = 33001; //群组不存在

    public static  $AddFriendErr   = 40001; //添加用户失败








}
