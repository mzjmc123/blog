<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Tool\M3Result;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;

class ArticleController extends Controller
{
    //
    public function index()
    {
        $articles = DB::table('article')->paginate(10);
        return view('admin.article.index')->with('articles',$articles);
    }

    public function add(Request $request)
    {
        if($request->post()){
            $m3_result = new M3Result();
            $cate_id = $request->input('cate_id','');
            $title = $request->input('title','');
            $url = $request->input('url','');
            $tag = $request->input('tag','');
            $description = $request->input('description','');
            $editor = $request->input('editor','');
            $author = $request->input('author','');

            if($cate_id == "" || $title == "" || $url == "" || $tag == "" || $description  == "" || $editor == "" || $author ==""){
                $m3_result->status = 1;
                $m3_result->message = "你的输入有误请重新输入";
                $m3_result->ToJson();
            }
            try{
                DB::beginTransaction();
                $article = DB::table('article')->insert([
                    'cate_id' => $cate_id,
                    'title' => $title,
                    'url' => $url,
                    'tag' => $tag,
                    'description' => $description,
                    'author' => $author,
                    'content' => $editor,
                    'status'=>1,
                    'view' => 0,
                    'created_at' =>date('Y-m-d H:i:s', time())
                ]);
                if($article){
                    DB::commit();
                    $m3_result->status = 0;
                    $m3_result->message = '文章添加成功';
                    return $m3_result->ToJson();
                }

            }catch (Exception $e){
                DB::rollback();
            }
        }
        $datas = Category::tree();
        return view('admin.article.add',compact('datas'));
    }

    public function article_stop(Request $request)
    {
        $id = $request->input('id');
        $m3_result = new M3Result();
        $article = Article::where(['id' => $id])->first();
        if($article == ""){
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $article = DB::table('article')->where(['id' => $id])->update([
               'status' => 2
            ]);
            if($article){
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '文章以静止显示';
                return $m3_result->ToJson();
            }

        }catch (Exception $e){
            DB::rollback();
        }
    }

    public function article_recover(Request $request)
    {
        $id = $request->input('id');
        $m3_result = new M3Result();
        $article = Article::where(['id' => $id])->first();
        if($article == ""){
            $m3_result->status = 1;
            $m3_result->message = '服务器错误';
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $article = DB::table('article')->where(['id' => $id])->update([
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s', time())
            ]);
            if($article){
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = '文章以静止显示';
                return $m3_result->ToJson();
            }

        }catch (Exception $e){
            DB::rollback();
        }
    }
    public function article_edit(Request $request, $id)
    {
        $article = Article::find($id);
        $datas = Category::tree();
      return view('admin.article.edit',compact('article','datas'));
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $cate_id = $request->input('cate_id');
        $title = $request->input('title');
        $url = $request->input('url');
        $tag = $request->input('tag');
        $description = $request->input('description');
        $editor = $request->input('editor');
        $author = $request->input('author');
        $m3_result = new M3Result();
        if($cate_id == "" || $title == "" || $url == "" || $tag == "" || $description  == "" || $editor == "" || $author ==""){
            $m3_result->status = 1;
            $m3_result->message = "你的输入有误请重新输入";
            return $m3_result->ToJson();
        }
        try{
            DB::beginTransaction();
            $article = DB::table('article')->where(['id' => $id])->update([
                'cate_id' => $cate_id,
                'title' => $title,
                'url' => $url,
                'tag' => $tag,
                'description' => $description,
                'author' => $author,
                'content' => $editor,
                'status'=>1,
                'view' => 0,
                'updated_at' =>date('Y-m-d H:i:s', time())
            ]);
            if($article){
                DB::commit();
                $m3_result->status = 0;
                $m3_result->message = "文章修改成功";
               return $m3_result->ToJson();
            }

        }catch (Exception $e){
            DB::rollback();
        }

    }
}
