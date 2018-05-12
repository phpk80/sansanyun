<?php
/**
 * Created by PhpStorm.
 * User: asuspc
 * Date: 2018/4/7
 * Time: 21:39
 */

namespace app\control;


use core\logic\User;

class LoginControl extends BaseControl
{
    public function login(){
        global $ym_name;
        $row = get_oauth(); //第三方登录列表
        $nav_footer = get_nav('bot');//底部导航
        $this->assign('nav_footer',$nav_footer);
        $this->assign('row',$row);
        $this->assign('ym_name',$ym_name);
        $this->show('login');
    }
    public function logout(){
        global $db;
        unset($_SESSION['uid']);
        unset($_SESSION['uname']);
        set_cookie('user', '');
        set_cookie('cnum', '');

        session_start();
        User::del_session(session_id());
        session_regenerate_id(true);//生成新会话

        set_cookie('ym_logout', '1');//标记人工退出，防止微信内自动登录

        if(isset($ym_sid))
        {
            /*if(file_exists(session_save_path()."/sess_".$ym_sid))
            {
                session_id($ym_sid);
                session_unset();
                session_write_close();
            }*/
            die();
        }
        else {
            header("Location:/");
        }
    }
}