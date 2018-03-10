@extends('layouts.admin.heade')
@section('content')
        <!-- 顶部结束 -->
<!-- 中部开始 -->
<div class="wrapper">
    @include('layouts.admin.left')
            <!-- 右侧主体开始 -->
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
            <xblock>
                <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
                <button class="layui-btn" onclick="article_add()"><i class="layui-icon">&#xe608;</i>添加</button>
                {{--<span class="x-right" style="line-height:40px">共有数据：{{$cate}}条</span>--}}
            </xblock>

            <table class="layui-table">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="" value="">
                    </th>
                    <th>ID</th>
                    <th>文章标题</th>
                    <th>查看次数</th>
                    <th>创建人</th>
                    <th>加入时间</th>
                    <th>修改时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach($articles as $article)
                <tr>
                    <td>
                        <input type="checkbox" value="1" name="">
                    </td>
                    <td>{{$article->id}}</td>
                    <td><u style="cursor:pointer" onclick="member_show()">{{$article->title}}</u></td>
                    <td >{{$article->view}}</td>
                    <td >{{$article->author}}</td>
                    <td>{{$article->created_at}}</td>
                    <td>{{$article->updated_at}}</td>
                    @if($article->status == 1)
                    <td class="td-status">
                        <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>
                    </td>
                    @else
                        <td class="td-status">
                            <span class="layui-btn layui-btn-disabled layui-btn-mini">
                                已停用
                            </span>
                        </td>
                    @endif
                    <td class="td-manage">
                        @if($article->status == 1)
                        <a style="text-decoration:none" onclick="article_stop({{$article->id}})" href="javascript:;" title="停用">
                            <i class="layui-icon">&#xe601;</i>
                        </a>
                        @else
                            <a style="text-decoration:none" onclick="article_recover({{$article->id}})" href="javascript:;" title="恢复"><i class="layui-icon">&#xe618;</i>
                            </a>
                        @endif
                        <a title="编辑" href="javascript:;" onclick="article_edit({{$article->id}})"
                           class="ml-5" style="text-decoration:none">
                            <i class="layui-icon">&#xe642;</i>
                        </a>
                    </td>

                </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 右侧内容框架，更改从这里结束 -->
            <div class="layui-laypage page-list">
                {{ $articles->links()}}
            </div>
        </div>

    </div>

    <!-- 右侧主体结束 -->
</div>
<style>
    .page-list li{
        float:left;
    }
    .page-list{

    }
</style>
<script>
    function article_add(){
       location.href = "{{url('admin/article/add')}}";
    }
    function article_recover(id){
        layer.confirm('您确定要恢复吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/article/article_recover')}}",
                dataType: "json",
                data: {"id": id,"_token": "{{csrf_token()}}"},
                success:function(data){
                    if(data != 0){
                        layer.msg(data.message,{icon: 5, time: 5000});
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
    function article_stop(id){
        layer.confirm('您确定要停用掉吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/article/article_stop')}}",
                dataType: "json",
                data: {"id": id,"_token": "{{csrf_token()}}"},
                success:function(data){
                    if(data != 0){
                        layer.msg(data.message,{icon: 5, time: 5000});
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
        });
    }
    function article_edit(id){
        location.href = "{{url('admin/article/article_edit/')}}/"+id;
    }
</script>
@endsection
