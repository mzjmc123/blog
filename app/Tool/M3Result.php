<?php
namespace App\Tool;
class M3Result
{
    public $status;

    public $message;

    public function ToJson(){

        return json_encode($this,JSON_UNESCAPED_UNICODE);
    }
}