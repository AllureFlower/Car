<?php
namespace Home\Controller;
use Think\Controller;

class PeopleController extends CommonController {
	
	//个人中心
    public function index(){
		$openid = session('openid');
		$User = M('User');
		$list = $User->where(['openid' => $openid])->find();
		$count = parent::message_count();
		$this->assign('list', $list);
		$this->assign('count', $count);
    		$this->display();
		
//		$this->display('Books/index');
		
    }
	
	//车主发布
	public function caruser() {
		$openid = session('openid');
		$User = M('User');
		$phone = $User->where(['openid' => $openid])->getField('phone');
		if(is_null($phone)) {
			exit(json_encode(['status'=>1]));
		}else {
			exit(json_encode(['status'=>0]));
		}
	} 
	
}