<?php
namespace Home\Controller;
use Think\Controller;

class CarController extends CommonController {
    //查询路程
	public function index() {
		if(IS_AJAX) {
			$where = I('post.');
			$where['time'] = $where['time'];
			$where['time'] = strtotime($where['time']);
			$where['time'] = array(array('gt',$where['time']),array('lt',$where['time'] + 86400)) ;
			$where['status'] = 1;
			$release = M('release');
			$data = $release->where($where)->order('time desc')->select(); 
			if(empty($data)) {  
				exit(json_encode(['status'=>0,'info' => '该天暂无过路车'])); 
			}else {    
	 			exit(json_encode(['status'=>1,'info' => $data]));  
			}  
  		}  
	}
	
	public function show() {
		if(IS_GET) {
			$where = I('get.');
			$where['time'] = strtotime($where['time']);
			$time = $where['time'];
			$where['time'] = array(array('gt',$where['time']),array('lt',$where['time'] + 86400)) ; 
			$release = M('release');
			$provinces = M('provinces');
			$where['status'] = 1;
			$data = $release->where($where)->order('time desc')->select(); 
			foreach($data as $key => $value) {
				$data[$key]['formname'] = $provinces->find($value['from']);
				$data[$key]['toname'] = $provinces->find($value['to']);
				$data[$key]['user'] = $release
								->join('car_user on car_user.openid = car_release.openid')
								->where("car_release.openid = '".$value['openid']."'")->find();
			}  	
			$fromlo = $provinces->find($where['from']);
			$tolo = $provinces->find($where['to']);
			$this->assign('time', date('Y年m月d日',$time));
			$this->assign('fromlo', $fromlo);
			$this->assign('tolo', $tolo);
			$this->assign('tolo', $tolo);
			$this->assign('data', $data);
			$this->assign('from', $where['from']);
			$this->assign('to', $where['to']);
			$this->display();
		}
	}


	public function showitem() {
		if(IS_AJAX) {
			$data = I('post.');
			if(empty($data['number'])) {
				exit(json_encode(['status'=>0,'info' => '预定人数不能为空']));
				die();
			}
			if($data['number'] > $data['count']) {
				exit(json_encode(['status'=>0,'info' => '预定人数过多']));
				die();
			}
			$data['passengers'] = session('openid');
			$data['time'] = time();
			$data['tuiding'] = 0;
			$trad = M("trad"); // 实例化User对象
			
			$release = M('release');
			$rdata['id'] = $data['owner'];
			$rdata = $release->find($rdata['id']);
			
			$rdata['number'] = $rdata['number'] - $data['number'];
			
			if($trad->data($data)->add() && $release->save($rdata)) {
				exit(json_encode(['status'=>1,'info' => '预定成功，待审核']));
				die();
			}
			
		}else {
			$id = I('get.id');
			$release = M('release');
			$provinces = M('provinces');
			$user = M('user');	
			//乘客
			$userdata  = $user->where(['openid' => session('openid')])->find();
			//车主
			$data = $release->where(['id' => $id])->find();
			$data['localfrom'] = $provinces->where(['id' => $data['from']])->find();
			$data['localto'] = $provinces->where(['id' => $data['to']])->find();
			$this->assign('id', $id);
			$this->assign('userdata', $userdata);
			$this->assign('data', $data);
			$this->display();
		}
	}

	public function info() {
 
		$trad = M('trad');
		
		$where['timeowner'] = array('gt', time());
		$where['passengers'] = session('openid');
		$data = $trad->where($where)->order('id desc')->select();
		
		$count['timeowner'] = array('gt', time());
		$count['owner_openid'] = session('openid');
		$count['status'] = 0; 
		
		$count =  $trad->where($count)->count();
		
		
	
		$provinces = M('provinces');
		$release = M('release');
		
		foreach($data as $key => $value) {
			$data[$key]['owner_p'] = $release->where(['id' => $value['owner']])->find();
			$data[$key]['localfrom'] = $provinces->where(['id' => $data[$key]['owner_p']['from']])->find();
			$data[$key]['localto'] = $provinces->where(['id' => $data[$key]['owner_p']['to']])->find();
		}		
		$this->assign('count', $count);
		$this->assign('data', $data);
		$this->display();
	}
	
	public function message() {
		$trad = M('trad');
		$where['timeowner'] = array('gt', time());
		$where['owner_openid'] = session('openid');
		$where['status'] = 0; 
		$data = $trad->where($where)->order('id desc')->select();
		$count =  $trad->where($where)->count();
		$provinces = M('provinces');
		$release = M('release');
		foreach($data as $key => $value) {
			$data[$key]['owner_p'] = $release->where(['id' => $value['owner']])->find();
			$data[$key]['localfrom'] = $provinces->where(['id' => $data[$key]['owner_p']['from']])->find();
			$data[$key]['localto'] = $provinces->where(['id' => $data[$key]['owner_p']['to']])->find();
		}		
		$this->assign('count', $count);
		$this->assign('data', $data);
		
		$this->display();
	}
	
