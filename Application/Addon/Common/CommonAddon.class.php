<?php 

namespace Addon\Common;


use Addon\Controller\Addon;

class  CommonAddon extends Addon {

	public function header(){
		$where['pid'] = 0;
		$title = M('menu')->where($where)->order('sort asc')->select();
		foreach ($title as $key => $item) {
			if(strtolower(CONTROLLER_NAME.'/'.ACTION_NAME)  == strtolower($item['url'])){
                $title[$key]['class']='current';
            }
		}
		
		$this->assign('title',$title);
		$this->display('header');
	}
	public function foot() {
		$this->display('foot');
	}
}