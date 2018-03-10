@extends('layouts.admin.heade')
@section('content')
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <div class="wrapper">
        @include('layouts.admin.left')
        <!-- 右侧主体开始 -->
        <div class="page-content">
            <fieldset class="layui-elem-field layui-field-title site-title">
                <legend><a name="default">网站配置</a></legend>
          <div class="content">
              <xblock>
                  <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon">&#xe640;</i>批量删除</button>
                  <button class="layui-btn" onclick="config_add()"><i class="layui-icon">&#xe608;</i>添加</button>
                  {{--<span class="x-right" style="line-height:40px">共有数据：{{$cate}}条</span>--}}
              </xblock>
              <form action="{{url('admin/config/content')}}" method="post">
                  {{csrf_field()}}
              <table class="layui-table">
                  <thead>
                  <tr>
                      <th>
                          <input type="checkbox" name="" value="">
                      </th>
                      <th>ID</th>
                      <th>配置名称</th>
                      <th>配置标题</th>
                      <th>配置类容</th>
                      <th>创建时间</th>
                      <th>修改时间</th>
                      <th>状态</th>
                      <th>操作</th>
                  </tr>
                  </thead>
                      <tbody>
                      @foreach($configs as $config)
                          <tr>
                              <td>
                                  <input type="checkbox" value="1" name="">
                              </td>
                              <td>{{$config->id}}</td>
                              <td >{{$config->name}}</td>
                              <td >{{$config->title}}</td>
                              <td >
                                  <input type="hidden" id="id" name="id[]" value="{{$config->id}}">
                                  {!! $config->html !!}
                              </td>
                              <td>{{$config->created_at}}</td>
                              <td>{{$config->updated_at}}</td>
                              @if($config->status == 1)
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
                                  @if($config->status == 1)
                                      <a style="text-decoration:none" onclick="config_stop({{$config->id}})" href="javascript:;" title="停用">
                                          <i class="layui-icon">&#xe601;</i>
                                      </a>
                                  @else
                                      <a style="text-decoration:none" onclick="config_recover({{$config->id}})" href="javascript:;" title="恢复"><i class="layui-icon">&#xe618;</i>
                                      </a>
                                  @endif
                                  <a title="编辑" href="javascript:;" onclick="config_edit({{$config->id}})"
                                     class="ml-5" style="text-decoration:none">
                                      <i class="layui-icon">&#xe642;</i>
                                  </a>
                              </td>

                          </tr>
                      @endforeach
                      </tbody>
              </table>
                  <div class="layui-form-item">
                      <label for="L_repass" class="layui-form-label">
                      </label>
                      <button  class="layui-btn" lay-filter="add" lay-submit="">
                          提交
                      </button>
                  </div>
              </form>

          </div>
        </div>

        <!-- 右侧主体结束 -->
    </div>
<style>
    .layui-form-item{
        position:absolute;
        left:170px;
        top:360px;
    }
</style>
<script>
    function config_add(){
        location.href = "{{url('admin/config/add')}}";
    }
    function config_edit(id){
        location.href = "{{url('admin/config/config_edit/')}}/"+id;
    }
    function config_stop(id){
        layer.confirm('您确定要停用掉吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/config/config_stop')}}",
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
    function config_recover(id){
        layer.confirm('您确定要启用吗？', {
            btn: ['确定','取消'] //按钮,
        },function(){
            $.ajax({
                type:"post",
                url:"{{url('admin/config/config_recover')}}",
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
</script>

@endsection
