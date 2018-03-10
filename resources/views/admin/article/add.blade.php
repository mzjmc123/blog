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
                    <span class="x-red">*</span>文章分类
                </label>
                <div class="layui-input-inline">
                    <select style=" width: 188px; height: 35px; background-color: #5e5e5e" id="cate_pid"  name="cate_pid">
                        <option value="0">==顶级分类==</option>
                        @foreach($datas as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>文章标题
            </label>
            <div class="layui-input-inline">
                <input type="text" id="title" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">

            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>图片
            </label>
            <td>
                <input type="text" class="layui-input" lay-verify="nikename" id="url" name="url" style="width: 300px; ">
                <input class="uploadify" id="file_upload" name="file_upload" type="file" multiple="true">
                <script src="{{asset('uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
                <link rel="stylesheet" type="text/css" href="{{asset('uploadify/uploadify.css')}}">
            </td>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
            </label>
            <div class="layui-input-inline">
                <img src="" alt="" id="urls" style="width: 350px; height: 100px;">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>关键词
            </label>
            <div class="layui-input-inline">
                <input type="text" id="tag" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>描述
            </label>
            <div class="layui-input-inline">
                <input type="text" id="description" name="username" required="" lay-verify="nikename"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">文章类容
            </label>
            <div class="layui-input-inline" >
                <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.config.js')}}"></script>
                <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/ueditor.all.min.js')}}"></script>
                <script type="text/javascript" charset="utf-8" src="{{asset('admin/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                <script id="editor" name="content" type="text/plain" style="width:900px;height:500px;"></script>
                <script type="text/javascript">
                    var ue = UE.getEditor('editor');
                </script>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_username" class="layui-form-label">
                <span class="x-red">*</span>创建人
            </label>
            <div class="layui-input-inline">
                <input type="text" id="author" name="username" required="" lay-verify="nikename"
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
                var cate_id = $("#cate_pid").val();
                var title = $("#title").val();
                var url = $("#url").val();
                var tag = $("#tag").val();
                var description = $("#description").val();
                var author = $("#author").val();
                var editor =  ue.getContent();
                if(title == ""){
                    layer.msg('文章标题不能为空',{icon : 5, time : 2000});
                }else if(description == ""){
                    layer.msg('文章描述不能为空',{icon : 5, time : 2000});
                }else if(editor == ""){
                    layer.msg('文章类容不能为空',{icon : 5, time : 2000});
                }else if(author == ""){
                    layer.msg('创建人不能为空',{icon : 5, time : 2000});
                }else {
                    $.ajax({
                        type: "post",
                        url: "{{url('admin/article/add')}}",
                        dataType: "json",
                        data: {
                            "cate_id": cate_id, "title": title, "url": url, "tag": tag, "description": description,
                            "editor": editor, "author": author, "_token": "{{csrf_token()}}"
                        },
                        success: function (data) {
                            if (data.status != 0) {
                                layer.msg(data.message, {icon: 5, time: 5000});
                            }
                            layer.msg(data.message, {icon: 6, time: 5000});
                            window.location.href = "{{url('admin/article/index')}}";
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                            console.log(status);
                            console.log(error);
                        }
                    });
                }
            });


    });
</script>
<script type="text/javascript">
    <?php $timestamp = time();?>
     $(function() {
        $('#file_upload').uploadify({
            'buttonText':'上传图片',
            'formData'     : {
                'timestamp' : '<?php echo $timestamp;?>',
                '_token'     : "{{csrf_token()}}",
            },
            'swf'      : '{{asset('uploadify/uploadify.swf')}}',
            'uploader' : "{{url('admin/article/upload')}}",
            'onUploadSuccess' : function(file,data,response){
                //alert(data);
                $('input[name=url]').val(data);
                $('#urls').attr('src', '/../'+data);
            }
        });
    });
</script>
<style>
    .uploadify{dposition:absolute;  left:420px;  top:-48px}
    .uploadify-button{border:none; border-radius:50px; margin-top:8px;}
    .uploadify-button-text{color: #FFF; margin:0;}
</style>
@endsection