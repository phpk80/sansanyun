<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/3/29
 * Time: 6:10
 */

namespace app\control;


use core\lib\Template;
use core\logic\News;
use core\logic\User;
use core\logic\Order;
class BaseControl
{
    protected $template ;
    public $user ;
    public $order;
    public $uid;
    public function  __construct()
    {
        global $ym_title;
        global $ym_logo;
        $this->template = Template::getInstance();
        $this->user = new User();
        $this->order =new Order();
        $userName = $this->user->get_username();
        $cnum = $this->order->get_cart_amount();

        $this->assign('cnum',$cnum);
        $this->assign('ym_logo',$ym_logo);
        $this->assign('ym_title',$ym_title);

        $this->assign('user_name',$userName);

    }

    public function show($path){
        $this->template->show($path);
    }
    public function assign($name,$value=null){

        $this->template->assign($name,$value);
    }
   //提示信息
    public function message($msg,$url='back',$miao='3'){
        if ($url=="back") {
            header("Cache-control: private"); $url="javascript:history.back(1)";
            exit();
        }
        if ($miao=="0") {
            header("Location:$url");
            exit();
        }
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $this->template->assign(array(
            'nav'=> $nav,
            'cat'=> $cats,
            'help'=> $help,
            'nav_footer' => $nav_footer,
            'msg' => $msg,
            'url'=> $url,
            's' => $miao
        ));

        $this->template->show('showmessage');
    }
}