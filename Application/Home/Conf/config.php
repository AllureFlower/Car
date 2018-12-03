<?php
return array(
	//'配置项'=>'配置值'
	//自定义模板
	'TMPL_PARSE_STRING' => array(
			'__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/images',
			'__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
			'__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
			'__FONTS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/fonts',
			'__LAYER__'		=>	__ROOT__ . '/Public/' . MODULE_NAME . '/layer', 
			'__BOOTSTRAP__'		=>	__ROOT__ . '/Public/' . MODULE_NAME . '/bootstrap', 
	),
	 
	'APPID'	=> 'wxa2f6a2b0db1bda3c',
	'Appsecret' => '024b6d900e4cdacfd3d1976c874646fe',
	'RedirectUrl' => 'http://182.254.245.209/car/Home/People',
	
	'Account'	=>	'C88413720',
	'Password'	=>	'0f326083ac9c45d9b9ee3ab2aff61a3c',
	
	'TMPL_ACTION_SUCCESS'=>'Public:dispatch_jump',
	'TMPL_ACTION_ERROR'	=>'Public:dispatch_jump',
);