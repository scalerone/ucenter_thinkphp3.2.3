<?php

namespace Admin\Controller;

use Think\Controller;

/**
*
*/
class IndexController extends Controller
{
    private $uc;
	protected function _initialize(){
        $this->uc  = D('Ucenter/Ucenter', 'Service');
	}

	public function index(){
        var_dump($this->uc->get_user('swcat'));
	}

	public function syslogin(){
		$params = I('param.');
        $ret = $this->uc->login($params['username'], $params['password']);
        if (false === $ret) {
            var_dump($this->uc->error);
        }else{
            echo '登录成功';
            echo $ret;
        }
	}

	public function syslogout(){
        echo $this->uc->logout();
	}

    public function logout(){
        echo $this->uc->logout(false);
    }

	public function get_cookie_info(){
        var_dump($this->uc->get_cookie_info());
	}
}
