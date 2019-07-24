@include('Mobile.Com.head')
<div class="ui-btn-wrap">
    <button class="ui-btn-lg ui-btn-primary" id="create_group">
        我要创建群组
    </button>
</div>

@if(!empty($data))
<ul class="ui-list ui-list-function ui-border-tb" id="data_list">
    @foreach($data as $k=>$v)
        <li  data-avatar=""  data-username="{{$v['groupname']}}">
            <div class="ui-list-thumb">
                <span style="background-image:url()"></span>
            </div>
            <div class="ui-list-info ui-border-t">
                <h4 class="ui-nowrap">{{$v['groupname']}}</h4>
            </div>
            <div class="ui-btn-s" id="delGroup" data-gid="{{$v['id']}}">退出</div>
        </li>
    @endforeach

</ul>
@else
    <section class="ui-notice">
        <i></i>
        <p>您还没有群组</p>

    </section>
@endif


<div class="ui-dialog ui-dialog-operate ui-dialog-operate-icon" id="dialog">
    <div class="ui-dialog-cnt">
        <div class="ui-dialog-hd">
            <div class="ui-form-item ui-border-b">
                <label>
                    创建群组
                </label>
                <input type="text" placeholder="输入群名称"  id="group_name"/>
                <a href="#" class="ui-icon-close">
                </a>
            </div>

        </div>

        <div class="ui-dialog-ft">
            <button class="ui-btn-lg" id="group_create">确认创建</button>
        </div>
        <i class="ui-dialog-close" data-role="button"></i>
    </div>
</div>

<script>
    $("#create_group").click(function(){
        $("#dialog").addClass("show");
    });

    $('body').on('click','#delGroup',function () {
        var obj = $(this);
        var gid = obj.attr('data-gid');
        $.get('/group/del/'+gid,function (data) {
            obj.parent().remove();
            if(data.code == 200){
                layer.open({
                    content: '退出成功'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                });

            }
        })
    });


    $("#group_create").click(function () {
        var group_name = $("#group_name").val();
        $.post('/group/create',{group_name:group_name},function (data) {
            if(data.code == 200){
                 html = ' <li id="show" data-avatar=""  data-username="">' +
                    '            <div class="ui-list-thumb">' +
                    '                <span style="background-image:url()"></span>' +
                    '            </div>' +
                    '            <div class="ui-list-info ui-border-t">' +
                    '                <h4 class="ui-nowrap">'+group_name+'</h4>' +
                    '            </div>' +
                    '            <div class="ui-btn-s" id="delGroup" data-gid="'+data.data.gid+'">退出</div>' +
                    '        </li>';

                if(!$("#data_list").html()){
                     html = '<ul class="ui-list ui-list-function ui-border-tb" id="data_list">'+html+'</ul>'
                    $('.ui-notice').remove();
                    $(".ui-btn-wrap").after(html);
                }else{
                    $("#data_list").append(html);
                }
                layer.open({
                    content: '创建成功'
                    ,skin: 'msg'
                    ,time: 2 //2秒后自动关闭
                    });
            }
        })
    });


    $(".ui-dialog button").click(function(){
        $('.ui-dialog').removeClass("show");
    });

    $('.ui-dialog-close').click(function(){
        $('.ui-dialog').removeClass("show");
    })

</script>