<?php
namespace app\control;
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/2/22
 * Time: 19:26
 */
use core\lib\cache\CacheFactory;
use core\lib\Model;
use core\lib\Template;
use core\logic\Goods;
use core\logic\News;
use core\Sansanyun;

class IndexControl extends BaseControl
{
    public function index(){

        global $indexbanner;//首页轮播图
        global $diy_mobiletuisong;
        global $diy_indexad;
        global $diy_timespike;
        global $diy_indexfloor;
        global $diy_indexfoot;
        global $diy_mobile;
        global $diy_qq;
        global $link;
        global $ym_icp ;//备案号
        global $ym_site_statice;
        $cats = get_catTree();//分类树
        $nav = get_nav();//导航
        $nav_footer = get_nav('bot');//底部导航
        $uid =  $this->user->get_userid();
        //楼层

        foreach ($diy_indexfloor as $k=>$v){

            $diy_indexfloor[$k]['url'] = 'goods/glist/cid'.$v['url'];
            foreach ($diy_indexfloor[$k]['goods'] as $k2=>$item){
                $diy_indexfloor[$k]['goods'][$k2]['url'] = 'goods/item/id/'.str_replace('-g','',$item['url']);
            }
        }

        if($uid!=0){
            $user = $this->user->get_user($uid);

            $this->assign('user',$user);
        }

        //限时秒杀
        $time = time();
        if(is_array($diy_timespike)){
            foreach($diy_timespike as $k=>$v){
                if(!$v['goods'])
                {
                    continue;
                }
                if($v['end_time']>$time){
                    if($v['start_time']<$time){
                        $spike=$v;
                        if($v['type']== promotion_type_fixed){
                            foreach($spike['goods'] as $w=>$y){
                                $spike['goods'][$w]['price']= format_price($spike['val']);
                            }
                        }else if($v['type']==promotion_type_cut){
                            foreach($spike['goods'] as $w=>$y){
                                $spike['goods'][$w]['price']= format_price($spike['goods'][$w]['price']-$spike['val']);
                            }
                        }else if($v['type']==promotion_type_off){
                            foreach($spike['goods'] as $w=>$y){
                                $spike['goods'][$w]['price']= format_price($spike['goods'][$w]['price']*$spike['val']);
                            }
                        }
                    }else{
                        $nextspike=$v;
                        if($v['type']== promotion_type_fixed){
                            foreach($nextspike['goods'] as $w=>$y){
                                $nextspike['goods'][$w]['price']= format_price($nextspike['val']);
                            }
                        }else if($v['type']==promotion_type_cut){
                            foreach($nextspike['goods'] as $w=>$y){
                                $nextspike['goods'][$w]['price']=format_price($nextspike['goods'][$w]['price']-$nextspike['val']);
                            }
                        }else if($v['type']==promotion_type_off){
                            if($spike['goods'])
                            {
                                foreach($spike['goods'] as $w=>$y){
                                    $nextspike['goods'][$w]['price']=format_price($nextspike['goods'][$w]['price']*$nextspike['val']);
                                }
                            }
                        }
                    }
                }
            }
        }
        //根据历史浏览记录获取推荐商品
        $remmend_goods = $this->get_recommend_goods();
        $news = new News();
        $help = $news->get_help(); //帮助

        $nowtime = $spike[end_time]-$time;
        $nexttime = $nextspike[start_time]-$time;

        $this->assign(array(
            'nav_footer' => $nav_footer,
            'diy_mobile' => $diy_mobile,
            'diy_qq' => $diy_qq,
            'nav' => $nav,
            'cats' => $cats,
             'ym_icp' => $ym_icp,
            'ym_site_statice' =>  $ym_site_statice,
            'remmend_goods' => $remmend_goods,
            'nowtime' => $nowtime,
            'diy_indexfloor' => $diy_indexfloor,
            'nexttime' => $nexttime,
            'indexbanner' => $indexbanner,
            'diy_mobiletuisong' => $diy_mobiletuisong,
            'diy_indexad' => $diy_indexad,
            'spike' => $spike,
            'help' => $help,
            'link' => $link,
            'diy_timespike' => $diy_timespike,
            'diy_indexfoot' => $diy_indexfoot
        ));
      dump($spike);
      exit();
        $this->show('index');
    }




    public function test(){
        $this->assign('data','这是头部的头部header');
        $this->assign('header','fsdfsdf');
        $this->assign('footer',' footer');

        $this->show('index/index');
        //$tpl->clean('index');
    }
    //根据历史浏览记录获取推荐商品
    public function get_recommend_goods(){
        global $diy_mobileindexyoulike;
        global $db;
        global $ym_youlike_num;
        global $diy_itemrem;
        //获取历史浏览记录
        $goods = new Goods();
        $history = $goods->get_history(100);
        foreach($history as $k=>$y){
            $cat_ids[$k] = $y['cat_id']; //获取同级别的属性id
        }
        $diy_goodslike = array();
        if(count($cat_ids)>0){
            $cat_ids = array_unique($cat_ids);
            foreach($cat_ids as $k=>$y){
                $diy_goods = $goods->get_diy_goods($diy_mobileindexyoulike, $y);
                if($diy_goods)
                {
                    $diy_goodslike = array_merge($diy_goodslike,$diy_goods);
                }
            }
        }
        $remmend_goods=array();
        $goodsidnum = '';
        $i=0;
        if(count($diy_goodslike)>0)
        {
            foreach($diy_goodslike as $y){

                foreach($y['goods'] as $w){
                    if($i==$ym_youlike_num){
                        break;
                    }
                    $remmend_goods[] = $w;
                    $goodsidnum = $goodsidnum .$w['goods_id'].',';
                    $i++;
                }
            }
        }
        $goodsnum =  $ym_youlike_num - count($remmend_goods);
        if($goodsnum){
            if($diy_itemrem){
                foreach($diy_itemrem as $u){
                    foreach($u['goods'] as $y){
                        if(!strstr($goodsidnum,$y['goods_id'])){
                            $remmend_goods[] = $y;
                            $goodsnum--;
                        }
                        if($goodsnum==0){
                            break;
                        }
                    }
                    if($goodsnum==0){
                        break;
                    }
                }
            }else{
                $rowss = $db -> query("SELECT * FROM ".$db->table('goods')." where 1  order by `goods_id` desc LIMIT ".$goodsnum,'select');
                foreach($rowss as $s){
                    $remmend_goods[] = $s;
                }
            }
        }
        return $remmend_goods;

    }
}