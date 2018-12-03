<?php 

namespace Admin\Controller;
use Think\Controller;

class EmailController extends CommonController {
	
	public function index(){
		
		if(IS_AJAX){
			$post = I('post.');
			
			$address = $post['user'];
			$title =  $post['theme'];
			$nav = $post['text'];

			if(think_send_mail($address,$title,$nav)){
				exit(json_encode(array('status'=>0,'info'=>'邮箱发送成功,请注意接收')));
			}else {
				exit(json_encode(array('status'=>1,'info'=>'邮箱发送失败，邮箱格式不正确')));
			}
		}else {
			$this->display();
		}
	}

}