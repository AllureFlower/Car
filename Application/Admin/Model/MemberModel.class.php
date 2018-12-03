<?php
namespace Admin\Model;

use Think\Model;

class MemberModel extends Model {
	
	protected $_validate = array(
			array('username','require','登录名不能为空'),
			array('password','require','密码不能为空'),
	);
}