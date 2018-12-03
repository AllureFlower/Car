<?php

function hook($name) {
	\Think\Hook::listen($name);
}
/**
 * 检测当前用户是否为管理员
 * @return boolean true-管理员，false-非管理员
 */
function is_administrator($uid = null){
    $uid = is_null($uid) ? is_login() : $uid;
    return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
}
/**
 * 检测用户是否登录
 * @return integer false-未登录，true登录
 */
function is_login(){
    $user = session();
    if (empty($user['id']) || empty($user['username']) || empty($user['logintime'])) {
        return false;
    } else {
        return $user['id'];
    }
}
/**
 * 检查IP是否违法
 */
function ip_refuse() {
	if(in_array(get_client_ip(),C('ADMIN_ALLOW_IP'))) {
		return true;
	}else {
		return false;
	}
}
/*
 * IP解析
 */
function ip_location() {
    $ip = new Org\Net\IpLocation('qqwry.dat');
    $location = $ip->getlocation(get_client_ip());
    $location = array_iconv('GBK','UTF-8',$location);
    return $location;
}
/**
 * 数组字符编码转换
 */
function array_iconv($in_charset,$out_charset,$arr) {
    foreach ($arr as $key => $value) {
        $arr[$key] = iconv($in_charset,$out_charset,$value);
    }
    return $arr;
}