<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Navs;
use App\Tool\M3Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\CountValidator\Exception;

class NavsController extends Controller
{

    public function index(){

        $navs =DB::table('navs')->paginate(10);
        $nav = DB::table('navs')->count(DB::raw('DISTINCT id'));
        return view('admin.navs.index',compact('navs','nav'));
    }

    public function add(Request $request)
    {
        if($request->post()) {
            $m3_result = new M3Result();
            $nav_name = $request->input('nav_name');
            $nav_title = $request->input('nav_title');
            $nav_url = $request->input('nav_url');
            $nav_author = $request->input('nav_author');
            if($nav_name == ""){
                $m3_result->status = 1;
                $m3_result->message = "导航名称不能为空";
                $m3_result->ToJson();
            }else if($nav_title == ""){
                $m3_result->status = 2;
                $m3_result->message = "导航标题不能为空";
                $m3_result->ToJson();
            }else if($nav_url == ""){
                $m3_result->status = 3;
                $m3_result->message = "url不能为空";
                $m3_result->ToJson();
            }else if($nav_author == ""){
                $m3_result->status = 4;
                $m3_result->message = "创建人不能为空";
                $m3_result->ToJson();
            }else{
               try{
                   DB::beginTransaction();
                   $navs = DB::table('navs')->insert([
                       'nav_name' => $nav_name,
                       'nav_title' => $nav_title,
                       'nav_url' => $nav_url,
                       'nav_author' => $nav_author,
                       'nav_status' =>1,
                       'created_at' => date('Y-m-d H:i:s',time()),
                   ]);
                   if($navs){
                       DB::commit();
                       $m3_result->status = 0;
                       $m3_result->message = '导航添加成功';
                       return $m3_result->ToJson();
                   }
               }catch (Exception $e){

                   DB::rollback();
               }
            }
        }
        return view('admin.navs.add');
    }

    public function navs_stop(Request $request)
    {
        $id = $request->input('id');
        $nav = Navs::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($nav == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('navs')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'nav_status' =>2,
                ]);
            if($master) {
                $m3_result->status = 0;
                $m3_result->message = '状态修改成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }
    public function navs_recover(Request $request){
        $id = $request->input('id');
        $nav = Navs::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($nav == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('navs')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'nav_status' =>1,
                ]);
            if($master) {
                $m3_result->status = 0;
                $m3_result->message = '状态修改成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function navs_edit(Request $request, $id)
    {

        $nav = Navs::where(['id' => $id])->first();
        return view('admin.navs.edit',compact('nav'));
    }

    public function storg(Request $request)
    {
        if($request->post()){
            $m3_result = new M3Result();
            $id = $request->input('id');
            $nav_name = $request->input('nav_name');
            $nav_title = $request->input('nav_title');
            $nav_url = $request->input('nav_url');
            $nav_author = $request->input('nav_author');
            if($nav_name == ""){
                $m3_result->status = 1;
                $m3_result->message = "导航名称不能为空";
                $m3_result->ToJson();
            }else if($nav_title == ""){
                $m3_result->status = 2;
                $m3_result->message = "导航标题不能为空";
                $m3_result->ToJson();
            }else if($nav_url == ""){
                $m3_result->status = 3;
                $m3_result->message = "url不能为空";
                $m3_result->ToJson();
            }else if($nav_author == ""){
                $m3_result->status = 4;
                $m3_result->message = "创建人不能为空";
                $m3_result->ToJson();
            }else{
                try{
                    DB::beginTransaction();
                    $navs = DB::table('navs')->where(['id' => $id])->update([
                        'nav_name' => $nav_name,
                        'nav_title' => $nav_title,
                        'nav_url' => $nav_url,
                        'nav_author' => $nav_author,
                        'updated_at' => date('Y-m-d H:i:s',time()),
                    ]);
                    if($navs){
                        DB::commit();
                        $m3_result->status = 0;
                        $m3_result->message = '导航修改成功';
                        return $m3_result->ToJson();
                    }
                }catch (Exception $e){

                    DB::rollback();
                }
            }
        }
    }
}
