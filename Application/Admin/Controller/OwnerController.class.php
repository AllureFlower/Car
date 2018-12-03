<?php 

namespace Admin\Controller;

class OwnerController extends CommonController {
	
	const PageCount = 3;
	
	public function index(){
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->ownerdata(0, 0, $time,1); 
			$show = $this->getShow(0, $time);	    
		}else {
			$data = $this->ownerdata(0); 
			$show = $this->getShow(0);		 
		}
		
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 0);
		
		$this->assign('pagecount', self::PageCount);
		$this->display(); 
	}
	
	public function complete(){
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->ownerdata(1, 0, $time,1);  
			$show = $this->getShow(1, $time);	  
		}else {
			$data = $this->ownerdata(1);	 
			$show = $this->getShow(1);	
		}
		  	 
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 1);
		$this->assign('pagecount', self::PageCount);
		$this->display();
	}
	
	public function rejected(){
		
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->ownerdata(2, 0, $time,1);  
			$show = $this->getShow(2, $time);	  
		}else {
			$data = $this->ownerdata(2);	 
			$show = $this->getShow(2);	
		}
	
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 2);
		$this->assign('pagecount', self::PageCount);
		$this->display();
	}
	
	private function ownerdata($status = 0, $id = 0, $time = 0, $own = 0) {
		$provinces = M('provinces');
		$release = M('release'); 
		$user = M('user');
		$admin = M('member'); 
		
		$Page = $this->getPage($status);
		
		if($status == 3) { 
			$data = $release->where(['id' => $id])->limit($Page->firstRow.','.$Page->listRows)->select();  
		}elseif ($own == 1){
			$endtime = $time+86400; 
			$data = $release->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->limit($Page->firstRow.','.$Page->listRows)->select(); 
		}else {
			$data = $release->where(['status' => $status])->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		} 
		
		foreach($data as $key => $value) {
			$data[$key]['from'] = $provinces->where(['id' => $value['from']])->find();
			$data[$key]['to'] = $provinces->where(['id' => $value['to']])->find();
			$data[$key]['user'] = $user->where(["openid" => $value['openid']])->find();
			$data[$key]['admin'] = $admin->where(['id' => $value['userid']])->find();
		}
		return $data; 
	}
	
	private function getShow($status = 0, $time = 0){
		$Page = $this->getPage($status, $time);
		$show = $Page->show();
		return $show;
	}
	
	private function getPage($status = 0, $time = 0) 
	{
		$release = M('release'); 
		if($time > 0) {
			$count  = $release->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->count();
		}else {
			$count  = $release->where(['status' => $status])->count();
		}
		$Page  = new \Think\Page($count, self::PageCount);  
		return $Page;
	}
	
	
	public function agree()
	{
		if(IS_AJAX)
		{
			$id = I('get.id');
			$release = M('release');
			$data['status'] =  1;
			$data['rtext'] = '';
			$data['userid'] = session('id');
			
			if($release->where(['id' => $id])->save($data)){
				exit(json_encode(['status'=>1,'info' => '审核通过，同意发布'])); 
			}else {
				exit(json_encode(['status'=>0,'info' => '操作失败']));
			}
		}
	}
	public function agrees() 
	{
		if(IS_AJAX)
		{
			$id = I('get.id');
			$release = M('release');
			
			$string = '';
			
			for($i = count($id)-1; $i >= 0 ; $i-- ){
				$data = [];
				$data['status'] =  1;
				$data['rtext'] = '';
				$data['userid'] = session('id');

				if(!$release->where(['id' => $id[$i]])->save($data)){
					$string = $id[$i]  . ',' . $string;
				}
			}	
			
			$string = substr($string, 0,-1);

			if(!empty($string)) {
				exit(json_encode(['status'=>0,'info' => "第{$string}条操作失败"]));
			}else {
				exit(json_encode(['status'=>1,'info' => '批量审核通过，同意发布']));
			}
		}
	}
	
	public function refuse()
	{
		if(IS_AJAX)
		{
			$data = I('post.');	
			
			if(mb_strlen($data['rtext'], 'utf-8') <= 4) {
				exit(json_encode(['status'=>0,'info' => '请输入原因'])); 
			}
			
			if(mb_strlen($data['rtext'], 'utf-8') > 255) {
				exit(json_encode(['status'=>0,'info' => '操作失败，理由不能超过255个字'])); 
			}
			$release = M('release');
			$data['status'] = 2;
			$data['userid'] = session('id');
			
			if($release->where(['id' => $data['id']])->save($data)){
				exit(json_encode(['status'=>1,'info' => '操作成功，已禁止发布'])); 
			}else {
				exit(json_encode(['status'=>0,'info' => '操作失败,禁止原因，请联系管理员'])); 
			}
		}
	}
	
	public function show()
	{
		$id = I('get.id');
		$data = $this->ownerdata(3, $id);
		$show = $this->getShow();
		
		$this->assign('data', $data[0]);
		$this->display(); 
	}
	
}