<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/4
 * Time: 9:28
 */

namespace app\control;


use core\logic\Cart;
use core\logic\Goods;
use core\logic\News;

class CartControl extends BaseControl
{
    public function index(){
        global $ym_endtitle;
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助

        $cart = Cart::get_cart();
        $history = Goods::get_history(10);

        $this->assign(compact('nav','nav_footer','cats','help','cart','history','ym_endtitle'));
        $this->show('cart');

    }
}