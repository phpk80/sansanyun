<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/12
 * Time: 8:21
 */

namespace core\lib\driver;
/*
 * 日志记录
 */

interface Log_Adapter
{
    public static function log($message,$file='log');
}