<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/3
 * Time: 9:06
 */
/**
 * 捕获抛出api异常
 */
namespace core\exception;


use Throwable;

class ApiException extends \Exception
{
    public function __construct($message = "", $code)
    {
        parent::__construct($message, $code);
    }
}