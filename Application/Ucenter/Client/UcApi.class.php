<?php

namespace Ucenter\Client;

/**
 * UCenter interface wrapper
 * Class UcApi
 * @package Ucenter\Client
 */
class UcApi {

    public function __construct() {
        require_cache(dirname(dirname(__FILE__)) . '/Conf/config.php');
        if (!defined('UC_API')) {
            E('未发现uc配置文件');
        }
        require_cache(dirname(__FILE__) . '/uc_client/client.php'); // 加载uc客户端主脚本
    }

    public function __call($method, $params) {
        $method = parse_name($method, 0); //函数命名风格转换，兼容驼峰法
        if (function_exists($method)) {
            return call_user_func_array($method, $params);
        } else {
            return -1; //api函数不存在
        }
    }
}