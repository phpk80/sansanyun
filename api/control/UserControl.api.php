<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/4/20
 * Time: 10:05
 */

namespace api\control;
use core\lib\LogFactory;
use core\lib\Sms;
use core\lib\System;
use core\logic\Distrib;
use core\logic\User;

class UserControl extends BaseControl
{
    public function check_mobile(){
        $mobile = $_GET['mobile'];
        $res['err'] = '';
        $res['res'] = 0;
        if(isset($mobile) && is_mobile($mobile))
        {

            if(User::check_user_field("mobile",$mobile)==true)
            {
                $res['err']= "该手机号码已注册";
                $res['res']= 1;
            }
        }
        die(json_encode($res));
    }
    public function sms_reg(){
        $mobile = isset($_GET['mobile']) ? trim($_GET['mobile']) :0 ;


        $this->sendsms_service($mobile,'reg',true);
    }
    /**发送短信服务
     * @param string $mobile 手机号
     * @param string $tpl_type 短信模板类型
     * @param bool $check_mobile 是否检测手机存在
     * @param bool $add_sess 是否保存到临时表
     * */
    function sendsms_service($mobile,$tpl_type,$check_mobile=false, $add_sess=true)
    {

        global $ym_name;

        if(intval($mobile)==0 || is_mobile($mobile)==false)
        {
            $res['err']= "手机格式不正确";
            die(json_encode($res));
        }
        if($check_mobile==true && User::check_user_field("mobile",$mobile)==true)
        {
            $res['err']= "该手机号码已存在";
            die(json_encode($res));
        }
        include CORE.'/lib/Sms.php';

        $sms_count_minute = \core\lib\get_sms_count($mobile, $tpl_type, 2); //2分钟内发送数量

        $sms_count_hour = \core\lib\get_sms_count($mobile, $tpl_type, 60); //1小时内发送数量
        $sms_count_ip = \core\lib\get_sms_count($mobile, $tpl_type, "day", 1440); //同个ip一天内发送数量

        if($sms_count_minute>0)
        {
            $res['err']= "请两分钟后再发送";
            die(json_encode($res));
        }
        if($sms_count_hour>6)
        {
            $res['err']= "您发的有点频繁，请一个小时后再试";
            die(json_encode($res));
        }
        if($sms_count_ip>=50)
        {
            $res['err']= "您发的太频繁了，请后天再来吧！";
            die(json_encode($res));
        }

        $extend='';
        $code = get_randNum(authCodeLen);
        LogFactory::factory()->log($code);

        $param='{"code":"'.$code.'","product":"'.$ym_name.'"}';

        $sms= new sms;
        $result= $sms->send($mobile, $tpl_type, $param);
        if($result['err'] !='')
        {
            $res['err'] = $result['err'];
            //die(json_encode_yec($res)); //todo
        }
        if($add_sess)
        {
            \core\lib\add_sms_session($mobile, $code, $tpl_type);
        }

        die(json_encode($res));
    }
    public function doreg(){
        global $ym_ditribution_config;
        require CORE.'/lib/Sms.php';
        $tel=!isset($_GET['tel'])?'': make_semiangle(trim($_GET['tel']));
        $username = isset($_GET['username'])? trim($_GET['username']): '';
        $password = isset($_GET['password'])? trim($_GET['password']): '';
        $email = isset($_GET['email'])? trim($_GET['email']): '';
        $birthday = isset($_GET['birthday'])? trim($_GET['birthday']): 0;
        $qq = isset($_GET['qq'])? trim($_GET['qq']): '';
        $sex = isset($sex)? intval($sex): '';
        $realname = isset($_GET['realname'])? trim($_GET['realname']): '';
        $smscode = isset($_GET['smscode'])? trim($_GET['smscode']): '';
        $agree = isset($agree)? intval($agree): 1;
        $authtype  = isset($_GET['authtype'])? intval($_GET['authtype']): 0;
        $res['err'] = '';
        if($username =='')
        {
            $username = User::build_username();
        }
        $name_err = User::check_uname($username);
        if($name_err !='')
        {
            $res['err']= $name_err;
            die(json_encode($res));
        }
        if($tel==0 || is_mobile($tel)==false)
        {
            $res['err']= "手机格式不正确";
            die(json_encode($res));
        }
        if (User::check_user_field('mobile',$tel)) {
            $res['err']= "手机号已注册";
            die(json_encode($res));
        }

        if (User::check_user_field('uname',$username)){
            $res['err']= "用户名已注册";
            die(json_encode($res));
        }

        if($smscode =='' || strlen($smscode)<4)
        {
            $res['err']="请填写短信验证码";
            die(json_encode($res));
        }

        $sms_session = \core\lib\get_sms_session($tel, $smscode, "reg");
        if(!$sms_session) {
            $res['err']="短信验证码不正确";
            die(json_encode($res));
        }

        if(strtotime('+10 minute',$sms_session['sendtime'])<time()){
            $res['err'] = '验证码失效';
            die(json_encode($res));
        }
        if($password=='' || mb_strlen($password,'utf-8')<6 || mb_strlen($password,'utf-8')>20)
        {
            $res['err']= "密码不正确，请使用6-20个字符";
            die(json_encode($res));
        }
        if($password !== trim($_GET['repassword']))
        {
            $res['err']="两次密码输入不一致";
            die(json_encode($res));
        }
        if($agree==0)
        {
            $res['err']="请先阅读并同意注册协议";
            die(json_encode($res));
        }
        $oauth_userinfo = json_decode($_SESSION['oauth_userinfo'], true);
        $img = '';
        if($oauth_userinfo) //下载头像
        {
            $img = User::get_oauthimg($oauth_userinfo['avatar']);
        }
        if($_SESSION['ditrib_id'] && $ym_ditribution_config['distrib_level']>0)	//分销
        {

            $pids = Distrib::get_parent_uid(intval($_SESSION['ditrib_id']));
            $pids['pid3'] = $pids['pid2'];
            $pids['pid2'] = $pids['pid1'];
            $pids['pid1'] = $_SESSION['ditrib_id'];
        }
        else {
            $pids = array('pid1'=>0,'pid2'=>0,'pid3'=>0);
        }
        $uid = User::add_user($username,$tel,$password,$img, $email,$birthday,$qq, $sex,$realname, $pids);
        if($uid==0)
        {
            $res['err']="注册失败，请稍后再试";
            die(json_encode($res));
        }

        \core\lib\update_sms_status($tel, 'reg');//更新短信验证码为已用
        User::set_login_session($uid, $username);
        $res['res']="ok";
        $res['sid']= User::get_sid();
        die(json_encode($res));
}
    public function dologin(){
        require CORE.'/lib/Sms.php';
        $username  = $_POST['username'];
        $authtype = $_POST['authtype'];
        $password = $_POST['password'];
        $username = isset($username) ? trim($username) : '';//用户名/手机号
        $authtype = isset($authtype) ? intval($authtype) : 0;
        $res['err'] = '';
        if($username=='' || mb_strlen($username)<3)
        {
            $res['err']="请输入用户名/手机号";
            die(json_encode($res));
        }
        if($authtype ==1)//验证方式:1 短信/0 短信+密码
        {
            $res['err']= \core\lib\check_smscode($username, $_REQUEST['smscode'], 'login');
            if($res['err'] !="")
            {
                die(json_encode($res));
            }
        }else {
            if(trim($password)=='' || strlen(trim($password))<6)
            {
                $res['err']= "请输入密码";
                die(json_encode($res));
            }
            if(is_mobile($username))
            {
                \core\lib\update_sms_status($username, 'login');//更新短信验证码为已用
                $user = User::get_user_by_mobile($username, '');
            }
            else {
                $user = User::get_user_by_mobile('', $username);
            }
            if(!$user)
            {
                $res['err']= "用户不存在";
                die(json_encode($res));
            }
            if($user['status']==locked)
            {
                $res['err']= "出于安全原因，系统已冻结您的账户，请与客服联系。";
                die(json_encode($res));
            }
            $count = System::get_login_count($user['id'], role_user);
            User::set_login_session($user['id'], $user['uname'], $_REQUEST['autologin']);
            $res['res']="登录成功!";
            die(json_encode($res));
        }
    }

}