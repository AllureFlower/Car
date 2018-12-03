<?php
function get_nav_url($url){
    return U($url);
}
/**
 * 权限检测
 */
function rbac(){
	$name['role_id']	=	session('role_id');
	$rbac_role_auths = C('RBAC_ROLES_AUTHS');
	$name['currRoleAuth'] = $rbac_role_auths[$name['role_id']];
	$name['controller'] = strtolower(CONTROLLER_NAME);
	$name['action']	=	strtolower(ACTION_NAME);
	$name['rbac_bach'] = $name['controller'] . '/' . $name['action'];
	$name['rbac_master'] =  $name['controller'] . '/*';
	return $name;
}
/**
 * Tree树
 */
function list_to_tree($list, $pk='id', $pid = 'pid', $child = '_child', $root = 0) {
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent = & $refer[$parentId];
                    $parent[$child][] = & $list[$key];
                }
            }
            
        }
    }
    return $tree;
}

/*
 * 发邮件
 */
function think_send_mail($address,$subject,$body,$attachment = null,$tip = 'fengpan在线用户'){
	$config = C('THINK_EMAIL');
	vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
	vendor('PHPMailer.class#smtp');
	$mail = new \PHPMailer(); 	//建立邮件发送类
	/*检验*/
	$mail->CharSet = 'UTF-8';	//设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP(); 			// 使用SMTP方式发送
	$mail->SMTPAuth = true; // 启用SMTP验证功能
	$mail->SMTPDebug = 0;		//关闭SMTP调试功能
	$mail->isHTML(true);
	/*发件人配置*/
	$mail->Host = $config['Host']; // 您的企业邮局域名
	$mail->Username = $config['Username']; // 邮局用户名(请填写完整的email地址)
	$mail->Password = $config['Password']; // 邮局密码
	$mail->Port = $config['Port'];		   //邮箱端口
	$mail->From = $config['From']; //邮件发送者email地址
	$mail->FromName = $config['FromName'];//发件人昵称
	$mail->AddAddress($address,$tip);//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
	/*收件人配置*/
	$replyEmail = $config['ReplyEmail'];   //留空则为发件人EMAIL
	$replyName = $config['ReplyName'];     //回复名称（留空则为发件人名称）
	$mail->addReplyTo($replyEmail, $replyName);
	
	/*发送内容*/
	$mail->Subject = $subject; //邮件标题
	$mail->Body = $body; //邮件内容
	$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
	
	if(is_array($attachment)){ // 添加附件
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	return $mail->Send();
}



/**
 * js数组
 */
function jsArray($arr)
{
	$str = '[';
	foreach ($arr as $key => $val) {
		$str = $str . $val . ',';
	}
	$str = 	substr($str, 0, strlen($str)-1);
	$str .= ']';
	return $str;
}
