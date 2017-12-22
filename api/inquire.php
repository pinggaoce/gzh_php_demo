<?php
/**
 * @author Ping
 *
 * 用于查询订单的状态
 */
	include_once __DIR__.'\..\conf\config.php';
	include_once __DIR__.'\..\controller\controller.php';

	$req['appId'] = APPID;
	$req['funcode'] = 'MQ002';
	$req['mhtOrderNo'] = 'gzh_2390020171222160155';
	$req['mhtCharset'] = CHARSET;
	$req['deviceType'] = DEVICE_TYPE;
	$req['mhtSignType'] = SIGN_TYPE;
	$req['version'] = '1.0.0';
	$req['mhtSignature'] = controller::getSignTrue($req, KEY);
	
	$data = controller::getSignTrue($req, 'post');
	$res = file_get_contents(TRADE_URL.'?'.$data);
	$Arr = array();
	$res = explode('&', $res);
	foreach($res as $v) {
		$value = explode('=', $v);
		$Arr[$value[0]] = urldecode($value[1]);
	}
	
	$signatrue = controller::getSignTrue($Arr, KEY);
	
	if($signatrue == $Arr['signature']) {
		echo '验签通过，订单状态码：'.$Arr['transStatus'];
	} else {
		echo '验签失败,请重试';
	}