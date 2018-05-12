<?php

/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/7
 * Time: 7:27
 */
namespace core\lib\driver;
use core\lib\Conf;
use core\lib\Log;

class File implements Log_Adapter
{
    public static $path;
    public function __construct()
    {
        $conf = Conf::get('log','OPTION');
        self::$path = $conf['PATH'];
    }

    public  static function log($message,$file='log'){
        //p('log');

        $file_path = self::$path.'/'.date('YmdH');
       // p($file_path);
        if(!is_dir($file_path)){
            mkdir($file_path,0777,true);
        }
        file_put_contents($file_path.'/'."{$file}.text",$message.PHP_EOL,FILE_APPEND);
    }
}