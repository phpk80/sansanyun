<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/24
 * Time: 16:03
 */

namespace core\lib;
use core\lib\Conf;

class Template
{
  public $arrayConfig = array();
  static private $instance;
  public $file; //模板文件名(不带路径)
  public $value =  array();
  public $compileFile;
  private  $compilerTool;

  public function __construct()
  {
      $this->arrayConfig=Conf::all('config');
      $this->getPath();
    //  dump($this->arrayConfig);
      //先分配资源目录等变量
      $this->assign(array(
          'ctrl' => $GLOBALS['ctrl'],
          'action' => $GLOBALS['action'],
          'public' => '/public',
          'static' => '/static',
          'images' => '/public/'.Conf::get('config','templateDir').'/images',
          'tpl'=>'/public/'.Conf::get('config','templateDir'),
          'css' => '/public/'.$this->arrayConfig['templateUrl'].'/css',
          'js' => '/public/'.$this->arrayConfig['templateUrl'].'resource/js'
      ));
  }
/*获取模板引擎实例
 *@return object
 */
  public static function getInstance(){
    if(is_null(self::$instance)){
        self::$instance = new Template();
    }
    return self::$instance;
  }
  /*
   * 单步设置引擎
   */
  public function setConfig($key,$value=null){
      if(is_array($key)){
          $this->arrayConfig = $key+$this->arrayConfig;
      }else{
          $this->arrayConfig[$key] = $value;
      }
  }
  public function getConfig($key=null){
      if($key){
          return $this->arrayConfig[$key];
      }else{
          return $this->arrayConfig;
      }
  }

  public function assign($key,$value=null){
      if (is_string($key)){
          $this->value[$key] = $value;
      }
      if (is_array($key)){
         foreach ($key as $k=>$v){
             $this->value[$k]=$v;
         }
      }
  }

  public function show($file){

        $this->file = $file;

        if(!is_file($this->path())){
            throw new \Exception('模板文件不存在');
        }

         $cacheFile = $this->arrayConfig['compiledir'].'/'.md5($file).$this->arrayConfig['suffix'];
         $compileFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.php';

      $this->compilerTool = new Compile($this->path(),$compileFile);
      extract($this->value,EXTR_OVERWRITE);
      if($this->reCache($file) === true){
          if($this->arrayConfig['cache_htm'])
          ob_start();
          if(!is_file($compileFile)||filemtime($compileFile)<filemtime($this->path())||$this->arrayConfig==true){
              $this->compilerTool->compile();
          }


          include $compileFile;
          if($this->arrayConfig['cache_htm']){
              //生成静态文件
              $message = ob_get_contents();
              file_put_contents($cacheFile,$message);
          }

      }else{

          readfile($cacheFile);
      }
      //生成静态文件

  }
  /*
   * 是否需要重新生成静态文件
   * @return bool
   */
  public function reCache($file){
      $flag = true;
      $cacheFile = $this->arrayConfig['compiledir'].'/'.md5($file).'.html';
      $left_time = (time()-@filemtime($cacheFile))>$this->arrayConfig['cache_time'] ? true :false;
      if(($this->arrayConfig['cache_htm']===true&&$left_time)||filesize($cacheFile)<1||$this->arrayConfig['debug']==true||!is_file($cacheFile)){

//          $timeFlag = (time()-@filemtime($cacheFilde))>$this->arrayConfig['cache_time'] ? true : false;
//          if(is_file($cacheFile)&&!$timeFlag&&filesize($cacheFile)>1){  ///文件未过期
//              $flag =  false;
//          }else{
//              $flag = true;
//          }
          $flag = true;
      }else{
          $flag = false;
      }
     // dump($flag);
      return $flag;
  }
  public function html(){

  }
  /*
   * 获取模板文件路径
   * @return string
   */
  public function path(){

        return $this->arrayConfig['templateDir'].'/'.$this->file.$this->arrayConfig['suffix'];
  }
  /*
   * 路径处理为绝对路径
   */
  public function getPath(){
      $this->arrayConfig['templateUrl'] = $this->arrayConfig['templateDir'];
      $this->arrayConfig['templateDir'] = APP_PATH.'/views/'.$this->arrayConfig['templateDir'];
      $this->arrayConfig['compiledir'] = SANSANYUN.'/'.$this->arrayConfig['compiledir'];
  }
  /*
   * 清除缓存
   */
  public function clean($path=null){
      if($path){
          $path = $this->arrayConfig['compiledir'].'/'.md5($path).'.html';
      }else{
          $path = $this->arrayConfig['compiledir'].'/'.'*[(.html)|(.php)]';

          $path = glob($path);
      }


      foreach ((array) $path as $k=>$v){
          unlink($v);
      }
  }
}