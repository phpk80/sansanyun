<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/20
 * Time: 11:02
 */

namespace core;
use core\db\sqlFactory;
use core\lib\Conf;
use core\lib\LogFactory;
class Sansanyun
{
    private static $classMap = array();
    public $assign;
    static function run(){
        //p($_SERVER);
        //$log = LogFactory::factory();
        //$log->log('test');
        //$log->log('test111');
        $route = new \core\lib\Route();
        $ctrl  =  $route->ctrl;
        $action = $route->action;
        $ctrlfile = APP.'/control/'.ucwords($ctrl).'Control.php';
        $ctrlClass = "\\".MODULE.'\\control\\'.ucwords($ctrl).'Control';
        if(strtolower(MODULE)=='api'){
            $ctrlfile = APP.'/control/'.ucwords($ctrl).'Control.api.php';
            //$ctrlClass = "\\".MODULE.'\\control\\'.ucwords($ctrl).'Control';
        }

        if(!file_exists($ctrlfile)){
            throw new \Exception('控制�?.$ctrlfile.'不存�?);
        }


        $control = new $ctrlClass();
        $control->$action();
      //  p('ok');
    }
    //自动加载�?
    static function load($class){

        $class = str_replace('\\','/',$class);

        $file = SANSANYUN.'/'.$class.'.php';

        if(strtolower(MODULE)=='api'&&strpos($class,'Control')!==false){
            $file = SANSANYUN.'/'.$class.'.api.php';
        }
        if (isset(self::$classMap[$class])){
            return true;
        }
        if(!file_exists($file)){
            //这里以后抛出异常
            return false;
        }
        include  $file;
        self::$classMap[$class] = $class;

    }

    public function assign($name,$value){
        $this->assign[$name] = $value;

    }

    public function display($file){
        $prefix = Conf::get('config','view_prefix');
        $path = APP_PATH.'/views/'.$file.'.'.$prefix;
        if(is_file($path)){
            p($this->assign);
            extract($this->assign);
            include $path;
        }else{
            throw new \Exception('文件不存�?'.$file);
        }
    }
}