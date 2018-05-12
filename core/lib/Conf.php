<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/25
 * Time: 16:54
 */

namespace core\lib;


use core\Sansanyun;

class Conf
{
    static $confarr = array();
    public static function all($file){
        $path = SANSANYUN.'/config/'.$file.'.php';

        if(isset(self::$confarr[$file])){
            return self::$confarr[$file];
        }
        if(is_file($path)){
            $conf = include $path;
            self::$confarr[$file] = $conf;
            return $conf;
        }else{
            throw new \Exception('配置文件'.$file.'不存在');
        }
    }
    public static function get($file,$name){
        $file = SANSANYUN.'/config/'.$file.'.php';
        if(isset(self::$confarr[$file])){
            if(isset(self::$confarr[$file][$name])){
                return self::$confarr[$file][$name];
            }else{
                throw new \Exception('配置项'.$name.'不存在');
            }

        }
        if(is_file($file)){
            $conf = include $file;
            self::$confarr[$file] = $conf;
            return $conf[$name];
        }else{
            throw new \Exception('配置文件不存在'.$file);
        }
    }
}