@extends('layouts.admin.heade')
@section('content')
        <!-- 顶部结束 -->
<!-- 中部开始 -->
<div class="wrapper">
    @include('layouts.admin.left')
            <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="content">
            <xblock>
                <button class="layui-btn" onclick="mester_add()"><i class="layui-icon">&#xe608;</i>添加</button>
                <span class="x-right" style="line-height:40px">共有用户：{{$master}} 条</span>
            </xblock>
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
                    @if($master->status == 1)
                        <td class="td-status">
                            <span class="layui-btn layui-btn-normal layui-btn-mini">
                                已启用
                            </span>
                        </td>
                        @else
                        <td class="td-status">
                            <span class="layui-btn layui-btn-disabled layui-btn-mini">
                                已停用
                            </span>
                        </td>
                    @endif

                    <td class="td-manage">
                        <a style="text-decoration:none" onclick="member_stop({{$master->id}})" href="javascript:" title="停用">
                            <i class="layui-icon">&#xe601;</i>
                        </a>
                        <a title="编辑" href="javascript:;" onclick="member_edit('编辑','member-edit.html','4','','510')"
                           class="ml-5" style="text-decoration:none">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                        <a style="text-decoration:none"   onclick="member_password({{$master->id}})" href="javascript:" title="修改密码">
                            <i class="layui-icon">&#xe631;</i>
                        </a>
                        <a title="删除" href="javascript:;" onclick="member_del({{$master->id}})"
                           style="text-decoration:none">
                            <i class="layui-icon">&#xe640;</i>
                        </a>
                    </td>
                </tr>
            @endforeach
                </tbody>
            </table>
        </div>
            <!-- 右侧内容框架，更改从这里结束 -->
        <div class="layui-form">
            <div class="layui-form-item">
                <input type="hidden" name="id" id="L_id" value="">
                <label for="L_username" class="layui-form-label">
                    昵称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="L_name" name="username" disabled="" value="" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                    <span class="x-red">*</span>旧密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="L_oldpass" name="oldpass" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_pass" class="layui-form-label">
                    <span class="x-red">*</span>新密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="L_pass" name="newpass" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    6到16个字符
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                    <span class="x-red">*</span>确认密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="L_repass" name="repass" required="" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn pass" lay-filter="save" lay-submit="">
                    确定
                </button>
            </div>
        </div>
    </div>

    <!-- 右侧主体结束 -->
</div>
<script>
    $(document).ready(function(){
        $(".layui-form").hide();
        $(".content").show();
        $(".pass").click(function(){
            var id = $("#L_id").val();
            var L_name = $("#L_name").val();
            var L_oldpass = $("#L_oldpass").val();
            var L_pass = $("#L_pass").val();
            var L_repass = $("#L_repass").val();
            $.ajax({
                type:"post",
                url:"{{url('admin/master/store_password')}}",
                dataType: "json",
                data: {"id":id,"name": L_name, "password":L_oldpass,
                        "pass":L_pass,"repass":L_repass,"_token": "{{csrf_token()}}"},
                success:function(data){
                   if(data.status != 0){
                       layer.msg(data.message,{icon: 5, time: 5000});
                   }
                    layer.msg(data.message,{icon: 6, time: 5000});
                    location.href = "{{url('admin/master')}}";
                },
                error:function(xhr,status,error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });

        });
    });
    function mester_add(){
        location.href = "{{url('admin/master/add')}}";
    }
    function member_password(Id){
        //var Id = $("#id").text();
        $.ajax({
            type:"post",
            url:"{{url('admin/master/update_password/')}}/"+Id,
            dataType: "json",
            data: {"id": Id,"_token": "{{csrf_token()}}"},
            success:function(data){
//                alert("ID:" + data.id + "\nName:" + data.name);
                if(data == ""){
                    layer.msg('服务器错误',{icon: 5, time: 5000});
                     location.href = location.href;
                }
                $("#L_name").val(data.name);
                $("#L_id").val(data.id);
                $(".content").hide();
                $(".layui-form").show();
            },
            error:function(xhr,status,error){
                console.log(xhr);
                console.log(status);
                console.log(error);
            }
        });
    }
    function member_stop(id){
        layer.confirm('您确定要停用掉吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/master/member_stop/')}}/"+id,
                dataType: "json",
                data: {"id": id,"_token": "{{csrf_token()}}"},
                success:function(data){
    //                alert("ID:" + data.id + "\nName:" + data.name);
                    if(data == ""){
                        layer.msg('服务器错误',{icon: 5, time: 5000});
                        location.href = location.href;
                    }
                    layer.msg(data.message,{icon: 6, time: 5000});
                    location.href = location.href;
                },
                error:function(xhr,status,error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
    })};
    function member_del(id){
        layer.confirm('您确定要删除吗掉吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/master/member_del/')}}/"+id,
                dataType: "json",
                data: {"id": id,"_token": "{{csrf_token()}}"},
                success:function(data){
                    if(data == ""){
                        layer.msg('服务器错误',{icon: 5, time: 5000});
                        location.href = location.href;
                    }
                    if(data != 0){
                        layer.msg(data.message,{icon: 5, time: 5000});
                        location.href = location.href;
                    }
                    layer.msg(data.message,{icon: 6, time: 5000});
                    //location.href = location.href;
                },
                error:function(xhr,status,error){
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });
        })};
</script>
@endsection
