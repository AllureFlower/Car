<?php
namespace Admin\Controller;

use Addon\Verify\Verification;
use Think\Controller;

class PublicController extends Controller {
	
	public function login(){
		if(IS_AJAX) {
			$post = I('post.');
			$model = D('Member');
			if($model->create()){
				if(session('Ver_VAL_SUM') == 0x111) {
					$result = $model->where($post)->find();
					if($result) {
						session(null);
						session('id',$result['id']);
						session('username',$result['username']);
						session('logintime',time());
						session('role_id',$result['role_id']);
						$data = array(
							'login_time'	=>		time(),
							'is_login'		=>		time(),
							'login_ip'		=>		get_client_ip(),
						);
						$model->where(array('id'=>session('id')))->setField($data);
						exit(json_encode(0));
					}else {
						exit(json_encode(array('status'=>1,'info'=>'用户名或密码错误')));
					}
				}else {
					exit(json_encode(array('status'=>1,'info'=>'验证码不正确')));
				}
			}else {
				exit(json_encode(array('status'=>1,'info'=>$model->getError())));
			}
		}else {
			$verification = new  Verification();
			$data = $verification->getOkPng();
			$temp = array_chunk($data['data'],20);
			$data['bg_pic'] = __ROOT__.$data['bg_pic'];
			$data['ico_pic']['url'] = __ROOT__.$data['ico_pic']['url'];
			$this->assign('left_pic',$temp[0]);
			$this->assign('right_pic',$temp[1]);
			$this->assign('pg_bg',$data['bg_pic']);
			$this->assign('ico_pic',$data['ico_pic']);
			$this->assign('y_point',$data['y_point']);
			session("Ver_VAL_SUM",1);
			$this->display();
		}
    }
    public function validation(){
    	static $v_num=1;
    	$ret =  Verification::checkData(I('post.point'),session('Verification'));
    	$v_num +=  session("Ver_VAL_SUM");
    	if( $v_num > 6 ) {
    		session("Ver_SUM",null);
    		exit(json_encode(array('state'=>4603,'data'=>'验证码失效')));
    	} else {
    		session("Ver_VAL_SUM",$v_num);
    	}
    	if( $ret['state'] == 0 ) {
    		session("Ver_VAL_SUM",0x111);
    		exit(json_encode(array('state'=>0,'data'=>session('Verification'))));
    	} else {
    		session("Ver_VAL_SUM",null);
    		exit(json_encode(array('state'=>603,'data'=>'错误'.$v_num)));
    	}
    }

    public function logout() {
    
		$data['id'] = session('id');
		$data['last_login_time'] = time();
		
		$member = M('member');
		
		$member->save($data);
		
    	session(null); 
    	$this->success('登出成功',U('Public/login'),5);
    } 

    public function changeName() {
    	if(IS_AJAX) {
    		$username = I('post.username', '', 'strip_tags');
    			
    		$strlen = mb_strlen($username,'utf-8');

    		if(is_string($username) && $strlen >= 2 && $strlen <= 5) {
    			$User = M('Member');
	    		$num = $User->where(['id' => session('id')])->save(['username' => $username]);
	    		if($num == 1) {
	    			session('username',$username);
	    			exit(json_encode(['status'=>1,'info' => '修改成功', 'username' => session('username') ]));
	    		}else {
	    			exit(json_encode(['status'=>0,'info'=>'昵称不能重复']));
	    		}
    		}else {
    			exit(json_encode(['status'=>0,'info'=>'昵称为2-5个字符串']));
    		}
    	}
    }
}