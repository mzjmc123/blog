<?php

namespace App\Http\Controllers\Admin;
use App\Models\Master;
use App\Tool\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Mockery\CountValidator\Exception;

class UserController extends Controller
{

    public function index(Request $request)
    {

        $m3_result = new M3Result();
        if($request->isMethod('post')){
            $name = $request->input('name','');
            $password = $request->input('password','');
            if($name == '' || $password == ''){
                $m3_result->status = 1;
                $m3_result->message = '用户名或密码不能为空';
                return $m3_result->ToJson();
            }
            $master = Master::where(['name' => $name])->first();
            if($master == null){
                $m3_result->status = 3;
                $m3_result->message = '用户不存在';
                return $m3_result->ToJson();
            }
            if($master->name != $name || !Hash::check($password,$master->password)){
                $m3_result->status = 2;
                $m3_result->message = '用户名或密码错误';
                return $m3_result->ToJson();
            }
            if($master->status !=1){
                $m3_result->status = 4;
                $m3_result->message = '该用户以冻结，请联系管理员激活该用户';
                return $m3_result->ToJson();
            } else{
                $request->session()->put('master',$master);
                $m3_result->status = 0;
                $m3_result->message = '登陆成功';
                return $m3_result->ToJson();
            }
        }
        return view('index');
    }

    public function master(){

        $masters = DB::table('master')->select('id','name','status','created_at','updated_at')->where('status','!=',3)->get();
        //dd($masters);
        $master = DB::table('master')->where('status','!=',3)->count(DB::raw('DISTINCT id'));

        return view('admin.list',compact('masters','master'));
    }

    public function add(Request $request){

        if($request->post()){
            $name = $request->input('name' , '');
            //这两个密码没有必要要
            $password = $request->input('password' , '');
            $passwords = $request->input('passwords' , '');
            $m3_result = new M3Result();
            if($name == ""){
                $m3_result->status = 1;
                $m3_result->message = '用户名不能为空';
                return $m3_result->ToJson();
            }
            $master = Master::where('name',$name)->first();
            if($master){
                $m3_result->status = 2;
                $m3_result->message = '用户已存在';
                return $m3_result->ToJson();
            }
            try{
                DB::beginTransaction();
                $master = DB::table('master')->insert([
                    'name' => $name,
                    'status'=>1,
                    'created_at' =>date('Y-m-d H:i:s', time()),
                    'password' =>bcrypt('1234567')
                ]);
                if($master){
                    DB::commit();
                    $m3_result->status = 2;
                    $m3_result->message = '管理员添加成功';
                    return $m3_result->ToJson();
                }
            }catch (Exception $e){
                DB::rollback();
            }

        }
        return view('admin.master-add');
    }

    public function update_password(Request $request)
    {
        $id = $request->input('id');
        $master = Master::where(['id' => $id])->first();
        return json_encode($master);
    }

    public function store_password(Request $request)
    {
        $id = $request->input('id');
        //旧密码
        $password = $request->input('password');
        //新密码
        $pass = $request->input('pass');
        //确认新密码
        $repass = $request->input('repass');
        $m3_result = new M3Result();
        if($password == "" || $pass == "" || $repass == ""){
            $m3_result->status = 1;
            $m3_result->message = '密码不能为空';
            return  $m3_result->ToJson();
        }else if($pass != $repass){
            $m3_result->status = 2;
            $m3_result->message = '新密码两次输入的不同相同';
            return  $m3_result->ToJson();
        }
        $master = Master::where(['id' => $id])->first();
        if($master == ""){
            $m3_result->status = 4;
            $m3_result->message = '服务器错误';
            return  $m3_result->ToJson();
        }else if(Hash::check($master->password,$pass)) {
            $m3_result->status = 5;
            $m3_result->message = '新密码不能与旧密码相同';
            return $m3_result->ToJson();
        }
        if(!Hash::check($password,$master->password)){
            $m3_result->status = 3;
            $m3_result->message = '原密码输入错误';
            return  $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('master')->where(['id' => $id])
                ->update([
                'updated_at' =>date('Y-m-d H:i:s', time()),
                'password' =>bcrypt($pass)
            ]);
            if($master){
                $m3_result->status = 0;
                $m3_result->message = '密码修改成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function member_stop(Request $request)
    {
        $id = $request->input('id');
        $master = Master::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($master == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('master')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'status' =>2,
                ]);
            if($master) {
                $m3_result->status = 0;
                $m3_result->message = '该用户状态修改成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function member_del(Request $request)
    {
        $id = $request->input('id');
        $master = Master::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($master == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('master')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'status' =>3,
                ]);
            if(!$master) {
                $m3_result->status = 2;
                $m3_result->message = '删除失败';
                return $m3_result->ToJson();
            }else{
                $m3_result->status = 0;
                $m3_result->message = '删除成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }

    }

    public function master_del(Request $request){
        $masters = DB::table('master')->select('id','name','status','created_at','updated_at')->where(['status' => 3])->get();
        $master = DB::table('master')->where(['status' => 3])->count(DB::raw('DISTINCT id'));
        return view('admin.master_del',compact('masters','master'));
    }

    public function master_recover(Request $request)
    {
        $id = $request->input('id');
        $master = Master::where(['id' => $id])->first();
        $m3_result = new M3Result();
        if($master == "") {
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $master = DB::table('master')->where(['id' => $id])
                ->update([
                    'updated_at' =>date('Y-m-d H:i:s', time()),
                    'status' =>1,
                ]);
            if(!$master) {
                $m3_result->status = 2;
                $m3_result->message = '恢复失败';
                return $m3_result->ToJson();
            }else{
                $m3_result->status = 0;
                $m3_result->message = '恢复成功';
                DB::commit();
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }
    public function pwd(){
	    echo 1234556;
    }
}
