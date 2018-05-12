<?php
/**
 * Created by PhpStorm.
 * User: xiaoliang.chen
 * Date: 2018/5/2
 * Time: 18:24
 */

namespace api\control;
use core\logic\User;

class BaseControl
{
    public $user ;
    public function __construct()
    {
        global $ym_title;
        global $ym_logo;
        $this->user = new User();
        $userName = $this->user->get_username();
    }
}