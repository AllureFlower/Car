<?php
namespace Home\Controller;
use Think\Controller;

class CommonController extends Controller {
    
	public function _initialize() {
		$User = M("User"); 
		if(!session('openid')) {	
			$code  =I('get.code');
			$info = getinfo($code);	
			$access_token = $info['access_token'];
			$openid = $info['openid'];
			$user = getuser($access_token, $openid);	
			$listopenid = $User->getField('openid', true);
			if(!in_array($openid, $listopenid)) {
				$data = [
					'openid'	=>	$openid,
					'nickname'	=>  $user['nickname'],
					'reputation'=>  2,
					'headimgurl'	=>		$user['headimgurl'],
					'status'		=>		1,
					'time'	=>	time(),
				];
				$User->data($data)->add();
			}
			session('openid', $openid);
		}
		$data = $User->where(['openid' => session('openid')])->find();
		if($data['reputation'] < 0 || $data['status'] == 0) {
			$this->error('对不起，你发布路线时信誉过低，暂不能登录，请联系管理员');
		}
	}
	
	public static function message_count() {
		$trad = M('trad');
		$count['timeowner'] = array('gt', time());
		$count['owner_openid'] = session('openid');
		$count['status'] = 0; 
		$count =  $trad->where($count)->count();
		return $count;
	}
	
}