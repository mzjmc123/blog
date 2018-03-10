@extends('layouts.admin.heade')
@section('content')
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <div class="wrapper">
        @include('layouts.admin.left')
        <!-- 右侧主体开始 -->
        <div class="page-content">
            <fieldset class="layui-elem-field layui-field-title site-title">
                <legend><a name="default">信息统计</a></legend>
          <div class="content">
            <table class="layui-table">
                <thead>
                    <tr>
                        <th>统计</th>
                        <th>资讯库</th>
                        <th>图片库</th>
                        <th>产品库</th>
                        <th>用户</th>
                        <th>管理员</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>总数</td>
                        <td>92</td>
                        <td>9</td>
                        <td>0</td>
                        <td>8</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>今日</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>昨日</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>本周</td>
                        <td>2</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>本月</td>
                        <td>2</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
          </div>
        </div>
        <!-- 右侧主体结束 -->
    </div>
@endsection
