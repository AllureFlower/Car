<?php
//微信授权认证
function getinfo($code) {
	$appid = C('APPID');
	$secret = C('Appsecret');
	$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code";
	$str = file_get_contents($url);
	$str = json_decode($str, true);	
	return $str;
}
//获取用户基本信息
function getuser($access_token, $openid) {
	$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
	$str = file_get_contents($url);
	$str = json_decode($str, true);
	return $str;
} 
//获取用户地理位置信息
function local($localhost) {
	$url = 'http://apis.map.qq.com/ws/geocoder/v1/?location='.$localhost.'&key=SFPBZ-G5SWW-VT6RC-OJDQ3-TG7QJ-FOFA7';
	$str = file_get_contents($url);
	$str = json_decode($str, true);
	$url = $str['result']['ad_info']['city'];
	$url = mb_substr($url, 0, mb_strlen($url, 'utf-8') - 1, 'utf-8');  
	return $url;
}

/**
 * 把返回的数据集转换成Tree
 * @access public
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
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
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

function menu($cate, $string, $fun) {
	foreach ($cate as $key => &$value){
    	$value['url']   =   __CONTROLLER__  .'/'. $fun .'/'. $string.'/'.$value['id']; 
    	if(!empty($value['_child'])) {
    		foreach ($value['_child'] as $key => &$va) {
				$va['url'] =  __CONTROLLER__  .'/'. $fun .'/'. $string.'/'.$va['id']; 
    			if(!empty($va['_child'])) {
    				foreach ($va['_child'] as $key => &$v) {
//  					$v['url'] = U($url.$v['id']);
						$v['url'] =  __CONTROLLER__  .'/'. $fun .'/'. $string.'/'.$v['id']; 
						
    				}
    			}
    		}
        }
    }
    return $cate;
}

//将 xml数据转换为数组格式。
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		for($i = 0; $i < $count; $i++){
		$subxml= $matches[2][$i];
		$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}

function de($format, $time) {
	$weekarray=array("日","一","二","三","四","五","六");
	return "星期".$weekarray[date('w', $time)];
}

function utf_substr($str, $start=0, $length=NULL,$encoding = 'utf-8')
{
	if(is_string($str)){
		$str = mb_substr($str, $start, $length, $encoding);
		return $str;
	}
}
