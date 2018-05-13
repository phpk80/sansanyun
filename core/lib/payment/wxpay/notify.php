<?php
if (!defined('in_sansan')) {exit('Access Denied');}

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
logs("bb-notify1 ");
require_once pay_root."wxpay/lib/WxPay.Api.php";
require_once pay_root.'wxpay/lib/WxPay.Notify.php';
require_once './inc/lib/log.php';

//初始化日志
$logHandler= new CLogFileHandler(pay_root."logs/pay-".date('Ymd').'.log');
$log = Log::Init($logHandler, 15);

class NativeNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify!");
$notify = new NativeNotifyCallBack();
$notify->Handle(true);
