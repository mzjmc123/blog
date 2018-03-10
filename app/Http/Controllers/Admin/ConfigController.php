<?php

namespace App\Http\Controllers\Admin;

use App\Tool\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;

class ConfigController extends Controller
{
    public function index(Request $request){

        $configs = DB::table('config')->get();
        foreach($configs as $k => $v){
            switch($v->type){
                case 'input':
                    $configs[$k]->html = '<input type="text" name="content[]" class="layui-input" value="'.$v->content.'">';
                    break;
                case 'textarea':
                    $configs[$k]->html='<textarea type="text" name="content[]" class="layui-textarea">'.$v->content.'</textarea>';
                    break;
                case 'radio':
                    $arr = explode(',', $v->value);
                    $str = '';
                    foreach($arr as $n => $m){
                        $arrs = explode('|',$m);
                        $check = $v->content==$arrs[0]?' checked ':'';
                        $str .= '<input type="radio" name="content[]" value="'.$arrs[0].'"'.$check.'>'.$arrs[1].'　';
                    }
                    $configs[$k]->html = $str;
                    break;
            }
        }

        return view('admin.config.index',compact('configs'));
    }

    public function add(Request $request){
        if($request->post()){
            $name = $request->input('name');
            $title = $request->input('title');
            $type = $request->input('type');
            $tips = $request->input('tips');
            $value = $request->input('value');
            $m3_result = new M3Result();
            if($name == "" || $title == ""){
                $m3_result->status = 1;
                $m3_result->message = '填写错误请重新填写';
                return $m3_result->ToJson();
            }
            try{
                DB::beginTransaction();
                $config = DB::table('config')->insert([
                    'title' => $title,
                    'name' => $name,
                    'type' => $type,
                    'value' => $value,
                    'tips' => $tips,
                    'status' => 1,
                    'created_at' => date("Y-m-d:H:i:s",time()),
                ]);
                if($config){
                    $this->putfile();
                    DB::commit();
                    $m3_result->status = 0;
                    $m3_result->message = "网站配置项添加成功";
                    return $m3_result->ToJson();
                }
            }catch (Exception $e){
                DB::rollback();
            }

        }
        return view('admin.config.add');
    }

    public function edit(Request $request,$id)
    {
        $config = DB::table('config')->where(['id' =>$id])->first();

        return view('admin.config.edit',compact('config'));
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $name = $request->input('name');
        $type = $request->input('type');
        $value = $request->input('value');
        $tips = $request->input('tips');
        $m3_result = new M3Result();
        if($title == "" || $name == ""){
            $m3_result->status = 1;
            $m3_result->message = "填写错误请重新填写";
            return $m3_result->ToJson();
        }
        $config = DB::table('config')->where(['id' => $id])->first();
        if(!$config){
            $m3_result->status = 2;
            $m3_result->message = "服务器错误";
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $config = DB::table('config')->where(['id' => $id])->update([
                'title' => $title,
                'name' => $name,
                'type' => $type,
                'value' => $value,
                'tips' => $tips,
                'updated_at' => date("Y-m-d:H:i:s",time()),
            ]);
            if($config){
                $this->putfile();
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '配置项修改成功';
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function config_stop(Request $request)
    {
        $id = $request->input('id');
        $m3_result = new M3Result();
        try{
            DB::beginTransaction();
            $config = DB::table('config')->where(['id' => $id])->update([
                'status' => 2,
                'updated_at' => date("Y-m-d:H:i:s",time())
            ]);
            if($config){
                $this->putfile();
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '状态以停止';
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }
    public function config_recover(Request $request)
    {
        $id = $request->input('id');
        $m3_result = new M3Result();
        try{
            DB::beginTransaction();
            $config = DB::table('config')->where(['id' => $id])->update([
                'status' => 1,
                'updated_at' => date("Y-m-d:H:i:s",time())
            ]);
            if($config){
                $this->putfile();
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '状态已开启';
                return $m3_result->ToJson();
            }
        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function content(Request $request)
    {
        $input = $request->all();
        foreach($input['id'] as $k => $v ){
            DB::table('config')->where(['id' => $v])->update([
                'content' => $input['content'][$k]
            ]);
        }
        $this->putfile();
        return back();
    }

    public function putfile()
    {
        $config = DB::table('config')->pluck('content','name')->all();
        $_path = base_path().'\config\web.php';
        $_str = '<?php return '.var_export($config,true).';';
        file_put_contents($_path,$_str);
    }
}
