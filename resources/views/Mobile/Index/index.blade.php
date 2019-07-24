<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/><!-- 删除苹果默认的工具栏和菜单栏 -->
<meta name="apple-mobile-web-app-status-bar-style" content="black"/><!-- 设置苹果工具栏颜色 -->
<meta name="format-detection" content="telephone=no, email=no"/><!--忽略页面中的数字识别为电话，忽略email识别 -->
    <!-- 启用360浏览器的极速模式(webkit) -->
<meta name="renderer" content="webkit">
    <!-- 避免IE使用兼容模式 -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- 针对手持设备优化，主要是针对一些老的不识别viewport的浏览器，比如黑莓 -->
<meta name="HandheldFriendly" content="true">
    <!-- 微软的老式浏览器 -->
<meta name="MobileOptimized" content="320">
    <!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
    <!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
    <!-- UC强制全屏 -->
<meta name="full-screen" content="yes">
    <!-- QQ强制全屏 -->
<meta name="x5-fullscreen" content="true">
    <!-- UC应用模式 -->
<meta name="browsermode" content="application">
    <!-- QQ应用模式 -->
<meta name="x5-page-mode" content="app">
    <!-- windows phone 点击无高光 -->
<meta name="msapplication-tap-highlight" content="no">
<title>轻聊 移动版</title>

<link rel="stylesheet" href="/im/css/layui.mobile.css">
<link rel="stylesheet" href="/im/css/layui.css">
<script src='/im/js/socket.io.js'></script>
<script src='/im/js/reconnecting-websocket.min.js'></script>
<script src='/im/js/jquery.js'></script>
<script src="/im/js/jquery.cookie.js"></script>
<script src="/im/js/json2.js"></script>
<script src="/im/layui.js"></script>
<style>
    *{
        -webkit-overflow-scrolling: touch;
    }

</style>
</head>
<body>




<script>



