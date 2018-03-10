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
                <span class="x-red">*</span>分类
            </label>
            <select style=" width: 188px; height: 35px; background-color: #5e5e5e" id="cate_pid"  name="cate_pid">
                <option value="0">==顶级分类==</option>
                @foreach($data as $da)
                    <option value="{{$da->id}}"
                            @if($da->id ==$file->cate_pid) selected @endif
                    >{{$da->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>分类名称
            </label>
            <div class="layui-input-inline">
                <input type="text" id="name" name="name" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input" value="{{$file->name}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>分类标题
            </label>
            <div class="layui-input-inline">
                <input type="text" id="title" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input" value="{{$file->title}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>关键词
            </label>
            <div class="layui-input-inline">
                <input type="text" id="keywords" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input" value="{{$file->keywords}}"style="width: 400px;" >
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>描述
            </label>
            <div class="layui-input-inline">
                <textarea  id="description" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-textarea" style="width: 400px;">{{$file->description}}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>创建人
            </label>
            <div class="layui-input-inline">
                <input type="text" id="author" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input" value="{{$file->author}}">
            </div>
        </div>
        <input type="hidden" id="id" value="{{$file->id}}">
    </div>
    <div class="layui-form-item">
        <label for="L_repass" class="layui-form-label">
        </label>
        <button  class="layui-btn" lay-filter="add" lay-submit="">
            修改
        </button>
    </div>
        <!-- 右侧内容框架，更改从这里结束 -->
    </div>
</div>
    </div>
<script>
    $(document).ready(function (){
            $(".layui-btn").click(function (){
                var id = $("#id").val();
                var cate_pid = $("#cate_pid").val();
                var name = $("#name").val();
                var title = $("#title").val();
                var keywords = $("#keywords").val();
                var description = $("#description").val();
                var author = $("#author").val();
                if(name == ""){
                    layer.msg('分类名称不能为空',{icon : 5, time : 2000});
                }else if(title == ""){
                    layer.msg('分类标题不能为空',{icon : 5, time : 2000});
                }else if(keywords == ""){
                    layer.msg('分类关键字不能为空',{icon : 5, time : 2000});
                }else if(description == ""){
                    layer.msg('描述不能为空',{icon : 5, time : 2000});
                } else if(author == ""){
                    layer.msg('创建人不能为空',{icon : 5, time : 2000});
                }
                $.ajax({
                    type: "post",
                     url: "{{url('admin/cate/update')}}",
                    dataType: "json",
                    data: {"id":id,"cate_pid":cate_pid,"name" : name,"title" : title,"keywords" :keywords,"description":description,"author" :author,"_token":"{{csrf_token()}}"},
                    success:function(data){
                        if(data.status != 0){
                            layer.msg(data.message,{icon : 5, time : 5000});
                        }
                        layer.msg(data.message,{icon : 6, time : 5000});
                        window.location.href = "{{url('admin/cate/index')}}";
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