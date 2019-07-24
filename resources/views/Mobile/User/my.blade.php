@include('Mobile.Com.head')
<script src="/im/layui.js"></script>
<script src="/im/layer_mobile/layer.js"></script>
<script src="/im/js/jquery.cookie.js"></script>
<script src="/im/js/json2.js"></script>
<link rel="stylesheet" href="/im/css/layui.css">
<link rel="stylesheet" href="/im/css/mobile/user.css ">
<body>


<section class="ui-container">
    <div class="mod-banner " style="">

        <!-- 用户信息 -->

        <section class="banner-info" >

            <a href="">

                <div class="ui-avatar-one " style="margin: auto">
                    <span class="an" >
                        <img src="{{$data['avatar']}}">
                    </span>

                </div>
            </a>

            <div class="info-role ">

                <h4 >{{$data['username']}}</h4>
                <h4 >{{$data['phone']}}</h4>
            </div>
        </section>

        <!-- 用户列表 -->
        {{--<ul class="banner-list">--}}
            {{--<li class="ui-border-r">--}}
                {{--<p>1</p>--}}
                {{--<p>考试次数</p>--}}
            {{--</li>--}}
            {{--<li class="ui-border-r">--}}
                {{--<p>--}}
                    {{--2--}}
                {{--</p>--}}
                {{--<p>平均分</p>--}}
            {{--</li>--}}
            {{--<li>--}}
                {{--<p>3</p>--}}
                {{--<p>余额</p>--}}
            {{--</li>--}}
        {{--</ul>--}}
        <!-- 头像层 高斯模糊-->
    </div>

                <div class="ui-form ui-border-t">

                        <ul class="ui-list ui-list-function ui-border-tb">
                            <li>
                                <input type="hidden" name="avatar" id="avatar" value="{{$data['avatar']}}">
                                <div class="ui-list-info ui-border-t">
                                    <h4 class="ui-nowrap">修改头像</h4>
                                </div>
                                <div class="ui-btn-s ui-btn-primary avatar_upload" >点击上传</div>
                            </li>
                        </ul>
                        <div class="ui-form-item ui-border-b">
                            <label>
                                修改呢称
                            </label>
                            <input type="text" placeholder="{{$data['username']}}" id="username" value="{{$data['username']}}"/>
                            </a>
                        </div>
                        <div class="ui-form-item ui-border-b">
                            <label>
                                修改密码
                            </label>
                            <input type="text" placeholder="输入新密码" id="password" />
                        </div>

                        <div class="ui-btn-wrap">
                            <button class="ui-btn-lg ui-btn-primary" id="submit">
                                确定修改
                            </button>
                        </div>

                </div>






</section>




</body>
<script>



    layui.use('upload', function(){
        var upload = layui.upload;

        //执行实例
        var uploadInst = upload.render({
            elem: '.avatar_upload' //绑定元素
            ,url: '/upload/file' //上传接口
            ,done: function(res){
                if(res.code == 0){
                    var imgSrc = res.data.src+'!100x100png';
                    $("#avatar").val(imgSrc);
                    $(".ui-avatar-one").empty();
                    var html = ' <span class="an " style="background-image:url('+imgSrc+');"></span>'
                    $(".ui-avatar-one").append(html);
                    layer.msg('头像上传成功,请点击确定按钮');
                }

                console.log(res);
                //上传完毕回调
            }
            ,error: function(){
                layer.msg('上传失败,请重试');
            }
        });
    });

    $('#submit').click(function () {
        var username = $("#username").val();
        var avatar = $("#avatar").val();
        var password = $("#password").val();
        $.post('/user/update',{username:username,password:password,avatar:avatar},function (data) {
            if(data.code == 200){
                user = JSON.parse($.cookie('user'));
                user.username = username;
                user.avatar = avatar;
                $.cookie('user',JSON.stringify(user),{path:'/'});
                console.log(JSON.parse($.cookie('user')));
                layer.msg('成功');
            }else{
                layer.msg('修改失败,请重试');
            }
        })
    });

    function sales(){
        $(".ui-dialog").addClass("show");
    }

    $('[id=no]').click(function(){
        $('.ui-dialog').removeClass("show");
    });

    $('.ui-list li,.url li').click(function(){
        if($(this).data('href')){
            location.href= $(this).data('href');
        }
    });
</script>
</html>