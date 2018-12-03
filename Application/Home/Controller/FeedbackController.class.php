<?php
namespace Home\Controller;
use Think\Controller;

class FeedbackController extends CommonController {
	
    public function index(){
    	
		$data = $this->alldata();			
		$this->assign('data', $data);
    	$this->display();
    }
	
	public function editfeed() {
		if(IS_AJAX) {
			
			$data = I('post.');
			if(mb_strlen($data['result'], 'utf-8') < 10) {
				exit(json_encode(['status'=>0,'info' => '理由不可少于10个字符']));
				die();
			}
			$data['pessengers'] = session('openid');
			$data['time'] = time();
			$data['status'] = 1;
			$feedback = M('feedback');
			
			$trad = M('trad');
			$t_data['id'] = $data['trad_id'];
			$t_data['feedback'] = 1;
			
			if($feedback->data($data)->add() && $trad->save($t_data)) {
				exit(json_encode(['status'=>1,'info' => '反馈成功，谢谢配合']));
				die();
			}else {
				exit(json_encode(['status'=>0,'info' => '反馈失败，请检查你的登录信息']));
				die();
			}
		}else {
			$id = I('get.trad');	
			$data = $this->alldata($id);
			$this->assign('data', $data[0]);
			$this->display();
		}
	}
	
	
	private function alldata($id = 0) {
		$trad = M('trad');
		$where['trad.owner_openid'] = session('openid');
		$where['trad.status'] = 1;
		
		if($id != 0) {
			$where['trad.id'] = $id;
		}
	
		$data = $trad->alias('trad')
					->field('releases.*, trad.*, releases.time as rtime, releases.number as rnumber, releases.status as rstatus')
					->join('car_release as releases ON trad.owner = releases.id')
					->where(['releases.time' => ['gt',time()]])
					->where($where)
					
					->select(); 
		$provinces = M('provinces');
		$user = M('user');
		
		foreach($data as $key => $value) {
			$data[$key]['from'] = $provinces->where(['id' => $value['from']])->find();
			$data[$key]['to']	= $provinces->where(['id' => $value['to']])->find();
			$data[$key]['user'] = $user->where(['openid' => $value['owner_openid']])->find();
		}
		
		return $data;
	}
}