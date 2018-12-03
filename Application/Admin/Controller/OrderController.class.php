<?php 

namespace Admin\Controller;

class OrderController extends CommonController {
	
	const PageCount = 3;
	
	public function index() 
	{
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getData(0, 0, $time,1); 
			$show = $this->getShow(0, $time);	    
		}else {
			$data = $this->getData(0); 
			$show = $this->getShow(0);		 
		}
		
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 0);
		
		$this->assign('pagecount', self::PageCount);
		$this->display(); 
	}
	
	public function complete()
	{
		$time = I('get.time');
		$time = strtotime($time);  
 
		if(!empty($time)){   
			$data = $this->getData(1, 0, $time,1);  
			$show = $this->getShow(1, $time);	  
		}else {
			$data = $this->getData(1);	 
			$show = $this->getShow(1);	
		}

		foreach($data as $key => $val)
		{
			if($val['release']['time'] < time())
			{
				$data[$key]['old'] = 0;
			}else {
				$data[$key]['old'] = 1;
			}
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
			$data = $this->getData(2, 0, $time,1);  
			$show = $this->getShow(2, $time);	  
		}else {
			$data = $this->getData(2);	 
			$show = $this->getShow(2);	
		}
	
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 2);
		$this->assign('pagecount', self::PageCount);
		$this->display();
	}
	
	
	public function refused_d()
	{
		$id = I('get.id');

		$trad = M('trad');
		$t_data = $trad->where(['id' => $id])->find();
		
		$release = M('release');
		$r_data = $release->where(['id' => $t_data['owner']])->find();
		
		$rdata['id'] = $t_data['owner'];
		$rdata['number'] = $r_data['number'] + $t_data['number'];
		
		$tdata['id'] = $id;
		$tdata['status'] = 2;
		
		if($trad->save($tdata) && $release->save($rdata)){
			exit(json_encode(['status'=>1,'info' => '操作成功']));
		}else {
			exit(json_encode(['status'=>0,'info' => '操作失败，请刷新页面重试']));
		}
	}
	
	
	private function getData($status = 0, $id = 0, $time = 0, $own = 0)
	{
		$trad = M('trad');
		$release = M('release'); 
		$provinces = M('provinces');
		$user = M('user');
		$admin = M('member'); 
		
		$Page = $this->getPage($status, $time);
		
		if($status == 3) { 
			$data = $trad->where(['id' => $id])->limit($Page->firstRow.','.$Page->listRows)->select();  
		}elseif ($own == 1){
			$endtime = $time+86400; 
			$data = $trad->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->limit($Page->firstRow.','.$Page->listRows)->select(); 
		}else {
			$data = $trad->where(['status' => $status])->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		
		foreach($data as $key => $value) {
			$data[$key]['release'] = $release->where(['id' => $value['owner']])->find();
			$data[$key]['from'] = $provinces->where(['id' => $data[$key]['release']['from']])->find();
			$data[$key]['to'] = $provinces->where(['id' => $data[$key]['release']['to']])->find();
			$data[$key]['passengers'] = $user->where(["openid" => $value['passengers']])->find();
			$data[$key]['owner'] =  $user->where(["openid" => $value['owner_openid']])->find();
			$data[$key]['admin'] = $admin->where(['id' => $data[$key]['release']['userid']])->find();
		}
		foreach($data as $key => $value) {
			if($value.['release'].['time']  <  time() ) {
				$data[$key]['oldOrder'] = 0;
			}else {
				$data[$key]['oldOrder'] = 1;
			}
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
		$trad = M('trad'); 
		if($time > 0) {
			$endtime = $time+86400; 
			$count  = $trad->where(['status' => $status, 'time' => ['between', [$time, $endtime]]])->count();
		}else {
			$count  = $trad->where(['status' => $status])->count();
		}
		$Page  = new \Think\Page($count, self::PageCount);  
		return $Page;
	}
	
	public function show()
	{
		$id = I('get.id');
		$data = $this->getData(3, $id);
		$show = $this->getShow();
		
		$this->assign('data',  $data[0]);
		$this->assign('time', time());
		$this->display(); 
	}
	
	
	public function tuiding()
	{	
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getDataT(3, 0, $time,1); 
			$show = $this->getShowT(3, $time);	    
		}else {
			$data = $this->getDataT(3); 
			$show = $this->getShowT(3);		 
		}
		
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 4);
		
		$this->assign('pagecount', self::PageCount);
		$this->display(); 
	}

	public function tuidingcom()
	{
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getDataT(1, 0, $time,1); 
			$show = $this->getShowT(1, $time);	    
		}else {
			$data = $this->getDataT(1); 
			$show = $this->getShowT(1);		 
		}
		
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 3);
		
		$this->assign('pagecount', self::PageCount);
		$this->display(); 
	}
	public function tuidingrej()
	{
		$time = I('get.time');
		$time = strtotime($time); 

		if(!empty($time)){ 
			$data = $this->getDataT(2, 0, $time,1); 
			$show = $this->getShowT(2, $time);	    
		}else {
			$data = $this->getDataT(2); 
			$show = $this->getShowT(2);		 
		}
		
		$this->assign('data', $data); 
		$this->assign('show', $show);
		$this->assign('current', 5);
		
		$this->assign('pagecount', self::PageCount);
		$this->display(); 
	}
	
	
	
	private function getDataT($tuiding = 0, $id = 0, $time = 0, $own = 0)
	{
		$trad = M('trad');$release = M('release'); $provinces = M('provinces');$user = M('user');$admin = M('member'); 
		$Page = $this->getPageT($tuiding);
		$show = $this->getShowT($tuiding,$time);
		
		if($tuiding == 6) {	
			$data = $trad->where(['id' => $id])->limit($Page->firstRow.','.$Page->listRows)->select();  
		}elseif ($own == 1){
			$endtime = $time+86400; 
			$data = $trad->where(['tuiding' => $tuiding, 'time' => ['between', [$time, $endtime]]])->limit($Page->firstRow.','.$Page->listRows)->select(); 
		}else {
			$data = $trad->where(['tuiding' => $tuiding])->order('time asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		
		
		foreach($data as $key => $value) {
			$data[$key]['release'] = $release->where(['id' => $value['owner']])->find();
			$data[$key]['from'] = $provinces->where(['id' => $data[$key]['release']['from']])->find();
			$data[$key]['to'] = $provinces->where(['id' => $data[$key]['release']['to']])->find();
			$data[$key]['passengers'] = $user->where(["openid" => $value['passengers']])->find();
			$data[$key]['owner'] =  $user->where(["openid" => $value['owner_openid']])->find();
			$data[$key]['admin'] = $admin->where(['id' => $data[$key]['release']['userid']])->find();
		}
		foreach($data as $key => $val)
		{
			if($val['release']['time'] <= time() - 24*60*60)
			{
				$data[$key]['old'] = 0;
			}else {
				$data[$key]['old'] = 1;
			}
		}
		return $data;
	}


	private function getShowT($tuiding = 0, $time = 0)
	{
		$Page = $this->getPageT($tuiding, $time);
		$show = $Page->show();
		return $show;
	}

	private function getPageT($tuiding = 0, $time = 0)
	{
		$trad = M('trad'); 
		if($time > 0) {
			$count  = $trad->where(['tuiding' => $tuiding, 'time' => ['between', [$time, $endtime]]])->count();
		}else {
			$count  = $trad->where(['tuiding' => $tuiding])->count();
		}
		
		$Page  = new \Think\Page($count, self::PageCount);  
		
		return $Page;
	}



	public function showt()
	{
		$id = I('get.id');
		$data = $this->getDataT(3,$id);
		$this->assign('data',  $data[0]);
		$this->assign('time', time());
		$this->display(); 
	}
	
	
	public function refuset()
	{
		if(IS_AJAX)
		{
			$dataget = I('post.');
			$trad = M('trad');
			$data['id'] = $dataget['id'];
			$data['tuiding'] = 2;
			$data['t_result'] = $dataget['rtext'];
			
			if($trad->save($data))
			{
				exit(json_encode(['status'=>1,'info' => '操作成功，已拒绝退订']));
			}
			else
			{
				exit(json_encode(['status'=>0,'info' => '操作失败，请刷新页面重试']));
			}
		}
	}
	
	public function agreet()
	{
		if(IS_AJAX)
		{
			$dataget = I('get.');
			$trad = M('trad');
			$data['id'] = $dataget['id'];
			$data['tuiding'] = 1;
			
			$releale_number = $dataget['releale_number'];
			$releale_id = $dataget['releale_id'];
			
			$release = M('release');
			$r_data = $release->where(['id' => $releale_id])->find();
			
			$r_data['number'] += $releale_number;
			 
			if($trad->save($data) && $release->save($r_data)) 
			{
				exit(json_encode(['status'=>1,'info' => '操作成功，已同意退订']));
			}
			else
			{
				exit(json_encode(['status'=>0,'info' => '操作失败，请刷新页面重试']));
			}
		}
	}
	
	public function agreeAll()
	{
		if(IS_AJAX)
		{
			$ids = I('get.id');
			$trad = M('trad');
			$string = '';
			
			for($i = count($ids)-1; $i >= 0 ; $i-- ){	
				$data = [];
				$data['id'] = $ids[$i];	
				$data['tuiding'] = 1;
				
				if(!$trad->save($data)){
					$string = $ids[$i]  . ',' . $string;
					
				}
			}	
			$string = substr($string, 0,-1);
			if(!empty($string)) {
				exit(json_encode(['status'=>0,'info' => "第{$string}条操作失败"]));
			}else {
				exit(json_encode(['status'=>1,'info' => '批量审核通过，同意发布']));
				die();
			}
		}
	}
}