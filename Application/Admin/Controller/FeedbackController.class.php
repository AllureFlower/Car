<?php 

namespace Admin\Controller;

class FeedbackController extends CommonController {
	
	const COUNRT = 3;
	const REPUTATION = -3;
	
	public function index() {
		$status = 1;
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getData($status,0,0,$time);
			$show = $this->getShow($status,$time);    
		}else {
			$data = $this->getData($status);
			$show = $this->getShow($status);	 
		}
		$this->assign('data', $data); 
		$this->assign('show', $show); 
		$this->display();
	}
	
	public function com() {
		
		$status = 0;
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getData($status,0,0,$time);
			$show = $this->getShow($status,$time);    
		}else {
			$data = $this->getData($status);
			$show = $this->getShow($status);	 
		}
		$this->assign('data', $data); 
		$this->assign('show', $show); 
		$this->display();
	}
	
	
	public function show()
	{
		$id = I('get.id');
		$data = $this->getData(1,1,$id);
		$this->assign('data', $data[0]); 
		$this->display();
	}
	
	public function refused()
	{
		if(IS_GET) {
			$id = I('get.id');
			$data['id'] = $id;
			$data['status'] = 2;
			
			$feedback = M('feedback');
			
			if($feedback->save($data)){
				exit(json_encode(['status'=>1,'info' => '操作成功'])); 
			}else {
				exit(json_encode(['status'=>0,'info' => '操作失败，请刷新页面重试'])); 
			}
		}
	}
	public function kouchu()
	{
		$id = I('get.id');
		$data['id'] = $id;
		$data['status'] = 0;
		
		$feedback = M('feedback');
		$trad = M('trad');
		$user = M('user');
		
		$udata = $feedback->where(['id' => $id])->find();
		$udata = $trad->where(['id' => $udata['trad_id']])->find();
		$udata = $user->where(['openid' => $udata['owner_openid']])->find();
		$udata['reputation'] = $udata['reputation'] - 1;
		if($udata['reputation'] < self::REPUTATION) {
			$data['status'] = 0;
		}
		
		if($feedback->save($data) && $user->save($udata)){
			exit(json_encode(['status'=>1,'info' => '操作成功'])); 
		}else {
			exit(json_encode(['status'=>0,'info' => '操作失败，请刷新页面重试'])); 
		}
		
	}
	
	public function tuiding()
	{
		$status = 2;
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getData($status,0,0,$time);
			$show = $this->getShow($status,$time);    
		}else {
			$data = $this->getData($status);
			$show = $this->getShow($status);	 
		}
		$this->assign('data', $data); 
		$this->assign('show', $show); 
		$this->display();	
	}
	
	
	private function getData($status, $show = 0, $id = 0, $time = 0)
	{
		$feedback = M('feedback');$trad = M('trad');$user = M('user');$provinces = M('provinces');$release = M('release');
		$Page = $this->getPage($status,$time);
		
		if($show == 1){
			$data = $feedback->where(['id' => $id])->order('time')->limit($Page->firstRow.','.$Page->listRows)->select();
		}elseif($time > 0){
			$endtime = $time+86400; 
			$data = $feedback->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->order('time')
				->limit($Page->firstRow.','.$Page->listRows)->select();
		}else {
			$data = $feedback->where(['status' => $status])->order('time')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		
		foreach($data as $key => $value)
		{	
			$data[$key]['trad'] = $trad->where(['trad_id' => $value['trad_id']])->find();
			$data[$key]['user'] = $user->where(["openid" => $value['pessengers']])->find();
			$data[$key]['owner'] = $user->where(["openid" => $data[$key]['trad']['owner_openid']])->find();
			$data[$key]['release'] = $release->where(['id' => $data[$key]['trad']['owner']])->find();
			$data[$key]['from'] = $provinces->where(['id' => $data[$key]['release']['from']])->find();
			$data[$key]['to'] = $provinces->where(['id' => $data[$key]['release']['to']])->find();
		}
		return $data;
	}
	
	private function getPage($status,$time)
	{
		$feedback = M('feedback'); 
		
		if($time > 0){
			$endtime = $time+86400; 
			$count      = $feedback->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->count();
		}else {
			$count      = $feedback->where(['status' => $status])->count();
		}
		$Page       = new \Think\Page($count, self::COUNRT);
		return $Page;
	}
	
	private function getShow($status, $time)
	{
		$Page = $this->getPage($status,$time);
		$show  = $Page->show();
		return $show;
	}

	
}