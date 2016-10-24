<?php
/**
 * Created by PhpStorm.
 * User: xsu
 * Date: 2016/9/27
 * Time: 9:34
 */

namespace Ucenter\Service;


class ApiService{
    // todo login_handler
    public function login($get, $post){
        $uid      = intval($get['uid']);
        $username = $get['username'];
        cookie('auth', _uc_authcode($uid . "\t" . $username, 'ENCODE'), array('prefix' => 'ucenter_'));
    }

    // todo logout_handler
    public function logout(){
        cookie('auth', null, array('prefix' => 'ucenter_'));
    }
}