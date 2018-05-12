<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/12
 * Time: 12:41
 */

namespace core\lib\cache;


class CacheFactory
{
    static $class;

    static function getInstance($type){
        $args = func_get_args();
        $type = ucwords($type);
        if(!isset($class[$type])){
            $class  = '\\'.'core\lib\\cache\\'.$type;
            self::$class[$type] = new $class;
        }
        return self::$class[$type];
    }
}