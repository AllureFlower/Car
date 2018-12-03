<?php
namespace Admin\Controller;

class IndexController extends CommonController {
	
	public function index(){
		
		$release = M('release');$trad = M('trad');$feedback = M('feedback');
		$count[0] = M('user')->count();
		$count[1] = $release->count();
		$count[2] = $release->where(['status' => 1])->count();
		$count[2] += $trad->where(['tuiding' => 3])->count();
		$count[2] += $feedback->where(['status' => 1])->count();
		$count[3] = $trad->count();
		$count[4] = $feedback->count();
		
		$config = [
			'php' => PHP_VERSION,
			'apache' => apache_get_version(),
			'loadtime' => get_cfg_var("max_execution_time"),
			'mysql'  =>  M()->query("select version() as v;"),
			'zend'	=> zend_version(),
		];
		
		$this->assign('count', $count);
		$this->assign('config', $config);
		$this->display();
	}
	
}