<?php
namespace Home\Controller;
use Think\Controller;

class BooksController extends CommonController {
	
	public function  guanli()
	{
	 	$this->display('Books/guanli');
	}
	
	public function index() 
	{
		$openid = session('openid');
		$User = M('User');
		$list = $User->where(['openid' => $openid])->find();
		$this->assign('list', $list);
		$this->display('Books/index');
	}
	
	public function suse() 
	{
		$id = I('get.id', 0);
		$home = M('home');
		if($id) {
			$home->delete($id);
		}
		$data = $home->select();
		$this->assign('data', $data);
		$this->display('Books/suse');
	}
	
	public function student() 
	{
		$id = I('get.id', 0);
		$book = M('book');
		if($id) {
			$book->delete($id);
		}

		$data = $book->select();
		$this->assign('data', $data);	
		$this->display();
	}
	
	public function studentinfo()
	 {
	 	$id = I('get.id');
		$book = M('book');
		$home = M('home');
		$data = $book->find($id);
		$data['suse'] = $home->find($data['sno']);
		$this->assign('data', $data);
	 	$this->display();
	 }
	
	
	public function guanlsuse()
	 {
	 	if(IS_POST) {
	 		$data = I('post.');
			$home = M('home');
			$in = $home->data($data)->add();
			if($in) {
				$this->suse();
			}
	 	}else {
	 		$this->display();
	 	}
	 }
	
	public function guanlstud()
	 {
	 	if(IS_POST) {
	 		$data = I('post.');
			$book = M('book');
			$in = $book->data($data)->add();
			if($in) {
				$this->guanli();
			}
	 	}else {
	 		$home = M('home');
			$select = $home->select();
			$this->assign('select', $select);
		 	$this->display();
	 	}
	 }
}