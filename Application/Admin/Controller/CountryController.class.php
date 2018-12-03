<?php
namespace Admin\Controller;

class CountryController extends CommonController {
	
	public function index(){
		$data = M('provinces')->select();	
		$data = list_to_tree($data);
		if(!session('?curty')) {
			session('curty', 0);
		}
		$this->assign('data', $data);
		$this->display();
	}
	
	public function edit()
	{
		if(IS_AJAX) 
		{
			$data = I('post.');
			$curty = $data['curty'];
			if(empty($data['proname'])){
				exit(json_encode(['status'=>0,'info' => '地区名不能为空']));
			}else {
				$provinces = M('provinces');
				
				if($provinces->where(['proname' => $data['proname']])->count()) {
					exit(json_encode(['status'=>0,'info' => '该地区已存在']));
				}
				
				
				if($provinces->save($data)) {
					session('curty', $curty);
					exit(json_encode(['status'=>1,'info' => '修改成功']));
				}else {
					exit(json_encode(['status'=>0,'info' => '你暂未修改名称']));
				}
			}
		}
	}
	
	public function add() {
		if(IS_AJAX)
		{
			$data = I('post.');
			$curty = $data['curty'];
			if(empty($data['proname'])){
				exit(json_encode(['status'=>0,'info' => '地区名不能为空']));
			}else {
				$provinces = M('provinces');
				
				if($provinces->where(['proname' => $data['proname']])->count()) {
					exit(json_encode(['status'=>0,'info' => '该地区已存在']));
				}
				
				
				if($provinces->data($data)->add()) {
					session('curty', $curty);
					exit(json_encode(['status'=>1,'info' => '添加成功']));
				}else {
					exit(json_encode(['status'=>0,'info' => '添加失败，请刷新浏览器重试']));
				}
			}
		}
	}
	
	
	public function del()
	{
		if(IS_AJAX)
		{
			$id = I('post.id');
			$curty = I('post.curty');
			if(!is_numeric($id)) {
				exit(json_encode(['status'=>0,'info' => '删除失败，请刷新浏览器重试']));
			}
			
			if(M('provinces')->delete($id)){
				session('curty', $curty);
				exit(json_encode(['status'=>1,'info' => '删除成功']));
			}else {
				exit(json_encode(['status'=>0,'info' => '删除失败，请刷新浏览器重试']));
			}
			
		}
	}
	
}