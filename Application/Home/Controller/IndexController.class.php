<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {
	
    public function index(){
    	
		if(IS_GET) {
			$string = I('get.');
			$loc = array_keys($string)[0];
			$locId = array_values($string)[0];
			$City = M('provinces');
			if($loc == 'from') {
				$from = $City->where(['id' => $locId])->find();
				session('from',$from);
			} else {
				$to = $City->where(['id' => $locId])->find();
				session('to',$to);
			}
		}
		
		vendor('getLocal.jssdk'); 
		$appid = C('APPID');
		$secret = C('Appsecret');
    	$jssdk = new \JSSDK($appid, $secret);
		$signPackage = $jssdk->GetSignPackage();

		$this->assign('signPackage', $signPackage);
		
		$count =  parent::message_count();
		$this->assign('count', $count);
		$this->assign('time', time());
		$this->display();
    }
	
	
	public function tolocal() {
		if(IS_AJAX) {
			$local = I('post.local');	
			if(!empty($local)) {
				$local = local($local);
				
				$province = M('provinces');
				
				$data = $province->where(['proname' => $local])->find();
				
				if(empty($data)){
					session('fl', $local);
					exit(json_encode(['status'=>1,'info' => $local]));
				}else {
					session('fl', $data['proname']);
					exit(json_encode(['status'=>2,'info' => $data['proname'], 'id' => $data['id']]));
				}
			}	else {
				exit(json_encode(['status'=>0]));
			}
		}
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
	
	
}