<?php 

namespace Admin\Model;

class CommonModel {
    public function menu($cate, $cate_id) {
		foreach ($cate as $key => &$value){
			$url = CONTROLLER_NAME.'/'.ACTION_NAME.'/cate_id/';
        	$value['url']   =   U($url.$value['id']);

        	if(!empty($value['_child'])) {
        		foreach ($value['_child'] as $key => &$va) {
                    $va['url'] = U($url.$va['id']);
                    
        			if(!empty($va['_child'])) {
        				foreach ($va['_child'] as $key => &$v) {
        					$v['url'] = U($url.$v['id']);
        				}
        			}
        			
	        		if($cate_id == $va['id'] || $cate_id == $value['id']) {
		            	$value['current']   =   true;
		            }
        		}
            }
        }
        return $cate;
	}

}