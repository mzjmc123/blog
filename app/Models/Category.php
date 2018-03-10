<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    protected $primaryKey = 'id';

    protected $guarded = [];


    public  static function tree(){
        $_categorys = Category::orderby('id','asc')->get();
        return self::action_getTree($_categorys,'name','id','cate_pid');
    }
    public static function action_getTree($data,$field_name,$field_id='id',$field_pid='pid',$pid=0){
        $_arr = array();
        foreach ($data as $k=>$v) {
            if ($v->$field_pid == $pid) {
                $data[$k]["_" . $field_name] = $data[$k]["$field_name"];
                $_arr[] = $data[$k];
                foreach ($data as $m => $n) {
                    if ($n->$field_pid == $v->$field_id) {
                        $data[$m]["_" . $field_name] = '丨一' . $data[$m]["$field_name"];
                        $_arr[] = $data[$m];
                    }
                }
            }
        }
        return $_arr;
    }

}
