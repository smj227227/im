@include('Mobile.Com.head')

<section class="ui-container">

    <section id="searchbar">

        <div  class="ui-searchbar-wrap ui-border-b">
            <div class="ui-searchbar ui-border-radius">
                <i class="ui-icon-search"></i>

                <input value="" type="number" id="tel" placeholder="输入手机号或者群号">

                <i class="ui-icon-close"></i>
            </div>
            <button class="ui-searchbar-cancel">取消</button>
        </div>



    </section>


    <ul class="ui-list ui-list-link ui-border-tb">
        @foreach($data as $k=>$v)
            <li id="show" data-avatar="{{$v['avatar']}}" data-uid="{{$v['id']}}" data-username="{{$v['username']}}">
                <div class="ui-list-thumb">
                    <span style="background-image:url({{$v['avatar']}})"></span>
                </div>
                <div class="ui-list-info ui-border-t">
                    <h4 class="ui-nowrap">{{$v['username']}}</h4>
                </div>
            </li>
        @endforeach

    </ul>


    <div class="ui-dialog ui-dialog-operate ui-dialog-operate-icon" id="dialog">
        <div class="ui-dialog-cnt">
            <div class="ui-dialog-hd">
                <div class="ui-avatar-lg">

                </div>
            </div>
            <div class="ui-dialog-bd">

            </div>
            <div class="ui-dialog-ft">
                <button class="ui-btn" id="Agree">同意</button>
                <button class="ui-btn" id="Refuse">拒绝</button>
            </div>
            <i class="ui-dialog-close" data-role="button"></i>
        </div>
    </div>


</section>
<div class="ui-loading-block ">
    <div class="ui-loading-cnt">
        <i class="ui-loading-bright"></i>
        <p>正在处理中...</p>
    </div>
</div>

<section id="dialog">
    <div class="ui-dialog">
        <div class="ui-dialog-cnt">
            <header class="ui-dialog-hd ui-border-b">
                <h3 >操作</h3>
                <i class="ui-dialog-close" data-role="button" id="no"></i>
            </header>

            <div class="ui-dialog-ft" id="explain">
                <button type="button"  data-role="button" id="yes" >添加</button>
                <button type="button"  data-role="button" id="no" >重新搜索</button>
            </div>
        </div>
    </div>
</section>

<script>

    uid = 0;

    type = 0;

    function loadStart(){
        $(".ui-loading-block").addClass("show");
    };

    function loadEnd(){
        $(".ui-loading-block").removeClass("show");
    };

    $(".ui-list").on('click','li',function () {
        var avatar = $(this).attr('data-avatar');
        var username = $(this).attr('data-username');
        uid = $(this).attr('data-uid');
        var ui_img = '<span style="background-image:url('+avatar+')"></span>';
        var text = '<h3>'+username+'</h3>'
        $(".ui-avatar-lg").empty();
        $(".ui-avatar-lg").append(ui_img);
        $(".ui-dialog-bd").empty();
        $(".ui-dialog-bd").append(text);
        $("#dialog").addClass("show");
    });

    $("#Agree").click(function () {
        loadStart();
        $.get('/friend/request/agree/'+uid,function (data) {
            if(data.code == 200){
                layer.open({
                    content: '添加完成'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });
            }
        });
        loadEnd();
    });


    $(".ui-dialog button").click(function(){
        $('.ui-dialog').removeClass("show");
    });
    $('.ui-dialog-close').click(function(){
        $('.ui-dialog').removeClass("show");
    })




    $("#tel").bind("input propertychange",function(event){

        var number = $("#tel").val();
        var first = number.substring(0,1);

        if(number.length == 11 && !isNaN(number) && first == 1){
            $(".ui-dialog-bd").remove();
            $(".ui-loading-block").addClass("show");
            $.get('/user/search/friend/'+number,function (data) {
                if(data.code == 200){
                    var data = data.data;
                    type = 1;
                    uid = data.id;
                    var explainHtml = '<div class="ui-dialog-bd" id="explainHtml">'
                    explainHtml +=  '<img src="'+data.avatar+'" width="100" height="80">';
                    explainHtml += '<p class="ui-txt-warning">'+data.username+'</p></div>';
                    $("#explain").before(explainHtml);
                    loadEnd();
                    $(".ui-dialog").addClass("show");
                }else{
                    $(".ui-loading-block").removeClass("show");
                    layer.open({
                        content: '没有找到该用户'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }

            })

        }

        if(number.length == 5 && !isNaN(number) && first != 1){
            $(".ui-dialog-bd").remove();
            $(".ui-loading-block").addClass("show");
            $.get('/user/search/group/'+number,function (data) {
                if(data.code == 200){
                    var data = data.data;
                    type = 2;
                    uid = data.id;
                    var explainHtml = '<div class="ui-dialog-bd" id="explainHtml">'
                    explainHtml +=  '<img src="'+data.avatar+'" width="100" height="80">';
                    explainHtml += '<p class="ui-txt-warning">'+data.groupname+'</p></div>';
                    $("#explain").before(explainHtml);
                    loadEnd();
                    $(".ui-dialog").addClass("show");
                }else{
                    $(".ui-loading-block").removeClass("show");
                    layer.open({
                        content: '没有找到该群组'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }

            })
        }

    })

    $('[id=no]').click(function(){
        $('.ui-dialog').removeClass("show");
        $(".ui-loading-block").removeClass("show");
    });

    $("#yes").click(function () {
        if(type == 1){
            $.get('/user/add/friend/'+uid,function (data) {
                console.log(data);
                if(data.code == 200){
                    layer.open({
                        content: '请求已发出'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }else {
                    layer.open({
                        content: '不能添加该用户'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            })
        }
        if(type == 2){
            $.get('/user/add/group/'+uid,function (data) {
                console.log(data);
                if(data.code == 200){
                    layer.open({
                        content: '服务器已经接受你成为群成员,即将下发群消息'
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                }
            })
        }

    });



</script>