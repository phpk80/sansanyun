<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/11
 * Time: 19:57
 */

namespace app\control;


use core\logic\Coupon;
use core\logic\News;

class QuanControl extends BaseControl
{
    public function index()
    {
        $nav_header = get_nav('top');
        $nav = get_nav(); //导航
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $nav_footer = get_nav('bot'); //底部导航
        $this->assign(compact(
            'nav_footer',
            'nav',
            'cats',
            'help',
            'nav_footer',
            'nav_header'
        ));
        $this->show('quan');
    }

    public function get_coupon()
    {
        global $db, $uid;

        $page = $_GET['page'];
        $ym_uid = intval($uid);
        $is_count = isset($_GET['is_count']) ? intval($_GET['is_count']) : 0;
        $page = intval($_GET['page']) == 0 ? 1 : intval($_GET['page']);
        $num = isset($_GET['num']) ? intval($_GET['num']) : 12;
        $startI = $page * $num - $num;
        $where = "and get_type=1 and limit_start<=" . time() . " and limit_end>" . time();
        if ($is_count == 1) {
            $res['count'] = Coupon::get_coupon_count($where);
            $res['total'] = ceil($res['count'] / $num);
        }
        $res['data'] = Coupon::get_coupon_list($where, 1, $startI, $num, $ym_uid);


        die(json_encode($res));
    }
}