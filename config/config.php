<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/25
 * Time: 21:52
 */
if(!defined('in_sansan'))  {
    exit('access denied');
}
return array(
    'suffix' => '.html' ,//模板文件后缀
    'templateDir' => 'default',//模板所在文件夹
    'compiledir'  => 'cache',//编译后存放的目录
    'cache_htm'  => true ,//是否生成静态的html文件
    'suffix_cache' => '.html' ,//编译后文件后缀
    'cache_time'   => '7200', //更新时间,单位秒
    'cache'=>'redis',
    'debug' => true,
);
