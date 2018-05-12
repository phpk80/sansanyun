<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/4/16
 * Time: 15:58
 */

namespace app\control;


use core\logic\Coupon;
use core\logic\Goods;
use core\logic\News;
use core\logic\Order;

class GoodsControl extends BaseControl
{
    //商品列表页
    public function glist(){
        global $ym_title,$db,$ym_cats_kv;

        $cid = isset($_GET['cid']) ? $ym_cats_kv[$_GET['cid']]['id'] : 0;//分类

        $bid = isset($_GET['bid']) ? $_GET['bid'] : 0;
        $this->assign('bid',$bid);
        $qid = isset($_GET['qid']) ? $_GET['qid'] : 0;
        $this->assign('qid',$qid);
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot'); //底部导航
        $cats = get_catTree(); //分类树
        $this->assign('cats',$cats);
        $this->assign('nav_footer',$nav_footer);
        $help = News::get_help(); //帮助
        $this->assign('help',$help);
        $crumbs_nav = get_crumbs_nav('goods', $cid); //面包导航
        //品牌
        $brand = Goods::get_cat_brand($cid);
        if($brand)
        {
            foreach ($brand as $k => $v) {
                $brand[$k]['url'] = build_url('bid', $v['id']);
            }
            array_unshift($brand, array('name'=> '全部', 'url' => build_url('bid', '')));
        }
        $catinfo = Goods::get_catInfo($cid); //分类信息
        $catinfo = $catinfo[0];

        if(count($catinfo)==0)
        {
            $ym_title= '所有分类 - '.$ym_title;
        }
        else {
            $ym_title = $catinfo['name'].' - '.$ym_title;
            $ym_keywords = $catinfo['keywords'];
            $ym_description = $catinfo['description'];
        }
        //价格
        $pr= $_GET['pr'] == 0 ? '全部': trim($_GET['pr']);

        $price = explode(',', $catinfo['grade']);
        $price_grade = array();
        $price_grade[0]['name'] = '全部';
        $price_grade[0]['url'] = build_url('pr', '');
        foreach ($price as $k => $v) {
            $price_grade[$k+1]['name'] = $v;
            $price_grade[$k+1]['url'] = build_url('pr', $v);
        }
        //属性
        $child_ids = Goods::get_childIDs($cid);

        $childs= Goods::get_catInfo(explode(',', $cid));
        $type_ids = array();
        foreach ($childs as $k => $v) {
            $type_ids[$k] = $v['type_id'];
        }
        $attr = Goods::get_attrs($type_ids);

        $at_arr= explode('@', $_GET['at']);

        $at_param = array(); //选择的属性
        foreach ($at_arr as $k => $v) {
            $at_tmp =explode('_', $v);
            if(count($at_tmp)==2)
            {
                $at_param[$at_tmp[0]]['val'] = $at_tmp[1];
            }
        }

        if($attr)
        {
            foreach ($attr as $k => $v) { //&at=72_红色@71_1000克
                $value = explode(',', $v['value']);
                $attr_value = array();
                $attr_value[0]['name'] = '全部';
                $attr_value[0]['url'] = build_url('at', $v['id'].'_');
                $attr_value[0]['cur'] = (count($at_param)==0 || $at_param[$v['id']]['val']=='' )? '1' : '0';
                foreach ($value as $key => $val) {
                    $attr_value[$key+1]['name']= $val;
                    $attr_value[$key+1]['url']= build_url( 'at', $v['id'].'_'.$val);
                    $attr_value[$key+1]['cur']= $at_param[$v['id']]['val']==$val ? '1' : '0';
                }
                $attr[$k]['value'] = $attr_value;
                if($at_param[$v['id']]['val']!='')
                {
                    $at_param[$v['id']]['name']=$v['name'];
                    $at_param[$v['id']]['type']=$v['type'];
                    $at_param[$v['id']]['url']=$attr_value[0]['url'];
                }
            }
        }
        else {
            unset($at_param);
        }

        //子分类
        $cat_child_tmp = Goods::get_children($cid);  //子分类
        $cat_child = array();
        $url = parse_url($_SERVER['REQUEST_URI']);
        //parse_str($url['query'], $url_param); print_r($url['query']);
        //$url = http_build_query($url_param);
        foreach ($cat_child_tmp as $k => $v) {
            $v['url'] = $v['urlname'].(!isset($url['query'])?'':'?' .$url['query']);
            $cat_child[] = $v;
        }

        $sort_list = array('a1'=>'addtime desc','s1'=>'salenum desc', 'p1'=>'price asc', 'p2'=>'price desc');
        $sort = (isset($sort) && trim($sort)!='') ? trim($sort) : 'a1';
        $order = $sort_list[trim($sort)];

        $cur[$sort]='class="red"' ;
        $sort_add_time = build_url('sort', 'a1');
        $sort_sale = build_url('sort', 's1');
        $sort_price = build_url('sort', trim($sort)=='p2' ? 'p1': 'p2');

        $page=intval($_GET['page'])==0 ? 1 : intval($_GET['page']);

        $pagenum= intval($catinfo['num'])==0 ? 20 : $catinfo['num'];

        $startI = $page * $pagenum - $pagenum;

        //过滤条件
        $condition='';
        if(isset($_GET['word']) && $_GET['word']!='') //分类
        {

            $this->assign('word',$_GET['word']);
            $wordlist=str_replace("+"," ",$_GET['word']);
            $wordlist=str_replace("　"," ",$wordlist);
            $wordlistx=explode(" ",$wordlist);
            $condition .=" and ( ";
            for ($k=0;$k<count($wordlistx);$k++){
                if ($k>0){
                    $keyworksqland=" and ";
                }
                $condition .=$keyworksqland."  g.name like '%".addslashes($wordlistx[$k])."%'";
            }
            $condition .=" )";
            //$condition .=" and g.name like '%".addslashes($word)."%'";
        }
        //if(intval($son)>0) //分类
        {
            $condition .=" and (g.cat_id in(".$child_ids.")  or g.goods_id ". Goods::get_extend_goods($cid) .")";
        }
        if($bid != 0)
        {
            $condition .=" and g.brand_id=". $bid;
        }

        //价格
        if(isset($_GET['pr']) && trim($_GET['pr'])!='')
        {
            $price_arr= explode('-', $_GET['pr']);
            $price_m = intval($price_arr[0]);
            $price_l = intval($price_arr[1]);
            if($price_l >0 && count($price_arr)==2)
            {
                $condition .=' and price>='. $price_m . ' and price<='.$price_l;
            }
            elseif($price_m !=0)
            {
                $condition .=' and price>='.intval($price_m);
            }
            elseif($price_l!=0)
            {
                $condition .=' and price<='.intval($price_l);
            }
        }

        //属性  格式如71_3000克@63_1200@44_16g
        if(count($at_param)>0)
        {
            $wh_attr = '';
            $wh_spec ='';
            $ids_attr =array();
            $ids_spec =array();
            foreach ($at_param as $k => $v) {
                if($v['type']== 1)
                {
                    $wh_spec .= " and (find_in_set('". $k."', b.`attr_ids`) and  find_in_set('". $v['val']."', b.`values`)) ";
                    $ids_spec[]=$k;
                }
                else {
                    $wh_attr .= " and (find_in_set('". $k."',a.`attr_ids`) and find_in_set('". $v['val']."',a.`values`))";
                    $ids_attr[]=$k;
                }
            }

            if($wh_attr !='' && $wh_spec !='')
            {
                $condition .=" and g.goods_id in(SELECT DISTINCT(goods_id) FROM ( SELECT a.goods_id, concat(a.attr_ids,',',b.attr_ids) attr_ids, concat(a.`values`,',',b.`values`)  val FROM ( select goods_id, GROUP_CONCAT(distinct attr_ids) attr_ids, GROUP_CONCAT( `values`) `values` from "
                    . $db->table('goods_attr') . " where 1 and attr_ids in(". implode(',', $ids_attr).") group by goods_id ) a join ( select goods_id, GROUP_CONCAT( distinct attr_ids) attr_ids, GROUP_CONCAT( `values`) `values` from "
                    . $db->table('goods_spec')
                    . " where 1 and attr_ids in(". implode(',', $ids_spec).") group by goods_id ) b on a.goods_id=b.goods_id " . $wh_spec. $wh_attr. ") t )";

            }
            else {
                if($wh_attr !='')
                {
                    $wh_attr ="SELECT DISTINCT(goods_id) FROM (select goods_id, GROUP_CONCAT( attr_ids) attr_ids, GROUP_CONCAT( `values`) `values` from " . $db->table('goods_attr') . " a where 1 and attr_ids in(". implode(',', $ids_attr).") group by goods_id) a where 1 ". $wh_attr;
                }
                if($wh_spec !='')
                {
                    $wh_spec ="SELECT DISTINCT(goods_id) FROM (select goods_id, GROUP_CONCAT( attr_ids) attr_ids, GROUP_CONCAT( `values`) `values` from " . $db->table('goods_spec') . " b where 1 and attr_ids in(". implode(',', $ids_spec).") group by goods_id) b WHERE 1 ". $wh_spec;
                }
                $condition .=" and g.goods_id in(".$wh_attr .  $wh_spec.  " )";
            }
        }

        //优惠券
        if($qid !=0)
        {

            $cids = Coupon::get_coupon_itemids($qid);
            if($cids !='')
            {
                $condition .=" and ".$cids;
            }

            $coupon = Coupon::get_couponinfo($qid);
        }

        $count = $db->query("select count(*) as count from ".$db->table('goods')." g JOIN " . $db->table('category') . " c ON g.cat_id = c.id where g.status=". goods_up . " and uptime<=".time().$condition,'find');
        $count = $count['count'];

        if ($count>0)
        {

                $pages = getPages($count, $page, $pagenum);

            $goods = Goods::get_goods_list($condition, 'g.*', $order, $startI , $pagenum,1,1,$wordlistx);
        }
        else {
            $goods='';
        }

        $this->assign(array(
            'ym_title'=>$ym_title,
            'ym_keywords' => $ym_keywords,
            'ym_description' => $ym_description,
            'crumbs_nav' => $crumbs_nav,
            'catinfo'   => $catinfo,
            'pr' => $pr,
            'price_grade' => $price_grade,
            'at_param' => $at_param,
            'coupon' => $coupon,
            'cat_child' => $cat_child,
            'brand' => $brand,
            'goods' => $goods,
            'attr' => $attr,
            'sort_add_time' => $sort_add_time,
            'cur' => $cur,
            'sort_sale' => $sort_sale,
            'sort_price' => $sort_price,
            'pages' => $page,
            'nav'=>$nav

         ));

        $this->show('list');

    }
    public function item(){
        global $ym_cats,$uid,$user,$ym_title,$ym_express_type,
               $ym_keywords,$ym_description,$diy_itemrem;
        require CORE.'/common/promotion.php';


        $id = ucode(trim($_GET['id']));

        if($id==0){
            $this->message('商品不存在','/');
            exit();
       }
       $goods = Goods::get_goods($id);
        if(!$goods){
            $this->message('商品不存在','/');
            exit();
        }

        if($goods['status'] == goods_del)
        {
            $this->message('商品已删除','/');
        }
        $imgs = json_decode($goods['imgs'],true);
        foreach ($imgs as $k=>$v){
            $imgs[$k]['img'] = '/'.$v['img'];
            $imgs[$k]['thumb'] = '/'.$v['thumb'];
        }
        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助
        $crumbs_nav = get_crumbs_nav('goods', $goods['cat_id']); //面包导航
        $express_fee_news = News::get_spage(0, 14); //print ($a['url'])
        $this->assign('express_fee_news',$express_fee_news);
        $prev_goods = Goods::get_prev_goods($id);//上一个商品
        $this->assign('prev_goods',$prev_goods);
        $next_goods = Goods::get_next_goods($id);//下一个商品
        $this->assign('next_goods',$next_goods);
        $spec_img = json_decode($goods['specs'],true);

        //规格
        $spec= Goods::get_goods_spec($id);

        //属性
        $attr= Goods::get_attr_val($id);

        if($spec && count($spec)>0 && $spec_img && count($spec_img)>0 && count($spec_img['spec_val'])>0)
        {
            foreach ($spec as $k => $v) {
                if($v['id'] == $spec_img['spec_id'])
                {
                    $spec_val = array();
                    foreach ($v['val'] as $key => $val) {
                        $tmp_val= array();
                        $tmp_val['name'] = $val;
                        foreach ($spec_img['spec_val'] as $n => $s) {
                            if($s['value'] == $val){
                                $tmp_val['img']= ($s['imgs'][0]);
                                unset($s['imgs'][0]);
                                $tmp_val['imgs']= ($s['imgs']);
                                unset($spec_img['spec_val'][$n]);
                                break;
                            }
                        }
                        $spec_val[$key] = $tmp_val;
                    }
                    $spec[$k]['val']= $spec_val;
                    $spec[$k]['is_img']=1;
                    break;
                }
            }
        }
//会员等级价格
//$grade = get_grade();
        $price = $goods['min_price'] ==0 ? $goods['price'] : $goods['min_price'];
        $discount =0;

        if($uid !=0)
        {
            $user_info = $user->get_user(_uid);
            $discount = $user_info['discount'];
            $user_price = $user_info ? format_price($user_info['discount'] * $price): 0; //会员价
        }
        $goods['goods_price'] = format_price(get_discount_price($id, $uid, $price, $discount));//优惠价
//SEO
        $ym_title = $goods['name'].' - '.$ym_title;
        $ym_keywords = trim($goods['keyword']) !='' ? $goods['keyword'] : $ym_keywords;
        $ym_description = trim($goods['description']) !='' ? $goods['description'] : $ym_description;
        //推荐
        $diy_goods = Goods::get_diy_goods($diy_itemrem, $goods['cat_id']);

        $remmend_goods=array();
        if(count($diy_goods)>0)
        {
            $remmend_goods=$diy_goods[0]['goods'];
        }
        //运费
        if($ym_express_type==1)
        {
            $express_fee = Order::get_max_express_fee();
        }
        //各星级评价数
        $uid = intval($uid);
        $comment_starcount=$user->get_comment_starcount($id, $uid);
        $commnet_total =0;
        foreach ($comment_starcount as $k => $v) {
            $commnet_total = $commnet_total+ $v;
        }
        $good_count = intval($comment_starcount['good']);
        $this->assign('good_cont',$good_count);
        $mid_count = intval($comment_starcount['mid']);
        $this->assign('mid_count',$mid_count);
        $bad_count = intval($comment_starcount['bad']);
        $this->assign('bad_count',$bad_count);
        $good_pacent = $commnet_total==0 ? 0 : format_price($good_count/$commnet_total*100,2);
        $this->assign('good_pacent',$good_pacent);
        $mid_pacent = $commnet_total==0 ? 0 : format_price($mid_count/$commnet_total*100,2);
        $this->assign('mid_pacent',$mid_pacent);
        $bad_pacent = $commnet_total==0 ? 0 : format_price($bad_count/$commnet_total*100,2);
        $this->assign('bad_pacent',$bad_pacent);
        $total = $good_pacent+$mid_pacent+$bad_pacent;
        $this->assign('total',$total);
        if($commnet_total !=0 && $total<100)
        {
            $good_pacent = $good_pacent + 100 -$total ;
        }
        //评价
        $start =0;
        $num = 10;
        $comment = $user->get_comment_list($id, '', 0, $start, $num, $uid);

        //记录点击数
        Goods::update_goods($id, array('click'=>($goods['click']+1) ));
        //记录最近浏览
        $history_tmp = isset($_COOKIE['his']) ? $_COOKIE['his'] : '';
        $his = array_filter(explode('@', $history_tmp));
        if(in_array($_GET['id'], $his)==false)
        {
            if(count($his) == 30)//todo 可配置
            {
                unset($his[0]);
            }
            array_push($his,$_GET['id']);

            $history_tmp = implode('@', $his);

            set_cookie('his', $history_tmp, time()+15552000);
        }

        $history = Goods::get_history(10);

        $this->assign(array(
            'goods' => $goods,
            'nav' => $nav,
            '$nav_footer' => $nav_footer,
            'cats' => $cats,
            'help' => $help,
            'crumbs_nav' => $crumbs_nav,
            'spec_img' => $spec_img,
            'spec'=>$spec,
            'attr'=>$attr,
            'price' => $price,
            'discount' => $discount,
            'ym_title' => $ym_title,
            'ym_description' => $ym_description,
            'ym_keywords' => $ym_keywords,
            'remmend_goods'=>$remmend_goods,
            'express_fee' => $express_fee,
            'comment' => $comment,
            'history' => $history,
            'imgs' => $imgs,
            'commnet_total' => $commnet_total

        ));

        $this->show('item');
    }
}