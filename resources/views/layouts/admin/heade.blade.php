<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BLOG 管理平台</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('admin/style/favicon.ico')}}" type="image/x-icon" />
    <link rel="stylesheet" href="{{asset('admin/style//css/font.css')}}">
    <link rel="stylesheet" href="{{asset('admin/style/css/xadmin.css')}}">
    <link rel="stylesheet" href="https://cdn.bootcss.com/Swiper/3.4.2/css/swiper.min.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/Swiper/3.4.2/js/swiper.jquery.min.js"></script>
    <script src="{{asset('admin/style/lib/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('admin/style/js/xadmin.js')}}"></script>


</head>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="./index.html">一笑傲三界</a></div>
    <div class="open-nav"><i class="iconfont">&#xe699;</i></div>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;">{{session()->get('master')->name}}</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <dd><a href="">个人信息</a></dd>
                <dd><a href="{{url('admin/index')}}">切换帐号</a></dd>
                <dd><a href="{{url('admin/logout')}}">退出</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="/">前台首页</a></li>
    </ul>
</div>
@yield('content')
{{--@include('layouts.admin.botton')--}}
@include('layouts.admin.images')
</body>
</html>
