<?php 

namespace Admin\Controller;

class UserController extends CommonController {
	
	const COUNRT = 3;
	
	public function index() {
		$status = 1;
		
		$nickname = I('get.nickname');

		if(!empty($nickname)){ 
			$data = $this->getData($status,0,0,$nickname);
			$show = $this->getShow($status,$time);    
		}else {
			$data = $this->getData($status);
			$show = $this->getShow($status);	 
		}
		$this->assign('show', $show); 
		$this->assign('data', $data); 
		$this->assign('current', 1); 
		$this->display();
	}
	
	public function refuse()
	{
		$status = 0;
		
		$nickname = I('get.nickname');

		if(!empty($nickname)){ 
			$data = $this->getData($status,0,0,$nickname);
			$show = $this->getShow($status,$time);    
		}else {
			$data = $this->getData($status);
			$show = $this->getShow($status);	 
		}
		$this->assign('show', $show); 
		$this->assign('data', $data); 
		$this->assign('current', 0); 
		$this->display();
	}
	
	
	public function show()
	{
		$status = 1;
		$id = I('get.openid');
		$data = $this->getData(1,1,$id);
		$this->assign('data', $data[0]); 
		$this->display();
	}
	
	public function none()
	{
		$openid = I('get.openid');
		$data['openid'] = $openid;
		$data['status'] = 0;
		$user = M('user');
		if($user->save($data)){
			exit(json_encode(['status'=>1,'info' => '操作成功'])); 
		}else {
			exit(json_encode(['status'=>0,'info' => '操作失败，请刷新浏览器重试'])); 
		}
	}
		
	private function getData($status, $show = 0, $id = 0, $nickname = '')
	{
		$user = M('user');
		$Page = $this->getPage($status,$nickname);
		if($show == 1){
			$data = $user->where(['openid' => $id])->order('time')->limit($Page->firstRow.','.$Page->listRows)->select();
		}elseif(!empty($nickname) && is_string($nickname)) {
			$data = $user->where(['status' => $status, 'nickname' => ['LIKE', '%'.$nickname.'%']])->limit($Page->firstRow.','.$Page->listRows)->select();
		}else {
			$data = $user->where(['status' => $status])->limit($Page->firstRow.','.$Page->listRows)->select();
		}
		return $data;
	}
	private function getPage($status, $nickname = '')
	{
		$user = M('user');
		
		if(!empty($nickname) && is_string($nickname)) {
			$count = $user->where(['status' => $status, 'nickname' => ['LIKE', $nickname]])->count();
		}else {
			$count = $user->where(['status' => $status])->count();
		}
		$Page = new \Think\Page($count, self::COUNRT);
		return $Page;
	}
	
	private function getShow($status, $nickname = '')
	{
		$Page = $this->getPage($status, $nickname);
		$show  = $Page->show();
		return $show;
	}
	
	
	
	/*------------------------------------------------------------------*/
	/*------------------------------------------------------------------*/
	/*------------------------------------------------------------------*/
	/*------------------------------------------------------------------*/
	/*------------------------------------------------------------------*/
	
	
	public function member()
	{
		$data = $this->get_m_data();
		$show = $this->get_m_show();
		$this->assign('data', $data); 	 
		$this->assign('show', $show); 
		$this->assign('current', 2); 
		$this->display();
	}
	
	
	private function get_m_data()
	{
		$member = M('member');
		$Page = $this->get_m_page();
		$data = $member->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		return $data;
	}
	
	private function get_m_page()
	{
		$member = M('member');
		$count = $member->count();
		$Page = new \Think\Page($count, self::COUNRT);
		return $Page;
	}
	private function get_m_show()
	{
		$Page = $this->get_m_page();
		$show  = $Page->show();
		return $show;
	}
	
	public function m_show()
	{
		$id = I('get.id');
		$member = M('member');
		$local = ip_location();
		$data = $member->where(['id' => $id])->find();
		$this->assign('data', $data);
		$this->assign('local', $local);
		
		$this->display();
	}
	
	public function m_none()
	{
		$id = I('get.id');
		$member = M('member');
		if($member->delete($id)){
			exit(json_encode(['status'=>1,'info' => '删除成功'])); 
		}else {
			exit(json_encode(['status'=>0,'info' => '删除失败，请重试'])); 
		}
	}

	public function m_add()
	{
		if(IS_POST) {
			
			$data = I('post.');
			$verity = new \Think\Verify();
			
			if(empty($data['username'])){
				exit(json_encode(['status'=>0,'info' => '用户名不能为空'])); 
				die();
			}elseif(empty($data['password'])){
				exit(json_encode(['status'=>0,'info' => '密码不能为空'])); 
				die();
			}elseif(empty($data['passwordtrue'])) {
				exit(json_encode(['status'=>0,'info' => '确认密码不能为空'])); 
				die();
			}elseif($data['password'] != $data['passwordtrue']) {
				exit(json_encode(['status'=>0,'info' => '密码不一致'])); 
				die();
			}elseif(mb_strlen($data['username'] , 'utf-8') < 2 || mb_strlen($data['username'] , 'utf-8') > 6){
				exit(json_encode(['status'=>0,'info' => '用户名在2-6个字符之间'])); 
				die();
			}elseif(mb_strlen($data['password'] , 'utf-8') < 6){
				exit(json_encode(['status'=>0,'info' => '密码不能小于6位'])); 
				die();
			}elseif(!$verity->check($data['captcha'])) {
				exit(json_encode(['status'=>0,'info' => '验证码不正确'])); 
				die();
			}
			
			$savedata['username'] = $data['username'];
			$savedata['password'] = $data['password'];
			$savedata['login_time'] = time();
			$savedata['login_ip'] = get_client_ip();
			
			$member = M('member');
			
			if($member->data($savedata)->add()) {
				exit(json_encode(['status'=>1,'info' => '添加成功'])); 
			}else {
				exit(json_encode(['status'=>0,'info' => '添加失败，请重试'])); 
			}
			
		}else {
			$this->display();
		}
	}
	public function captcha() {
		$config = array(
				'fontSize'  =>  100,              // 验证码字体大小(px)
//				'useCurve'  =>  false,            // 是否画混淆曲线
//				'useNoise'  =>  false,            // 是否添加杂点
//				'length'    =>  4,              // 验证码位数
//				'fontttf'   =>  '4.ttf',              // 验证码字体，不设置随机获取
				'bg'        =>  array(95, 184, 120),  // 背景颜色
		);
		$verify = new \Think\Verify($config);
		$verify->entry();
	}
}