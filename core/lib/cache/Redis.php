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
        $this->redis = new \Redis();
        $this->redis->connect($config['host'],$config['port']);
        echo $this->redis->ping();
    }
    public function set($key,$value)
    {
        if(is_array($value)){
            $this->redis->hMset($key,$value);
        }
    }
}