<?php
/**
 * 入口文件，初始化数据
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/23
 * Time: 6:10
 */
Header('Content-type: text/html; charset=UTF-8');
date_default_timezone_set ('Etc/GMT-8');
define('APP_PATH',realpath(APP));
define('DEBUG',true);
define('SANSANYUN',realpath('./'));
define('CACHE_DATA',realpath('./cache/data'));
define('TOKEN','eek53nx4hpb7cjjd');
define('PLUGIN',realpath('./core/lib/plugin'));
define('CORE',SANSANYUN.'/core');
include "./vendor/autoload.php";

if(DEBUG&&strtolower(MODULE)!='api'){
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors','on');
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}else{
    ini_set('display_errors','off');
}


include SANSANYUN.'/core/common/function.php';
if(strtolower(MODULE)=='app'||strtolower(MODULE)=='api'){
    include SANSANYUN.'/config/shop_config.php';
    include SANSANYUN.'/config/const.php';
    include CACHE_DATA.'/diy.php';
}

if(strtolower(MODULE)=='api'){
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors','on');
    set_error_handler('errhandler',E_ALL ^ E_NOTICE);
    //set_exception_handler('handler');
}
include SANSANYUN.'/core/Sansanyun.php';

spl_autoload_register('\core\SANSANYUN::load');

//$route = new \core\lib\Route();
//p($route);
$ym_fullurl = get_fullurl();
$user = new \core\logic\User();

$sansan_client = get_client();
$uid = $user->get_userid();
$db = Model();

\core\SANSANYUN::run();


//dump($user);vxcbcb
