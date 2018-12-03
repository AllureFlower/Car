<?php
return array(
	//'配置项'=>'配置值'
	/* 模板相关配置 */
	'TMPL_PARSE_STRING' => array(
			'__STATIC__' => __ROOT__ . '/Public/static',
			'__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/Addons',
			'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
			'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
			'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
			'__PLUGS__'  => __ROOT__ . '/Public/Plugs',
			'__STATIC__'  => __ROOT__ . '/Public/static',
	),

	//RBAC权限
	//权限等级
	'RBAC_ROLES'	=>	array(
			0	=>	'root',
			1	=>	'普通管理员',
	),
	//权力分配
	'RBAC_ROLES_AUTHS'	=>	array(
			0		=>		'*/*',
			1		=>		array('user/*'),
	),
	//邮箱配置
	'THINK_EMAIL' => array(
		'Host' => 'smtp.126.com', //SMTP服务器
		'Port' => '25', 	//SMTP服务器端口
		'Username' => 'feng_pan26', //SMTP邮局用户名
 		'Password' => 'world123456hello', //SMTP服务器密码
 		'From' => 'feng_pan26@126.com',	//邮件发送者email地址
 		'FromName' => 'fengpan', 	//发件人名称(默认为发件人名字)
		'ReplyEmail' => '', 		//回复EMAIL（留空则为发件人EMAIL）
		'ReplyName' => '', 			//回复名称（留空则为发件人名称）
		'SESSION_EXPIRE'=>'72',
	),
);