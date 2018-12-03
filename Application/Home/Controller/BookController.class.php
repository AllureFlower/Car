<?php
namespace Home\Controller;
use Think\Controller;

class BooksController extends CommonController {
	
	public function  guanli()
	{
	 	$this->display('guanli');
	}
	
	public function index() 
	{
		$this->display('Books/index');
	}
	
}