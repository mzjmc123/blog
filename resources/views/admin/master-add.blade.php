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
                    <span class="x-red">*</span>昵称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="username" required="" lay-verify="nikename"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_pass" class="layui-form-label">
                    <span class="x-red">*</span>密码
                </label>
                <div class="layui-input-inline">
                    <input type="password" id="password" name="pass" required="" lay-verify="pass"
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
                    <input type="password" id="passwords" name="repass" required="" lay-verify="repass"
                           autocomplete="off" class="layui-input">
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
                var name = $("#name").val();
                var password = $("#password").val();
                var passwords = $("#passwords").val();
                if(name == ""){
                    layer.msg('用户名不能为空',{icon : 5, time : 2000});
                }
                $.ajax({
                    type: "post",
                     url: "{{url('admin/master/add')}}",
                    dataType: "json",
                    data: {"name" : name,"password" : password,"passwords" :passwords,"_token":"{{csrf_token()}}"},
                    success:function(data){
                        if(data.status != 0){
                            layer.msg(data.message,{icon : 5, time : 5000});
                        }
                        layer.msg(data.message,{icon : 6, time : 5000});
                        window.location.href = "{{url('/admin/master')}}";
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