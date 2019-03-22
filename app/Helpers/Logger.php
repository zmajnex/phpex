<?php
namespace App\Helpers;

class Logger
{
    public function logger($log_msg)
    {
        $log_folder="app/Logs/";
        $log_filename = "errors.log";
        $date= date('d-M-Y h:m:s');
        $file=__FILE__;
        $level="warning";
        $log="[{$date}] [{$file}] [{$level} $log_msg]";
   
        return file_put_contents($log_folder.$log_filename, $log. "\n", FILE_APPEND);
    }
}
