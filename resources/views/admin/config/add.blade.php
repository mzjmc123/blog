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
                    <span class="x-red">*</span>配置名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="nikename"
                           autocomplete="off" class="layui-input">
                </div>
            </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>配置标题
            </label>
            <div class="layui-input-inline">
                <input type="text" id="title" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>类型
            </label>
            <div class="layui-form-inline">
                <input type="radio" id="type" name="type" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-form-radio" value="input" checked onclick="showTr()"> Input　
                <input type="radio" id="type" name="type" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-form-radio" value="textarea" onclick="showTr()">textarea　
                <input type="radio" id="type" name="type" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-form-radio" value="radio" onclick="showTr()">radio　
            </div>
        </div>
        <div class="layui-form-item value">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>类性值
            </label>
            <div class="layui-input-inline" style="width: auto">
                <input type="text" id="value" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
                <p><i style="color: red">类型值只有在radio的情况下才需要配置 格式 1|开启，0|关闭</i></p>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>说明
            </label>
            <div class="layui-input-inline">
               <textarea  id="tips" name="username" required="" lay-verify="nikename"
                          autocomplete="off" class="layui-textarea" style="width: 400px;"></textarea>
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
    showTr();
    function showTr(){
        var type = $('input[name=type]:checked').val();
        if (type=='radio'){
            $('.value').show();
        }else {
            $('.value').hide();
        }

    }
    $(document).ready(function (){
            $(".layui-btn").click(function (){
                var name = $("#name").val();
                var title = $("#title").val();
                var tips = $("#tips").val();
                var type  = $('input[name="type"]:checked').val();
                var value  = $("#value").val();
                if(name == ""){
                    layer.msg('名称不能为空',{icon : 5, time : 2000});
                }else if(title == ""){
                    layer.msg('标题不能为空',{icon : 5, time : 2000});
                }else{
                    $.ajax({
                        type: "post",
                         url: "{{url('admin/config/add')}}",
                        dataType: "json",
                        data: {"title":title, "tips":tips,"type":type,"value":value,"name" : name,"_token":"{{csrf_token()}}"},
                        success:function(data){
                            if(data.status != 0){
                                layer.msg(data.message,{icon : 5, time : 5000});
                                window.location.href = location.href;
                            }
                            layer.msg(data.message,{icon : 6, time : 5000});
                            window.location.href = "{{url('admin/config/index')}}";
                        },
                        error:function(xhr,status,error){
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            });

    });
</script>
@endsection