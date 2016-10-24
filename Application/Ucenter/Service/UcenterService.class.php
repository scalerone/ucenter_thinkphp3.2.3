<?php
/**
 * Created by PhpStorm.
 * User: xsu
 * Date: 2016/9/23
 * Time: 14:07
 */

namespace Ucenter\Service;


class UcenterService{
    protected $uc;

    public function __construct() {
        $this->uc = new \Ucenter\Client\UcApi();
    }

    /**
     * @param string $username uid | username
     * @param int    $isuid     0  |    1
     * @return mixed
     */
    public function get_user($username, $isuid=0){
        return $this->uc->uc_get_user($username, $isuid=0);
    }

    /**
     * @param  $username
     * @param  $password
     * @param  $is_sync
     * @return bool|string
     */
    public function login($username, $password, $is_sync = true){
        $result = $this->uc->uc_user_login($username, $password);
        $uid = $result['0'];

        if($uid > 0) {
            return $is_sync ? $this->uc->uc_user_synlogin($uid): $result;
        } elseif($uid == -1) {
            $this->error = '用户不存在,或者被删除';
        } elseif($uid == -2) {
            $this->error = '密码错';
        } else {
            $this->error = '未定义错误';
        }

        return false;
    }

    /**
     * @param  bool $is_sync
     * @return string
     */
    public function logout($is_sync = true){
        cookie('auth', null, array('prefix' => 'ucenter_'));
        return $is_sync ? $this->uc->uc_user_synlogout() : '';
    }

    public function get_cookie_info(){
        $cookie = cookie('auth', '', array('prefix'=>'ucenter_'));
        if (empty($cookie)) return false;
        return explode("\t",$this->uc->_uc_authcode($cookie, 'DECODE'));
    }

}