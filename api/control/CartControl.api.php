<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/2
 * Time: 18:28
 */

namespace api\control;


use core\logic\Cart;
use core\logic\Goods;
use core\exception\ApiException;
class CartControl
{
    public function addCart(){
        $ckey = $_REQUEST['ckey'];
        $ckey = $ckey ? intval($ckey) : intval($_COOKIE['ckey']);
        $gid = intval($_REQUEST['gid']);
        $spec = !isset($_REQUEST['spec']) ? '' : trim($_REQUEST['spec']);
        $num = intval($_REQUEST['num']);
        $total =intval($_REQUEST['total']);
        $pid = !isset($_REQUEST['pid']) ? 0 : intval($_REQUEST['pid']);
        $direct = !isset($_REQUEST['direct']) ? 0 : intval($_REQUEST['direct']);
        if($gid == 0) {
            $res['err'] = '获取商品编号失败';
            die(json_encode($res));
        }
        if($total<=0 && $num <= 0) {
            $res['err'] = '购买数量格式错误';
            die(json_encode($res));
        }
        global $db,$uid;
        $ym_uid = $uid;
        if($uid > 0) {
            $cart_goods = Cart::get_cart_goods(0, $ym_uid);
        } else  {
            $cart_goods = Cart::get_cart_goods($ckey);
        }
        if(count($cart_goods) >= 100)
        {
            $res['err'] = '抱歉，您的购物车已经满了，先去结账吧';
            die(json_encode($res));
        }
        $goods = Goods::get_goods_num($gid, $spec);
        $err ='';
        $cart_total = 0;
        foreach ($cart_goods as $k => $v) {
            if($v['gid'] == $gid && $v['spec'] == $spec)
            {
                $cart_total = $cart_total+$v['num'];
                break;
            }
        }

        if( ($cart_total + $num) >$goods['number'])
        {
            $err .= '库存不足，库存只有'.$goods['number'].'件<br>';
        }
        if($goods['goods_status'] != goods_up) //已下架
        {
            $err .= '来晚了，商品已下架<br>';
        }
        if($goods['goods_status'] == goods_up && $goods['uptime']> time()) //未上架
        {
            $err .= '来早了，商品还没有上架<br>';
        }
        if($err !='')
        {
            $res['err'] = $err;
            die(json_encode($res));
        }


            $model = Model('cart');
            $modeli = Model('cart_item');
            $spec = $goods['spec'];
            if($direct == 1) //立即购买
            {
                Cart::update_cart($ckey, $ym_uid);
            }

            $n = '';
            $cnum = $num;
            if ($cart_goods)//已有商品
            {
                foreach ($cart_goods as $k => $v) {
                    if ($v['gid'] == $gid && $v['spec'] == $spec) {
                        $n = $k;
                    }
                    $cnum= $cnum +$v['num'];
                }
                $id = $cart_goods[0]['cid'];

                $model ->where(array("id" =>$id))->update(array('lasttime' => time()) );
            }
            else {

                $id = $model -> insert(array('uid' => $ym_uid, 'lasttime' => time()));

            }

            if ($n !== '') {

                $modeli ->where(array('cid' => $id, 'gid' => $gid, 'spec' => $spec))->update(array('num' => ($total >0 ? $total: $num + $cart_goods[$n]['num']), 'pid' => $pid, 'status' => '1') );
            }
            else {
                $modeli-> insert( array('cid' => $id, 'gid' => $gid, 'num' => $num, 'pid' => $pid, 'spec' => $spec, 'status' => '1'));
            }

            $time = time() + 15552000;
            if ($ym_uid == 0 && $ckey == 0) {
                set_cookie('ckey', $id, $time);	//游客购物车id
                $res['cart_id'] = $id;
            }

            if($total >0)
            {
                $cnum=$cnum -$cart_goods[$n]['num']+$total;
            }
            set_cookie('cnum',  $cnum, $time); //购物车数量
            $res['res'] = $cnum;
            die(json_encode($res));

    }
    public function get_cart(){
            $row= Cart::get_cart();
            $res['data']= $row;
            set_cookie('cnum',  intval($row['num']), time() + 15552000);
            die(json_encode($res));
    }
}