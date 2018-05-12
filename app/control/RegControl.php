<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/4/16
 * Time: 12:52
 */

namespace app\control;


use core\logic\News;
use core\logic\User;

class RegControl extends BaseControl
{
    public function reg(){

        global $ym_name,$ym_fullurl;
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $oauth = User::get_oauth();
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $return_url = isset($_GET['return_url']) ? $_GET['return_url'] :'';
        $this->assign(array(
            'nav' => $nav,
            'nav_footer' => $nav_footer,
            'cats' => $cats,
            'help' => $help,
            'ym_name' => $ym_name,
            'oauth' => $oauth,
            'return_url' => $return_url
        ));
        $this->show('reg');
    }
}