layui.config({
  version: true,debug: true
}).use('mobile', function(){
   mobile = layui.mobile
  ,layim = mobile.layim
  ,layer = mobile.layer;



  init();
  function init(){
      user = $.cookie('user');
      token = $.cookie('token');
      if(user == null || token == null ){
          window.location.href = '/login';
          return false;
      }
      user = JSON.parse(user);

      console.log(user);

      layim.config({
          //上传图片接口
          uploadImage: {
              url: '/upload/file' //（返回的数据格式见下文）
              ,type: '' //默认post
          }
          //上传文件接口
          ,uploadFile: {
              url: '/upload/file' //（返回的数据格式见下文）
              ,type: '' //默认post
          }

          //,brief: true

          ,init: {
              //我的信息
              mine: {
                  "username": user.username
                  ,"id":  user.id
                  ,"avatar": user.avatar
                  ,"sign": "懒得签名"
              }
              //我的好友列表
              ,friend: [{
                  "groupname": "我的好友"
                  ,"id": 1
                  ,"online": 2
                  ,"list":{!! $friend !!}
              }
              ]
              ,"group": {!! $group !!}
          }

          //扩展聊天面板工具栏
          ,tool: [
          //     {
          //     alias: 'code'
          //     ,title: '代码'
          //     ,iconUnicode: '&#xe64e;'
          // }
          ]

          //扩展更多列表
          ,moreList: [{
              alias: 'user'
              ,title: '我的信息'
              ,iconUnicode: '&#xe66f;' //图标字体的unicode，可不填
              ,iconClass: '' //图标字体的class类名
          },{
              alias: 'groupManage'
              ,title: '群组管理'
              ,iconUnicode: '&#xe770;' //图标字体的unicode，可不填
              ,iconClass: '' //图标字体的class类名
              }
          //     ,{
          //     alias: 'share'
          //     ,title: '分享与邀请'
          //     ,iconUnicode: '&#xe641;' //图标字体的unicode，可不填
          //     ,iconClass: '' //图标字体的class类名
          // }
          ]

          //,tabIndex: 1 //用户设定初始打开的Tab项下标
          //,isNewFriend: false //是否开启“新的朋友”
          ,isgroup: true //是否开启“群聊”
          ,chatTitleColor: '#3ec2f2' //顶部Bar颜色
          ,title: '轻聊移动版'
          ,copyright:true
      });
  }



  //创建一个会话
  /*
  layim.chat({
    id: 111111
    ,name: '许闲心'
    ,type: 'kefu' //friend、group等字符，如果是group，则创建的是群聊
    ,avatar: 'http://tp1.sinaimg.cn/1571889140/180/40030060651/1'
  });
  */

    ws =  new ReconnectingWebSocket('ws://'+location.hostname+':8082');

    ws.onopen = function (e) {
            ws.send(JSON.stringify({type:0,token:$.cookie('token'),uid:user.id}));
        }



    ws.onmessage = function (e) {

        var data = JSON.parse(e.data);
        console.log(data);

        switch (data.msgType){
            case 0: // 消息通知
                switch (data.showType) {
                    case 1: //新的好友申请提示
                        layim.showNew('List', true);
                        layim.showNew('Friend', true);
                        break;
                    case 2:  //将好友添加到列表
                        layim.addList({
                            type: 'friend'
                            ,avatar: data.avatar
                            ,username: data.username
                            ,groupid: 1
                            ,id: data.id
                            ,sign: ''
                        });
                        break;
                    case 3:  //将群组添加到列表
                        layim.addList({
                            type: 'group'
                            ,avatar: data.avatar
                            ,username: data.groupname
                            ,id: data.id
                        });
                        layim.showNew('List', true);
                        layim.showNew('Group', true);
                        break;
                    case 4:
                        parent.layui.layim.removeList({
                            type: 'group'
                            ,id: data.id
                        });
                        break;
                }

                break;
            case 1: //好友消息
                obj = {
                    username: data.username
                    ,avatar: data.avatar
                    ,id: data.id
                    ,type: 'friend'
                    ,content: data.content
                }
                console.log(obj);
                layim.getMessage(obj);
                break;
            case 2:  //群组消息
                obj = {
                    username: data.username
                    ,avatar: data.avatar
                    ,id: data.group_id
                    ,type: 'group'
                    ,content: data.content
                }
                layim.getMessage(obj);
                // layim.showNew('Group', true);
                //
                // layim.showNew('List', true);
                // layim.showNew('Friend', true);
                break;
            case 3:  //添加好友
                layim.addList({
                    type: 'friend'
                    ,avatar: data.avatar
                    ,username: data.username
                    ,groupid: 1
                    ,id: data.id
                    ,sign: data.sign
                });
                break;
            case 4:  //添加群
                if(data.online == 0){
                    layim.setFriendStatus(data.uid, 'offline');
                }else{
                    layim.setFriendStatus(data.uid, 'online');
                }
                break;
            case 5:  //群组消息

                break;
            case 6:  //添加好友到列表
                layim.addList({
                    type: 'friend'
                    ,avatar: data.avatar
                    ,username: data.username
                    ,groupid: 1
                    ,id: data.id
                    ,sign: data.sign
                });
                break;
            case 7: //添加群组到列表
                layim.addList({
                    type: 'group'
                    ,avatar: data.avatar
                    ,groupname: data.groupname
                    ,id: data.id
                    ,members: 0
                });
                //通知服务器准备推送群组消息
                ws.send(JSON.stringify({type:7,group_id:data.id}));
                break;
            case 8:  //接受群组消息
                layim.getMessage({
                    system: true
                    ,id: data.group_id
                    ,type: "group"
                    ,content: data.content
                });
                break;
            default:
                console.log(data)
        }
    }

    function getToken(){
        var token = $.cookie('token');
        if(token == null){
            window.location.href = '/login';
            return false;
        }
        return token;
    }

    $.ajaxSetup({
        async : false,
        headers: {
            "token": getToken(),
        } ,
    });

  //监听点击“新的朋友”
  layim.on('newFriend', function(){
      layim.showNew('List', false);
      layim.showNew('Friend', false);
    layim.panel({
      title: '新的朋友' //标题
      ,tpl: '<iframe height="100%" width="100%" src="/user/search"></iframe>'
    });
  });

    layim.on('group', function(){
        console.log(123);
    });





    //查看聊天信息
  layim.on('detail', function(data){
    console.log(data); //获取当前会话对象
    layim.panel({
      title: data.name //标题
      ,tpl: '<iframe height="100%" width="100%" src="/group/message/'+data.id+'"></iframe>'//模版

    });
  });



  //监听点击更多列表
  layim.on('moreList', function(obj){
    switch(obj.alias){
      case 'find':
          layim.panel({
              title: '好友申请' //标题
              ,tpl:  '<iframe height="100%" width="100%" src="/friend/request/manage"></iframe>'

          });

        //模拟标记“发现新动态”为已读
        layim.showNew('More', false);
        layim.showNew('find', false);

      break;
      case 'share':
        layim.panel({
          title: '邀请好友' //标题
          ,tpl: '<div style="padding: 10px;">自定义模版，23</div>' //模版
          ,data: { //数据
            test: '么么哒'
          }
        });
      break;
        case 'user':
            layim.panel({
                title: '我的信息' //标题
                ,tpl:'<iframe height="100%" width="100%" src="/user/my"></iframe>'

            });
            break;
        case 'groupManage':
            layim.panel({
                title: '群组管理' //标题
                ,tpl:'<iframe height="100%" width="100%" src="/group/manage"></iframe>'

            });
            break;
    }





  });






  //监听返回
  layim.on('back', function(){
      //history.back();
      layer.close('iframe');
      console.log('iframe')
      return false;
      //如果你只是弹出一个会话界面（不显示主面板），那么可通过监听返回，跳转到上一页面，如：history.back();
  });

  //监听自定义工具栏点击，以添加代码为例
  layim.on('tool(code)', function(insert, send){
    insert('[pre class=layui-code]123[/pre]'); //将内容插入到编辑器
    send();
  });

  //监听发送消息
  layim.on('sendMessage', function(data) {
      if(data.to.type == 'group'){
          data.type = 2;
          console.log(data);
          ws.send(JSON.stringify(data));
      }else{
          data.type = 1;
          console.log(data);
          ws.send(JSON.stringify(data));
      }

  });



  //监听查看更多记录
  layim.on('chatlog', function(data, ul){
    console.log(data.id);
    layim.panel({
      title: '与 '+ data.name +' 的聊天记录' //标题
      ,tpl: '<iframe height="100%" width="100%" src="/chat/log/friend/'+data.id+'"></iframe>'

    });
  });




});

var sh;

sh = setInterval("sendHeartbeat()", 10000);

function sendHeartbeat() {
    console.log(ws.readyState);
    if (ws.readyState == 1) {
        ws.send(JSON.stringify({type: '4'}));
    }
    if (ws.readyState == 3) {
        layer.msg('与服务器链接中断,请检查网络');
        //clearInterval(sh);

    }
}
</script>


</body>
</html>
