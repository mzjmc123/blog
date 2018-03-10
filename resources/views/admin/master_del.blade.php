@extends('layouts.admin.heade')
@section('content')
<div class="wrapper">
    @include('layouts.admin.left')
<div class="page-content">
    <div class="content">
        <!-- 右侧内容框架，更改从这里开始 -->
        <form class="layui-form xbs" action="" >
            <div class="layui-form-pane" style="text-align: center;">
                <div class="layui-form-item" style="display: inline-block;">
                    <label class="layui-form-label xbs768">日期范围</label>
                    <div class="layui-input-inline xbs768">
                        <input class="layui-input" placeholder="开始日" id="LAY_demorange_s">
                    </div>
                    <div class="layui-input-inline xbs768">
                        <input class="layui-input" placeholder="截止日" id="LAY_demorange_e">
                    </div>
                    <div class="layui-input-inline">
                        <input type="text" name="username"  placeholder="请输入用户名" autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-input-inline" style="width:80px">
                        <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                    </div>
                </div>
            </div>
        </form>
        <xblock><button class="layui-btn layui-btn-danger" onclick="recoverAll()"><i class="layui-icon">&#xe640;</i>批量恢复</button><span class="x-right" style="line-height:40px">共有数据：{{$master}} 条</span></xblock>
        <table class="layui-table">
            <thead>
            <tr>
                <th><input type="checkbox" name="" value=""></th>
                <th>ID</th>
                <th>名称</th>
                <th>加入时间</th>
                <th>修改时间</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($masters as $master)
                <tr>
                    <td>
                        <input type="checkbox" value="1" name="">
                    </td>
                    <td id="id">{{$master->id}}</td>
                    <td id="name"><u style="cursor:pointer" onclick="member_show('张三','member-show.html','10001','360','400')">{{$master->name}}</u></td>
                    <td>{{$master->created_at}}</td>
                    <td>{{$master->updated_at}}</td>
                    <td class="td-status">
                            <span class="layui-btn layui-btn-danger layui-btn-mini">
                                已删除
                            </span>
                    </td>
                    <td class="td-manage">
                        <a style="text-decoration:none" onclick="master_recover({{$master->id}})" href="javascript:;" title="恢复">
                            <i class="layui-icon">&#xe618;</i>
                        </a>
                        <a title="彻底删除" href="javascript:;" onclick="member_unset(this,'1')"
                           style="text-decoration:none">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!-- 右侧内容框架，更改从这里结束 -->
    </div>
</div>
<!-- 右侧主体结束 -->
</div>
<script>
    function master_recover(id){
        layer.confirm('您确定要恢复吗？', {
            btn: ['确定','取消'] //按钮,
        },function() {
            $.ajax({
                type: "post",
                url: "{{url('admin/master/master_recover/')}}/" + id,
                dataType: "json",
                data: {"id": id, "_token": "{{csrf_token()}}"},
                success: function (data) {
                    //                alert("ID:" + data.id + "\nName:" + data.name);
                    if (data == "") {
                        layer.msg('服务器错误', {icon: 5, time: 5000});
                        location.href = location.href;
                    }
                    if(data.message != 0){
                        layer.msg(data.message, {icon: 6, time: 5000});
                        location.href = location.href;
                    }
                    layer.msg(data.message, {icon: 6, time: 5000});
                    location.href = "{{url('admin/master')}}";
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        });
    };
</script>
@endsection