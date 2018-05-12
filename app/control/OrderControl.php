<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/4
 * Time: 14:53
 */

namespace app\control;


use core\logic\Cart;
use core\logic\News;
use core\logic\Order;
use core\logic\Pay;
use core\logic\User;

class OrderControl extends BaseControl
{
    public function index(){
        global $uid,$sansan_client,$ym_express_type,$ym_endtitle,$ym_is_bal,$ym_is_invoice;


        if($uid==0) //需要登录
        {
            $uid =User::check_login(1);
        }



        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $cart = Cart::get_cart(1);
        $user = User::get_user();

        $payment = Pay::get_payment('', 1, $sansan_client); //支付方式

        $isweixin = is_weixin();
        foreach ($payment as $k => $v) {
            if(strpos($v['pay_code'], 'alipay') ===0 && $isweixin) //微信端屏蔽支付宝
            {
                unset($payment[$k]);
            }
        }

        if($uid!=0)
        {
            $consignee = Order::get_consignee(0, $uid); //收货地址
            $express_fee = format_price(Order::get_express_fee($uid, $cart));//运费
        }
        else {
            $consignee = 0;
            $express_fee = 0;
        }
        $total = format_price($cart['amount'] + $express_fee);//总金额

        $ym_express = get_cache("express"); //配送方式
        if($ym_express_type==1 && count($ym_express) == 0)
        {

            $express_common = get_cache("express_common");
        }

        $this->assign(compact(
            'express_common',
                    'nav',
                    'nav_footer',
                    'ym_endtitle',
                    'cats',
                    'user',
                    'consignee',
                    'total',
                    'express_fee',
                    'help',
                    'cart',
                    'ym_is_bal',
                    'uid',
                    'payment',
                    'ym_express',
                    'ym_is_invoice'
        ));
        $this->show('order');
    }
    public function myorder(){
        global $uid;

        $res = array('err' => '', 'res' => '', 'data' => array());
        $is_ajax=isset($is_ajax) ? intval($is_ajax): 0;

        $keyword = isset($keyword) ? trim(addslashes($keyword)) : '';

        if($uid ==0)
        {
            $uid = User::check_login($is_ajax);
        }
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $t=$_REQUEST['t'];
        $trade_start_date = $_REQUEST['trade_start_date'];
        $trade_end_date   = $_REQUEST['trade_end_date'];
        $page = $_GET['page'];
        $where = "";
        if(isset($status) && $status != -1)
        {
            $where .=" and o.status=". intval($status);
        }
        if(intval($t)==1)//待付款
        {
            $where .=" and pay_code<>'cod' and o.pay_status=0 and o.status<>".order_cancel." and o.status<>".order_del;
        }
        if(intval($t)==2)//待收货
        {
            $where .=" and o.status=". order_receiving;
        }
        if(intval($t)==3)//待评价
        {
            $where .=" and is_comment=0 and o.status=".order_finish;
        }
        if(intval($t)==4)//待发货
        {
            $where .=" and o.status=".order_deliver;
        }
        if($trade_start_date != '')
        {
            $where .=" and o.add_time>=". strtotime($trade_start_date);
        }
        if($trade_end_date != '')
        {
            $where .=" and o.add_time<=". strtotime($trade_end_date);
        }
        if($keyword != '')
        {
            $where .=" and (o.order_sn like '%".$keyword. "%' or og.name like '%".$keyword. "%')";
            $page =0;
        }

        $page=intval($page)==0 ? 1 : intval($page);
        $pagenum=isset($num)? intval($num) : 10;
        $start = $page * $pagenum - $pagenum;
        $count = Order::get_order_count(0,'',$uid, $where);
        if ($count>0 && $is_ajax==0)
        {
            $pages = getPages($count, $page, $pagenum);
        }
        $order = Order::get_order_list(0,'',$uid, $where, $start, $pagenum);

        $trade_start_date ='';
        $trade_end_date ='';
        $this->assign(compact(
            'nav',
                    'nav_footer',
                    'help',
                    'order',
                    'trade_end_date',
                    'trade_start_date',
                    'pages',
                    'count' ,
                     'cats'
        ));
        $this->show('myorder');
    }
}