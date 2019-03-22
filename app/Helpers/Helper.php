<?php
namespace App\Helpers;

class Helper
{
    public function postRequest(string $param)
    {
        //$res=null;
            
        $res = $_POST[$param];
        return $res;
    }
}
