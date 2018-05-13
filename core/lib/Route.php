<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/20
 * Time: 15:04
 */

namespace core\lib;


class Route

{
    public $ctrl = '';
    public $action = '';
    public function __construct(){

        if(!$_SERVER['PATH_INFO']||$_SERVER['PATH_INFO']=='/'){
            $this->ctrl='index';
            $this->action='index';
         }else{
            $path = $_SERVER['PATH_INFO'];

            $patharr = explode('/',trim($path,'/'));
            isset($patharr[0]) ? $this->ctrl=$patharr[0] : $this->ctrl='index';
            unset($patharr[0]);
            isset($patharr[1]) ? $this->action= $patharr[1] : $this->action='index';
            unset($patharr[1]);
            $count  = count($patharr);
            //p($count);
            $i = 2;
            while ($i<=$count){
                if(isset($patharr[$i+1])){
                    $_GET[$patharr[$i]] = $patharr[$i+1];
                }
                $i+=2;
            }
          //  p($_GET);
        }
        $GLOBALS['ctrl'] = $this->ctrl;
        $GLOBALS['action'] = $this->action;

    }

}