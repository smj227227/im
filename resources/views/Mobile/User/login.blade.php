<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登录</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" name="viewport"/>
<meta content="yes" name="apple-mobile-web-app-capable"/>
<meta content="black" name="apple-mobile-web-app-status-bar-style"/>
<meta content="telephone=no" name="format-detection"/>
<script src='/im/js/jquery.js'></script>
<script src="/im/js/jquery.cookie.js"></script>
<script src="/im/js/json2.js"></script>
<style>
body, h1, h2, h3, h4, h5, h6, p, dl, dt, dd, ul, ol, li, form, legend, button, input, textarea, th, td, input, aside, article, section, nav, pre, figure {
    margin: 0;
    padding: 0;
}
body{ color: #333;
   font: 0.6rem/1 tahoma, arial, 'Microsoft YaHei', simsun;}
input, select, option, textarea, button {
    font: 0.6rem/1 tahoma, arial, 'Microsoft YaHei', simsun;
}
	* {
    -webkit-tap-highlight-color: rgba(180,180,180,0.2);
}
a {
    color: #333;
    text-decoration: none;
}
a:hover {
    text-decoration: none;
}
table {
    border-collapse: collapse;
}
li {
    list-style: none;
}
img, iframe {
    border: none;
}
dt {
    font-weight: bold;
}
em, i {
    font-style: normal;
}
input[type="submit"], input[type="reset"], input[type="button"], button {
    -webkit-appearance: none;
    line-height: normal !important;
}
::-webkit-input-placeholder {
color:#999;
}
.placeholder-text {
    color: #999;
}
.hidden {
    display: none !important;
}
.clearfix:after {
    content: "";
    display: block;
    height: 0;
    clear: both;
    overflow: hidden;
}
.float-left {
    float: left !important;
}
.float-right {
    float: right !important;
}
.absolute-left {
    position: absolute !important;
    left: 0 !important;
}
.absolute-right {
    position: absolute !important;
    right: 0 !important;
}
.over-hidden {
    overflow: hidden !important;
}
.inline-block {
    display: inline-block !important;
}
.text-left {
    text-align: left !important;
}
.text-center {
    text-align: center !important;
}
.text-right {
    text-align: right !important;
}
.border-box {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
}
.middle {
    vertical-align: middle;
}
.block {
    display: block !important;
}
.weight {
    font-weight: bold !important;
}
.both {
    clear: both !important;
}
.ellipsis {
    display: block;
    overflow: hidden !important;
    white-space: nowrap;
    text-overflow: ellipsis;
}
.circle {
    border-radius: 50%;
    overflow: hidden;
}
/*字体图标*/
@font-face {
    font-family: "iconfont";
    src: url(../fonts/iconfont.eot?t=1547626840735);
    src: url(../fonts/iconfont.eot?t=1547626840735#iefix) format('embedded-opentype'), url('data:application/x-font-woff2;charset=utf-8;base64,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') format('woff2'), url(../fonts/iconfont.woff?t=1547626840735) format('woff'), url(../fonts/iconfont.ttf?t=1547626840735) format('truetype'), url(../fonts/iconfont.svg?t=1547626840735#iconfont) format('svg');
}
.iconfont {
    font-family: "iconfont" !important;
    font-size: 16px;
    font-style: normal;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.icon-guanbi:before {
    content: "\e6b7";
}
.icon-xiangyou:before {
    content: "\e6af";
}
.icon-xiangxia-copy:before {
    content: "\e63a";
}
.icon-icon:before {
    content: "\e66e";
}
.icon-xiangzuo:before {
    content: "\e682";
}
.icon-open-eye:before {
    content: "\e79c";
}
.icon-biyan:before {
    content: "\e610";
}/** * Swiper 4.3.3 * Most modern mobile touch slider and framework with hardware accelerated transitions * http://www.idangero.us/swiper/ * * Copyright 2014-2018 Vladimir Kharlampidi * * Released under the MIT License * * Released on:June 5,2018 */	
.font-color{ color:#10a78e;}
/*登录注册*/

.login-logo{ width: 9rem; height: 9rem; background: #10a78e; margin: 6rem auto 4rem; color: #fff; line-height: 9rem; font-size: 3rem;}

.login-cont{ margin: 0 2rem; line-height: 4rem;}
.login-cont li{ border-bottom: 1px solid #ebebeb; height: 3rem; padding: 1rem; position: relative; overflow: hidden;}
.login-cont li input{ border: none; line-height: 4rem; width: 100%; outline: 0;}
.login-cont{ margin: 0 2rem; line-height: 4rem;}
#click{ position: absolute; right: 0; top: 50%; margin-top: 0.5rem; transform: translate(0,-50%)}
.button{background: #10a78e; color: #fff; border-radius:2rem; height: 4rem; line-height: 4rem; font-size: 1.6rem; margin-top: 4rem;}
	
	</style>
</head>

<body>


<div class="login-logo circle text-center">轻聊</div>

<form name="forms"  action="/login" method="post" class="login-cont">
  <ul>
  <li><input type="tel" name="phone" placeholder="请输入您的手机号" ></li>
  <li>
	<span id="box">
      <input type="password"  name="password">
    </span>

    <span id="click"><a href="javascript:ps()" class="iconfont icon-biyan"></a></span>
  </li>
  </ul>
  <a href="javascript:;" onclick="login()" class="button text-center block">登 录</a>
  <a href="/forget" class="text-center block font-color">忘记密码？</a>
  <p class="text-center">没有账号？ <a href="/reg" class="font-color" >立即注册</a></p>
  </form>

<script>
function login() {
    $("form[name='forms']").submit();
}

</script>

  
</body>
</html>
