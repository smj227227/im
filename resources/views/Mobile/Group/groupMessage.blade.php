@include('Mobile.Com.head')
<ul class="ui-list ui-list-single  ui-border-tb">
    {{--@if($admin_id == $group['uid'])--}}
        {{--<li>--}}

            {{--<div class="ui-list-info ui-border-t">--}}
                {{--<h4 class="ui-nowrap">修改群头像</h4>--}}
            {{--</div>--}}
            {{--<div class="ui-btn-s ui-btn-primary avatar_upload" >点击上传</div>--}}
        {{--</li>--}}
    {{--@endif--}}
    <li class="ui-border-t">
        <div class="ui-list-info">
            <h4 class="ui-nowrap">群名</h4>
            <div class="ui-txt-info">{{$group['groupname']}}</div>
        </div>
    </li>
    <li class="ui-border-t">
        <div class="ui-list-info">
            <h4 class="ui-nowrap">群号</h4>
            <div class="ui-reddot ui-reddot-static"></div>
            <div class="ui-txt-info">{{$group['id']}}</div>
        </div>
    </li>
</ul>
<input type="hidden" id="group_id" value="{{$group['id']}}">
<section class="ui-panel ui-border-t">
<div class="ui-btn-wrap">
    <button class="ui-btn-lg ui-btn-primary" id="history">
        查看群组历史消息
    </button>
</div>

<h4>群组成员：</h4>
<ul class="ui-row ui-whitespace">
    @foreach($user as $k=>$v)
    <li class="ui-col ui-col-20">
        <div class="ui-avatar">
            <span style="background-image:url({{$v['avatar']}})"></span>
        </div>
        <div class="ui-nowrap-flex ui-whitespace">
           {{$v['username']}}
        </div>
    </li>
    @endforeach

    @if(count($user) > 1)

    <li class="ui-col ui-col-20">
            <div class="ui-avatar">
                <span style="background-image:url(/im/images/more.jpeg)"></span>
            </div>
            <div class="ui-nowrap-flex ui-whitespace">
                更多...
            </div>
        </li>
     @endif
</ul>
</section>

<script>
    $("#history").click(function () {
      var group_id =  $("#group_id").val();
      window.location.href = '/chat/log/group/'+group_id;
    })
</script>
