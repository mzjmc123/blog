<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Tool\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class CateGoryController extends Controller
{
    public function index( Request $request)
    {
        //$cates = DB::table('category')->paginate(20);
       // $cates = Category::tree();
        $data = DB::table('category')->get();
//        $data = DB::table('category')->paginate(1000);
        $cates = $this->tree($data);
        $cate = DB::table('category')->where('status','!=',2)->count(DB::raw('DISTINCT id'));
        return view('admin.cate.index',compact('cates','cate'));
    }
    //递归函数分类列表
    public function tree($data,$parentgcid = 0, $level = 0)
    {
        $arr = array();
        foreach($data as $v){
            if($v->cate_pid == $parentgcid){
                $v->level = $level;
                $v->html = str_repeat('_',($level * 2 ));
                $arr[] = $v;
                $arr = array_merge($arr,$this->tree($data,$v->id,$level+1));
            }
        }
        return $arr;
    }
    public function add(Request $request)
    {
        $m3_result = new M3Result();
        if($request->post()){
              $cate_pid = $request->input('cate_pid');
              $name = $request->input('name', '');
              $title = $request->input('title', '');
              $keywords = $request->input('keywords', '');
              $description = $request->input('description', '');
              $author = $request->input('author', '');

            if($name == "" || $title == "" || $keywords == "" || $description == "" || $author == ""){
                $m3_result->status = 1;
                $m3_result->message = "请检查必填项";
                return $m3_result->ToJson();
            }
            $cate =Category::where(['name' => $name])->first();
            if($cate){
                $m3_result->status = 2;
                $m3_result->message = "分类名称不能重复";
                return $m3_result->ToJson();
            }
                try{
                    DB::beginTransaction();
                    $category = DB::table('category')->insert([
                        'name' => $name,
                        'title' => $title,
                        'keywords' => $keywords,
                        'description' => $description,
                        'author' => $author,
                        'cate_pid' => $cate_pid,
                        'status'=>1,
                        'created_at' =>date('Y-m-d H:i:s', time())
                    ]);
                    if($category){
                        DB::commit();
                        $m3_result->status = 0;
                        $m3_result->message = '分类添加成功';
                        return $m3_result->ToJson();
                    }

                }catch (Exception $e){
                    DB::rollback();
                }
        }

        $_data = DB::table('category')->where('cate_pid',0)->where('status',1)->get();
        return view('admin.cate.add',compact('_data'));
    }

    public function cate_stop(Request $request)
    {
        $id = $request->input('id');
        $nav = Category::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($nav == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('category')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'status' =>2,
                ]);
            if($master) {
                 DB::table('category')->where(['cate_pid' => $id])
                    ->update([
                        'updated_at' =>date('Y-m-d H:i:s', time()),
                        'status' =>2,
                    ]);
                    $m3_result->status = 0;
                    $m3_result->message = '状态修改成功';
                    DB::commit();
                    return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function cate_recover(Request $request)
    {
        $id = $request->input('id');
        $nav = Category::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($nav == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            if($nav->status == 2 && $nav->cate_pid == 1){
                $master = DB::table('category')->where(['id' => $id])
                    ->update([
                        'updated_at' =>date('Y-m-d H:i:s', time()),
                        'status' =>1,
                        'cate_pid' =>0,
                    ]);
                if($master){
                    $m3_result->status = 0;
                    $m3_result->message = '状态修改成功';
                    DB::commit();
                    return $m3_result->ToJson();
                }
            }
            $master = DB::table('category')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'status' =>1,
                ]);
            if($master) {
                $cate = DB::table('category')->where(['cate_pid' => $id])
                    ->update([
                        'updated_at' =>date('Y-m-d H:i:s', time()),
                        'status' =>1,
                    ]);
                $m3_result->status = 0;
                $m3_result->message = '状态修改成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function cate_edit(Request $request, $id)
    {
        $file = Category::find($id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin.cate.edit',compact('file','data'));
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $title = $request->input('title');
        $keywords = $request->input('keywords');
        $description = $request->input('description');
        $cate_pid = $request->input('cate_pid');
        $author = $request->input('author');
        $m3_result = new M3Result();
        if($name == "" || $title == "" || $keywords == "" || $description == "" || $author == ""){
            $m3_result->status = 1;
            $m3_result->message = "请检查必填项";
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $category = DB::table('category')->where(['id' => $id])
                ->update([
                    'name' => $name,
                    'title' => $title,
                    'keywords' => $keywords,
                    'description' => $description,
                    'author' => $author,
                    'cate_pid' => $cate_pid,
                    'created_at' =>date('Y-m-d H:i:s', time())
                ]);
            if($category){
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '分类信息修改成功';
                return $m3_result->ToJson();
            }

        }catch (Exception $e){
            DB::rollback();
        }
    }

}
