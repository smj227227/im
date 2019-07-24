@include('Mobile.Com.head')
<ul class="ui-list  ui-border-tb">
    @foreach($data as $K=>$v)
    <li>
        <div class="ui-avatar">
            <span style="background-image:url({{$v['avatar']}})"></span>
        </div>
        <div class="ui-list-info ui-border-t">
            <h4 class="ui-nowrap">{{$v['username']}}</h4>
            <p>{{$v['content']}}</p>
        </div>
    </li>
    @endforeach
</ul>