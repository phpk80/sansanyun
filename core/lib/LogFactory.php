<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/7
 * Time: 7:20
 */

namespace core\lib;
use core\lib\Conf;

class LogFactory
{

    public static function factory(){
        $className  = ucwords(Conf::get('log','DRIVER'));
        $class = '\core\lib\driver\\'.$className;

        return  new $class;

    }

}