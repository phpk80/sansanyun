<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/5
 * Time: 9:40
 */

namespace app\control;


use core\logic\News;
use core\logic\Order;
use core\logic\Pay;
use core\logic\User;

class PayControl extends BaseControl
{
    private $err ;
    private $order_info;
    private $payment;

    public function index(){
        global $db,$ym_name,$ym_endtitle;

        $pay_amount = $_REQUEST['pay_amount'];
        $oid = $_REQUEST['oid'];
        $order_type = $_REQUEST['order_type'];
        $pay_id = $_REQUEST['pay_id'];
        $iscancel = $_REQUEST['iscancel'];
        $ym_uid = User::check_login();

        $nav = get_nav(); //导航
        $nav_footer = get_nav('bot');
        $cats = get_catTree(); //分类树
        $help = News::get_help(); //帮助

        $err = '';
        $order = array();
        $pay_again = 0;
        $payable =1;
        $is_qrcode =0;
        $payment = array();

        if($pay_amount && isset($_POST['submit']))//充值/分批支付/取消分批
        {
            $pay_amount = floatval($pay_amount);
            $p_order_sn = $oid ? $oid :'';
            $order_type = intval($order_type);
            $pay_id = $pay_id ? $pay_id: '';
            $iscancel = $iscancel ? intval($iscancel) : 0;

            if($pay_id !='') //删除上次选择未支付的
            {
                $pay_log = Pay::get_pay_log($pay_id);
                if($pay_log && $pay_log['uid'] == $ym_uid)
                {
                    Pay::del_pay_log($pay_id);
                }
            }

            if($iscancel ==1)
            {
                redirect("pay.html?oid=$oid&pay_amount=$pay_amount");
            }
            else {
                if($pay_amount<=0)
                {
                    $this->message("金额错误");
                }
                $pay_id = Order::build_order_sn(1);
                Pay::add_pay_log($pay_id, $pay_amount, $order_type, $ym_uid, $p_order_sn);
                redirect("pay.html?oid=$pay_id&order_type=1&pay_amount=$pay_amount&pay_id=".$pay_id);
            }
        }
        elseif(isset($_POST['codsubmit']))
        {
            $module = Model('order');
            $pay_code = $_REQUEST['pay_code'];
            $module->update( array("pay_code"=>$pay_code), array("order_sn"=>$oid,'uid'=>$ym_uid));
            redirect($_SERVER['HTTP_REFERER']);
            die();
        }

        $res = $this->get_pay_html();
        $order = $this->order_info;

        $payhtml = $this->err!=''? $this->err['message']: $res['pay_html']; //print_r($payhtml);
        $payment = $this->payment;
        $this->assign(compact(
            'nav',
                   'nav_footer',
                    'help',
                    'cats',
                    'payhtml',
                    'ym_name',
                    'ym_endtitle',
                    'payable',
                    'oid',
                    'order',
                    'payment',
                    'ym_uid'
        ));

        $this->show('pay');

    }
    function get_pay_html($act='')
    {

        global $uid;
        $oid = $_GET['oid'];
        $ym_uid = $uid;
        $pay_code = $_GET['pay_code'] ? $_GET['pay_code'] : 'alipay';
        $order_type = isset($_GET['order_type'] )? $_GET['order_type'] :0;

        if($order_type==0)//普通订单
        {

            if(!isset($oid) || trim($oid) =='' || !is_num($oid))
            {
                $this->err['message'] = "订单号错误，请重新支付。";
                return false ;
            }
            $order = Order::get_order_info(0, $oid, $ym_uid);
            $this->order_info = $order;
            if(!$order || intval($order['id'])==0)
            {
                $this->err['message'] = "订单处理异常，请稍后再试或重新支付。";
                $pay_again=1;
                return false;
            }
            if($order['pay_status'] == pay_payed)
            {
                $err = "订单已支付。";
                $err_code='ORDERPAID';
                return $err;
            }
            if($order['pay_code'] == 'cod')
            {
                $this->err['message'] = "该订单您已选择货到付款，请收货后再付款。";
                return $err;
            }
            $pay_id = $pay_id ? trim($pay_id) :'';
            if($pay_id !='')
            {
                $order = Pay::get_pay_log($pay_id);
                if($order['pay_status'] == pay_payed)
                {
                    $this->err['message'] = "订单已支付。";
                    $err_code ='ORDERPAID';
                    return false;
                }
            }

        }
        else{

            $order = Pay::get_pay_log($oid);
            if($act == "check_paystatus")
            {
                if($order['pay_status'] == pay_payed)
                {
                    $this->err['message'] = "订单已支付。";
                    $err_code ='ORDERPAID';
                    return $err;
                }
                return '';
            }

            $order = array('order_sn'=>$oid, 'payble_amount'=>$order['amount']);
        }
        if($act == "check_paystatus")
        {
            return '';
        }
        $payable = 1;

        try
        {
//            if($action =='pay' && isLetter($pay_code)) {
//                $db->update('order', array("pay_code"=>$pay_code), array("order_sn"=>$oid,'uid'=>$ym_uid));
//                redirect("payresult.html?oid=".$oid);
//            }
            global  $sansan_client;
            $pay_amount = $_GET['pay_amount'];
            if($order_type ==0 || $pay_amount > 0)
            {
                $payment =    Pay::get_payment('', 1, $sansan_client);//支付方式
                $this->payment = $payment;
            }

            if($order_type !=0 && $pay_code==''){return false;}
            if(!isset($pay_code) || $pay_code=='')
            {
                $isweixin = is_weixin();

                foreach ($payment as $k => $v) {
                    if($v['pay_code'] == $order['pay_code'] && ($v['client'] == $ym_client || $v['client']==client_all) )//若之前选择的不同设备的，需要重新选择
                    {
                        $pay_code= $order['pay_code'];
                    }
                    if(strpos($v['pay_code'], 'alipay') ===0 && $isweixin) //微信端屏蔽支付宝
                    {
                        unset($payment[$k]);
                        if($v['pay_code'] == $order['pay_code'])
                        {
                            $pay_code='';
                        }
                    }
                }
                if(!isset($pay_code) || $pay_code=='')
                {
                    return;
                }
            }
            else {
                foreach ($payment as $k => $v) {
                    if(strpos($v['pay_code'], 'alipay') ===0 && $isweixin) //微信端屏蔽支付宝
                    {
                        unset($payment[$k]);
                        break;
                    }
                }
                $pay_code= trim($pay_code);
            }

            //$pay_code=isset($pay_code)? trim($pay_code): $order['pay_code'];
            $pay_file = CORE.'/lib/payment/'.$pay_code."/".$pay_code.'.php';

            if(file_exists($pay_file))
            {
                global $ym_name;
                include $pay_file;

                if(class_exists($pay_code))
                {

                    $pay_code = "\\".$pay_code;
                    $tmp=explode("_", $pay_code);
                    $is_qrcode = $tmp[count($tmp)-1]=='native'? 1:0;

                    $order['com_param'] = json_encode(array('is_split'=>($pay_id ==''?0:1), "order_type"=>$order_type));
                    $order['order_type'] = ''.$order_type;
                    $order['pay_code'] = $pay_code;
                    $order['subject'] = $ym_name.'订单';
                    $order['body'] = $ym_name.'订单';
                    $pay = new $pay_code;

                    return $pay->get_payhtml($order);
                }
            }
            else {
                $this->err['message']= "支付方式不存在：".$pay_code;
                return false;
            }
        }
        catch(\exception $e)
        {

        }
    }

}