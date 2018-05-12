<?php

/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/19
 * Time: 7:52
 */
namespace core\db;

use core\lib\Conf;

class sqlFactory
{
      static $db;
      public static function factory(){
            if(!self::$db){
                $conf = Conf::all('db');
                $className = "core\\db\\driver\\Db_Adapter_".ucwords($conf['db_type']);
                self::$db =  new $className;
            }
            return self::$db;
      }

}