	public function messageinfo() {
		$id = I('get.id');
		$data = self::datatrad($id);
		$this->assign('data', $data);
		$this->assign('id', $id);
		$this->display();
	}
	
	public function infoinfo() {
		if(IS_AJAX) {
			$id = I('post.id');
			$trad = M('trad');
			$data['pess_result'] = I('post.pess_result');
			$data['tuiding'] = 3;
			
			if(empty($data['pess_result'])) {
				exit(json_encode(['status'=>0,'info' => '退订理由不能为空']));
				die();
			}
			
			if($trad->where(['id' => $id])->save($data)){
				exit(json_encode(['status'=>1,'info' => '退订成功，待审核']));
				die();
			}
			exit(json_encode(['status'=>0,'info' => '退订失败']));
			
		}else {
			$id = I('get.id');
			$data = self::datatrad($id);
			$this->assign('data', $data);

			$this->assign('id', $id);
			$this->display();
		}
	}
	
	
	
	public function ajcar() {
		$id = I('post.id');
		$trad = M('trad');
//		$release = M('release');
		$data['status'] = 1;
		
		$tdata = $trad->where(['id' => $id])->find();
//		$rdata = $release->where(['id' => $tdata['owner']])->find();
//		$sdata['number'] = $rdata['count'] - $rdata['number'] - $tdata['number'];
		
		if($trad->where(['id' => $id])->save($data)){
			exit(json_encode(['status'=>1,'info' => '同意成功']));
			die();
		}
		exit(json_encode(['status'=>0,'info' => '同意失败']));   
	}
	
	public function refused() {
		
		if(IS_AJAX) {
			
			$id = I('post.id');
			$trad = M('trad');
			$data['status'] = 2;
			$data['result'] = I('post.result');
			if(mb_strlen($data['result'], 'utf-8') < 10) {
				exit(json_encode(['status'=>0,'info' => '理由不可少于10个字符']));
				die();
			}
			$t_data = $trad->where(['id' => $id])->find();
			$release = M('release');
			$r_data = $release->where(['id' => $t_data['owner']])->find();
			
			$r_s_data['number'] = $r_data['number'] + $t_data['number'];
			$r_s_data['id'] = $t_data['owner'];		

			if($trad->where(['id' => $id])->save($data) && $release->where(['id' => $t_data['owner']])->save($r_s_data)) {
				exit(json_encode(['status'=>1,'info' => '拒绝成功']));
				die();
			}
			exit(json_encode(['status'=>1,'info' => '拒绝失败']));
			
		}else {
			$id = I('get.id');
			$this->assign('id', $id);
			$this->display();
		}
	}
	
	
	
	private static function datatrad($id = '') {
		$trad = M('trad');
		
		$where['id'] = $id; 
		$data = $trad->where($where)->order('id desc')->find();
	
		$provinces = M('provinces');
		$release = M('release');
		$user = M('user');
		
		$data['owner'] = $release->where(['id' => $data['owner']])->find();
		$data['owner']['user'] = $user->where(['openid' => $data['owner']['openid']])->find();
		$data['localfrom'] = $provinces->where(['id' => $data['owner']['from']])->find();
		$data['localto'] = $provinces->where(['id' => $data['owner']['to']])->find();
		
		$data['user'] = $user->where(['openid' => $data['passengers']])->find();
		
		return $data;
	}
	
	public function ownerinfo() {
		$trad = M('trad');
		
		$where['timeowner'] = array('gt', time());
		$where['owner_openid'] = session('openid');
		$data = $trad->where($where)->order('id desc')->select();
		
		$provinces = M('provinces');
		$release = M('release');
		
		foreach($data as $key => $value) {
			$data[$key]['owner_p'] = $release->where(['id' => $value['owner']])->find();
			$data[$key]['localfrom'] = $provinces->where(['id' => $data[$key]['owner_p']['from']])->find();
			$data[$key]['localto'] = $provinces->where(['id' => $data[$key]['owner_p']['to']])->find();
		}	
		
		$count = parent::message_count();
		
		$this->assign('count', $count);
		$this->assign('data', $data);
		$this->display();
	}
	
	public function ownerinfoinfo() {
		$id = I('get.id');
		$data = self::datatrad($id);
		$this->assign('data', $data);
	
		$this->assign('id', $id);
		$this->display();
	}
	
	
}