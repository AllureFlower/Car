<?php
namespace Home\Controller;
use Think\Controller;

class PhoneController extends CommonController {
	
	public function index(){
		if(IS_AJAX) {
			$phone = I('post.phone');
			$idt = I('post.idt');
			
//			$url = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account='.C('Account').'&password='.C('Password').'&mobile='.$phone.'&content=';
			$url = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account='.C('Account').'&password='.C('Password').'&mobile='.$phone.'&content=您的验证码是：'.$idt.'。请不要把验证码泄露给其他人。';
			$str = file_get_contents($url);
			$str = xml_to_array($str);
			$msg = $str['SubmitResult']['msg'];
			if($str['SubmitResult']['code'] == 2) {
				exit(json_encode(['status'=>1,'msg' => $msg]));
			}else {
				exit(json_encode(['status'=>0,'msg' => $msg]));
			}
		}else {
			$this->display();
		}
	}
	
	public function identify() {
		
		if(IS_AJAX) {
			$data = I('post.');
			if(is_numeric($data['nuidt']) && is_numeric($data['nitd'])) {
				if($data['nuidt'] == $data['nitd']) {
					exit(json_encode(['status'=>1, 'phone' => $data['phone']]));
				}else {
					exit(json_encode(['status'=>0]));
				}
			}
		}else {
			$data = I('get.');
			$this->assign('data', $data);
			$this->display();
		}
	}
	
	public function name() {
		
		if(IS_AJAX) {
			$name = I('post.name');
			$phone = I('post.phone');
			if(mb_strlen($name, 'utf-8') > 5 || mb_strlen($name, 'utf-8') < 2) {
				exit(json_encode(['status'=>0,'info' => '名字长度2-5个字符'])); 
				die();
			}
			$User = M("User");
			$User->phone = $phone;
			$User->name = $name;
			
			$openid = session('openid');
			$row = $User->where("openid='".$openid."'")->save();
			if($row) {
				exit(json_encode(['status'=>1,'info' => '绑定成功']));
			}else {
				exit(json_encode(['status'=>0,'info' => '绑定失败']));
			}
		}else {
			$phone = I('get.phone');
			$this->assign('phone', $phone);
			$this->display();
		}
	}
	
	public function update() {
		if(IS_AJAX) {
			$phone = I('post.phone');
			$idt = I('post.idt');
			
//			$url = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account='.C('Account').'&password='.C('Password').'&mobile='.$phone.'&content=';
			$url = 'http://106.ihuyi.com/webservice/sms.php?method=Submit&account='.C('Account').'&password='.C('Password').'&mobile='.$phone.'&content=您的验证码是：'.$idt.'。请不要把验证码泄露给其他人。';
			$str = file_get_contents($url);
			$str = xml_to_array($str);
			$msg = $str['SubmitResult']['msg'];
			if($str['SubmitResult']['code'] == 2) {
				exit(json_encode(['status'=>0,'msg' => $msg]));
			}else {
				exit(json_encode(['status'=>1,'msg' => $msg]));
			}
		}else {
			$openid = session('openid');
			$User = M("User");
			$data = $User->where("openid='".$openid."'")->find();
			$this->display();
		}
	}
	
	public function updateIdent() {
		if(IS_AJAX) {
			$data = I('post.');
			if(is_numeric($data['nuidt']) && is_numeric($data['nitd'])) {
				if($data['nuidt'] == $data['nitd']) {
					$User = M("User");
					$User->phone = $data['phone'];
					$openid = session('openid');
					$row = $User->where("openid='".$openid."'")->save();
					if($row) {
						exit(json_encode(['status'=>1,'info' => '修改成功']));
					}else {
						exit(json_encode(['status'=>0,'info' => '修改失败,请检查你的手机号是否修改']));
					}
				}else {
					exit(json_encode(['status'=>0]));
				}
			}
		}else {
			$data = I('get.');
			$this->assign('data', $data);
			$this->display();
		}
	}
	
}
	