@include('Mobile.Com.head')
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

<div class="ui-loading-block ">
    <div class="ui-loading-cnt">
        <i class="ui-loading-bright"></i>
        <p>正在拉取数据...</p>
    </div>
</div>

<script>
    function loadStart(){
        console.log(234)

        $(".ui-loading-block").addClass("show");
    };

    function loadEnd(){
        console.log(45)
        $(".ui-loading-block").removeClass("show");
    };

    uid = 0;

    $("body").on('click','#show',function () {
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
</script>