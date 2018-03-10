@extends('layouts.admin.heade')
@section('content')
        <!-- 顶部结束 -->
<!-- 中部开始 -->
<div class="wrapper">
    @include('layouts.admin.left')
<div class="page-content">
    <div class="content">
        <!-- 右侧内容框架，更改从这里开始 -->
            <div class="layui-form-item">
                <label for="L_username" class="layui-form-label">
                    <span class="x-red">*</span>导航名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="nav_name" name="username" required="" lay-verify="nikename"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>导航标题
            </label>
            <div class="layui-input-inline">
                <input type="text" id="nav_title" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>导航连接
            </label>
            <div class="layui-input-inline">
                <input type="text" id="nav_url" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input" value="http://" style="width: 400px;">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>创建人
            </label>
            <div class="layui-input-inline">
                <input type="text" id="nav_author" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    增加
                </button>
            </div>
        <!-- 右侧内容框架，更改从这里结束 -->
    </div>
</div>
    </div>
<script>
    $(document).ready(function (){
            $(".layui-btn").click(function (){
                var nav_name = $("#nav_name").val();
                var nav_title = $("#nav_title").val();
                var nav_url = $("#nav_url").val();
                var nav_author = $("#nav_author").val();
                if(nav_name == ""){
                    layer.msg('导航名称不能为空',{icon : 5, time : 2000});
                }else if(nav_title == ""){
                    layer.msg('导航标题不能为空',{icon : 5, time : 2000});
                }else if(nav_url == ""){
                    layer.msg('导航连接不能为空',{icon : 5, time : 2000});
                }else if(nav_author == ""){
                    layer.msg('创建人不能为空',{icon : 5, time : 2000});
                }
                $.ajax({
                    type: "post",
                     url: "{{url('admin/navs/add')}}",
                    dataType: "json",
                    data: {"nav_name" : nav_name,"nav_title" : nav_title,"nav_url" :nav_url,"nav_author":nav_author,"_token":"{{csrf_token()}}"},
                    success:function(data){
                        if(data.status != 0){
                            layer.msg(data.message,{icon : 5, time : 5000});
                        }
                        layer.msg(data.message,{icon : 6, time : 5000});
                        window.location.href = "{{url('admin/navs/index')}}";
                    },
                    error:function(xhr,status,error){
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                    }
                });
            });

    });
</script>
@endsection