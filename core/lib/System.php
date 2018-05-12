<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/4/21
 * Time: 15:38
 */

namespace core\lib;


class System
{
    function get_app_list($type=0, $startI=0, $pagenum=10)
    {
        global $db;
        $where =array();
        if($type !=0)
        {
            $where['type'] = intval($type);
        }
        $row = $db->fetchall('app', '*', $where,'addtime desc',$startI .",". $pagenum);
        foreach ($row as $k => $v) {
            $row[$k]['addtime']= date("Y-m-d H:i", $v['addtime']);
        }
        return $row;
    }

    function get_cron_list($status=-1, $nexttime=0)
    {
        global $db;
        $where ='1';
        if($status != -1)
        {
            $where .=" and status =". intval($status);
        }
        if($nexttime !=0)
        {
            $where .=" and nexttime<=". $nexttime;
        }
        $row = $db->fetchall('cron', '*', $where,'addtime desc');
        foreach ($row as $k => $v) {
            $row[$k]['addtime_format']= date("Y-m-d H:i", $v['addtime']);
            $row[$k]['lasttime_format']= $v['lasttime']==0?'': date("Y-m-d H:i:s", $v['lasttime']);
            $row[$k]['nexttime_format']= $v['nexttime']==0?'': date("Y-m-d H:i:s", $v['nexttime']);
        }
        return $row;
    }

    function update_cron($id, $row = array())
    {
        global $db;
        return $db -> update('cron', $row, array('id'=>intval($id)));

    }

    /*添加登陆日志*/
    static function add_login_log($uid, $role_type, $status)
    {
        $db = Model('login_log');
        return $db->insert(array('uid'=>intval($uid),'role_type'=>intval($role_type),'status'=>intval($status), 'ip'=>getip(),'lasttime'=>time()));
    }

    /*获取指定时间内的登陆失败次数, 默认一小时*/
    static function get_login_count($uid, $role_type, $time = 3600)
    {
         $db = Model('login_log');
         return  $db->where("uid=".intval($uid)." and role_type=".intval($role_type)." and status=".login_fail." and lasttime>".(time()-$time))->total();

    }

    /*获取登陆日志*/
    function get_login_log($uid=0, $log_status=0, $role_type=0, $ip='', $grade_ids='', $sex=0, $min_time=0, $max_time=0, $start=0, $num=null)
    {
        global $db;
        $where = '';
        if($uid !=0)
        {
            $where .=" and uid=".intval($uid);
        }
        if($log_status !=0)
        {
            $where .=" and a.status=".intval($log_status);
        }
        if($role_type !=0)
        {
            $where .=" and role_type=".intval($role_type);
        }
        if($ip !='')
        {
            $where .=" and ip='".$ip."'";
        }
        if($grade_ids !='')
        {
            $where .=" and grade_id in(".trim($grade_ids).") ";
        }
        if($sex !=0)
        {
            $where .=" and sex=".intval($sex);
        }
        if($min_time !=0)
        {
            $where .=" and lasttime>=".trim($min_time);
        }
        if($max_time !=0)
        {
            $where .=" and lasttime<".trim($max_time);
        }
        if($num !=null)
        {
            $where .=" limit ".$start.",".$num;
        }

        return  $db->queryall("select a.*, b.uname,b.status u_status from ".$db->table("login_log")." a join ".$db->table("member")." b on a.uid=b.id where 1=1 ".$where);
    }

    /*更新缓存 快递*/
    function update_cache_express()
    {
        global $db,$php_pre;
        $tmp_express = get_express();
        $tmp_express_common = get_express_common();
        $tmp_express_district = get_express_district(0, 1);
        $tmp_express_picksite = get_express_picksite();

        foreach ($tmp_express_common as $k => $v) {
            if($v['id'] == 1)
            {
                unset($tmp_express_common[$k]);//排除自提点
            }
        }

        write_file(cache_data.'express.php', $php_pre."\$ym_express=".@arrayeval($tmp_express,'').";");
        write_file(cache_data.'express_common.php', $php_pre."\$ym_express_common=".@arrayeval($tmp_express_common,'id').";");
        write_file(cache_data.'express_district.php', $php_pre."\$ym_express_district=".@arrayeval($tmp_express_district,'id').";");
        write_file(cache_data.'express_picksite.php', $php_pre."\$ym_express_picksite=".@arrayeval($tmp_express_picksite,'').";");
    }

    /*更新缓存 物流跟踪*/
    function update_cache_express_track()
    {
        global $db,$php_pre;
        $row=  $db->fetchall('express_track', '*');
        write_file(cache_static.'express_track.php', $php_pre."\$ym_express_track=".@arrayeval($row,'code').";".PHP_EOL);
    }

    /*更新缓存 第三方登录*/
    function update_cache_oauth()
    {
        global $db,$php_pre;
        $row =  $db->fetchall('oauth', '*');
        write_file(cache_static.'oauth.php', $php_pre."\$ym_oauth=".@arrayeval($row,'code').";".PHP_EOL);
    }

    /*更新缓存 短信*/
    function update_cache_sms()
    {
        global $db,$php_pre;
        $row=  $db->fetchall('sms_config', '*');
        $con = '';
        foreach ($row as $k => $v) {
            $v['config'] = json_decode($v['config'], true);
            $con .= "\$ym_".$v['code']."=".@arrayeval($v,'').";".PHP_EOL;
        }
        write_file(cache_static.'sms.php', $php_pre . $con);
    }

    /*更新缓存 某分类下面的品牌*/
    function update_cache_cat_brand()
    {
        global $php_pre;
        $row =  get_brand();
        $cat_brand = array();
        foreach ($row as $k => $v) {
            if(trim($v['cat_ids']) ==''){
                continue;
            }
            $cids = explode(",", $v['cat_ids']);
            foreach ($cids as $key => $val) {
                $cat_brand[$val][]= $v;
            }
        }
        write_file(cache_data.'cat_brand.php', $php_pre."\$ym_cat_brand=".@arrayeval($cat_brand).";".PHP_EOL);
    }

    /*更新缓存 微信配置*/
    function update_cache_weixin()
    {
        global $db,$php_pre;

        $con = '';
        $row = $db->fetch('wx_config','*');
        $con = "\$ym_wxconfig=".@arrayeval($row).";".PHP_EOL;

        $row = $db->fetchall('wx_tpl','*');
        $data = array();
        foreach ($row as $k => $v) {
            $data[$v['code']] = $v;
        }
        $con .= "\$ym_wxnotice=".@arrayeval($data).";".PHP_EOL;
        write_file(cache_data.'weixin.php', $php_pre.$con);
    }

}