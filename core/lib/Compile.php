<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/25
 * Time: 8:56
 */

namespace core\lib;

/*
 * 模板编译工具类
 */
class Compile
{
    private $template;//需要编译的模板
    private $content;//需要替换的文本
    private $compile;//编译后的文件
    private $left = '{';//做定界符
    private $right = '}'; //右定界符
    private $T_P = array(
        '#\{\\$([a-zA-z_\x7f-\xff][a-zA-z0-9_\x7f-\xff)[\[\'\'\]]*)\}#',
        '#\{(\#|\*)(.*?)(\#|\*)\}#',
        '/[\n\r]*\{if\s+(.+?)\}[\n\r\t]*/',
        '#\{(elseif |else if) (.*?)\}#',
        '#\{else\}#i',
        '/[\n\r]*\{\/if\}[\n\r\t]*/',
        '#\{(loop|foreach) (.* ?) as (.* ?)\}#i',
        '#\{include ([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff)]*)\}#i',
        '#\{\/(foreach|loop)}#i'
    );
    private $T_R = array(
        '<?php echo \$\\1; ?>',
        '',
        '<?php if(\\1) { ?>',
        '<?php }else if(\\2){ ?>',
        '<?php }else{ ?>',
        '<?php } ?>',
        '<?php if(is_array(\\2)){foreach(\\2 as \\3){ ?>',
        "<?php include \$this->arrayConfig['compiledir'].'/'.md5('\\1').'.php'; ?>",
        '<?php }} ?>'
    );
    public function __construct($template,$compile)
    {
        $this->template = $template;
        $this->content = file_get_contents($template);
        $this->compile = $compile;
    }

    public function compile() {
        $this->childrenFile($this->content);
        $this->c_var();

        file_put_contents($this->compile,$this->content);
    }

    public function c_var($path=null){
//        $pattern =   "#\{\\$([a-zA-z_\x7f-\xff[a-zA-z0-9_\x7f-\xff]*)\}#";
//        if (strpos($this->content,'{$')!==false){
//            $this->content = preg_replace($pattern,"<?php echo \$this->value['\\1']; ?",$this->content);
//
//        }

        $this->content = preg_replace($this->T_P,$this->T_R,$this->content);
    }
    //处理模板中包含的文件
    public function childrenFile($content){

        $needle = '#\{include (.* ?)\}#';
        $templateDir = Conf::get('config','templateDir');
        $compileDir = Conf::get('config','compiledir');
        if($fileName = preg_match_all($needle,$content,$matches)){

            if (is_array($matches[1])){
                foreach ($matches[1] as $k=>$v){

                    $path = APP_PATH.'/views'.'/'.$templateDir.'/'.$v.'.html';
                    $compile = SANSANYUN.'/'.$compileDir.'/'.md5($v).'.php';
                    $children_content = file_get_contents($path);
                    $this->childrenFile($children_content);
                    $children_content_R = preg_replace($this->T_P,$this->T_R,$children_content);

                    file_put_contents($compile,$children_content_R);
                }

            }

        }

    }
}