<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CommonController extends Controller
{
    public function upload()
    {
        $file = Input::file('Filedata');
        if ($file->isValid()) {
            //获取临时文件的绝对路径
            //  $realPath = $file -> getRealPath();
            //上传文件的后缀
            $entenion = $file->getClientOriginalExtension();
            //移动文件并充命名
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entenion;
            $path = $file->move(base_path() . '/public/uploads', $newName);
            $filepath = '/uploads/' . $newName;
            return $filepath;
        }
    }
}
