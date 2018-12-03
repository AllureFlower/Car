<?php
namespace Home\Controller;
use Think\Controller;

class ReleaseController extends CommonController {
	
	public function index() {
		
		if(IS_GET) {
			$string = I('get.');
			$loc = array_keys($string)[0];
			$locId = array_values($string)[0];
			$City = M('provinces');
			if($loc == 'from') {
				$fromp = $City->where(['id' => $locId])->find();
				session('fromp',$fromp);
			} 
			if($loc == 'to') {
				$top = $City->where(['id' => $locId])->find();
				session('top',$top);
			}		
		}
		$this->display();
	}


	public function sendcarinfo() {
		$data = $this->carsendinfo();
		
		$this->assign('data',$data);
		$this->display();
	}
	
	private function carsendinfo() {
		$release = M('release');
		$provinces = M('provinces');
		$data = $release->where("openid = '".session('openid'). "' and ".'time > '. time())->order('time desc')->select();
		foreach($data as $key => $value) {
			$data[$key]['formname'] = $provinces->find($value['from']);
			$data[$key]['toname'] = $provinces->find($value['to']);
		}
		return $data;
	}
	
	//城市
	public function selcity() {
		$sid = I('get.');
		$string = array_keys($sid)[0];
	
		$City = M('provinces');
		$list = $City->select();
		$list = list_to_tree($list);
		$list = menu($list, $string, 'index');
		
		$this->assign('list', $list);
		$this->display();
	}
	
	
		
	public function savecar() {
		if(IS_AJAX) {
			$data = I('post.');
			if(empty($data['rout']) || empty($data['time']) || empty($data['carmodel']) || empty($data['money']) || empty($data['count'])) {
				exit(json_encode(['status'=>1,'info' => '字段不能为空']));
				die();
			}
			if(!is_numeric($data['money']) || !is_numeric($data['count'])) {
				exit(json_encode(['status'=>1,'info' => 'A费和余座必须是数字']));
				die();
			}
			
			if($data['count'] > 10) {
				exit(json_encode(['status'=>1,'info' => '余座不可多余10个']));
				die();
			}
			if(mb_strlen($data['rout'], 'utf-8') < 50) {
				exit(json_encode(['status'=>1,'info' => '路线不少于50个汉字']));
				die();
			}
			if(mb_strlen($data['carmodel'], 'utf-8') > 10) {
				exit(json_encode(['status'=>1,'info' => '车型不可多余10个汉字']));
				die();
			}
			if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $data['carmodel'])) {
				exit(json_encode(['status'=>1,'info' => '车型必须是汉字']));
				die();
			} 		
			$data['time'] = strtotime($data['time']);
			if($data['time'] < time()) {
				exit(json_encode(['status'=>1,'info' => '日期有误']));
				die();
			}
			
			$data['openid'] = session('openid');
			$data['status'] = 0;
			$data['number'] = $data['count'];
			
			$release = M('release');
			if($release->data($data)->add()) {
				exit(json_encode(['status'=>0,'info' => '发布成功，待审核...']));
			}		
		}
	}
	
	//发布信息查看
	public function carinfomessage() {
		$id = I('get.id');
		$release = M('release');
		$provinces = M('provinces');
		$user = M('user');
		$data = $release->where(['id' => $id])->find();
		$data['frominfo'] = $provinces->where(['id' => $data['from']])->find();
		$data['toinfo'] = $provinces->where(['id' => $data['to']])->find();
		$data['userinfo'] = $user->where(['openid' => $data['openid']])->find();
		
		$this->assign('data', $data);
		$this->assign('id', $id);
		$this->display();
	}
	
	
	public function chuxinginfo() {
		
		if(IS_AJAX) {
			$data = I('post.');
			if(empty($data['rout']) || empty($data['time']) || empty($data['carmodel']) || empty($data['money']) || empty($data['count'])) {
				exit(json_encode(['status'=>1,'info' => '字段不能为空']));
				die();
			}
			if(!is_numeric($data['money']) || !is_numeric($data['count'])) {
				exit(json_encode(['status'=>2,'info' => 'A费和余座必须是数字']));
				die();
			}
			if($data['count'] > 10) {
				exit(json_encode(['status'=>3,'info' => '余座不可多余10个']));
				die();
			}
			if(mb_strlen($data['rout'], 'utf-8') < 50 ) {
				exit(json_encode(['status'=>4,'info' => '路线不可少于50个汉字']));
				die();
			}
			if(mb_strlen($data['carmodel'], 'utf-8') > 10) {
				exit(json_encode(['status'=>5,'info' => '车型不可多余10个汉字']));
				die();
			}
			if (!preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $data['carmodel'])) {
				exit(json_encode(['status'=>6,'info' => '车型必须是汉字']));
				die();
			}
			$data['time'] = strtotime($data['time']);
			if($data['time'] < time()) {
				exit(json_encode(['status'=>7,'info' => '日期有误']));
				die();
			}
			$release = M('release'); 
			$data['status'] = 0;
			if($release->save($data)) {
				exit(json_encode(['status'=>0,'info' => '修改成功,待审核']));
			}
		}else {
			$data = $this->chuxingdata();
			
			$this->assign('data',$data);
			$this->display();  
		}
	} 
	
		
	private function chuxingdata() {
		if(IS_GET) {
			$id = I('get.id');
			$release = M('release'); 
			$provinces = M('provinces');
			$data = $release->where("openid = '".session('openid'). "'")->find($id);
			$data['formname'] = $provinces->find($data['from']);
			$data['toname'] = $provinces->find($data['to']);
			return $data;
		}
	} 
	
	
}