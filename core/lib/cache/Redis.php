<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/12
 * Time: 13:52
 */

namespace core\lib\cache;


use core\lib\Conf;

class Redis extends Cache
{
    private $redis;
    public function __construct()
    {
        $this->connect();
    }
    public function connect()
    {
        $config = Conf::all('redis');
        $redis = new \Predis\Client('tcp://127.0.0.1:6379');
        $redis->connect();
        $this->redis=$redis;
    }
    public function set($key,$value)
    {
//        if(is_array($value)){
//            $this->redis->hMset($key,$value);
//        }
        $this->redis->set($key,$value);
    }

    public function get($key)
    {
       return $this->redis->get($key);
    }
}