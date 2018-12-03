<?php
namespace Admin\Controller;
use Think\Controller;

class CommonController extends Controller {

	public function _initialize(){
		if(!is_login()) {
			$this->error('请先登录',U("Public/login"));
			exit;
		}
		if(ip_refuse()){
            $this->error('403:禁止访问');
            exit;
        }
		$result = M('member')->field('is_login')->where(array('id'=>session('id')))->find();
		if(session('logintime') < $result['is_login'] && $result['is_login'] > 0) {
			$localtion = ip_location();
			$info = $localtion['country'].':'.$localtion['area'] . '网络。';
			$this->error('你的账号在异地'.$info.'所在客户端登陆',U("Public/login"));
			exit;
		}
		$rbac = rbac();
		
		if($rbac['role_id'] > 0) {
			if(in_array($rbac['rbac_bach'], $rbac['currRoleAuth'])	|| in_array($rbac['rbac_master'], $rbac['currRoleAuth'])) {		
				$this -> error('您没有权限',U('Index/index'));
				exit;
			}
		}
	}
}