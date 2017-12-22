<?php
/**
 * @author Ping
 *
 * 用于接收异步通知
 */
	include_once __DIR__.'\..\conf\config.php';
	include_once __DIR__.'\..\controller\controller.php';

	$res = file_get_contents('php://input');
	$Arr = array();
	$res = explode('&', $res);
	foreach($res as $v) {
		$value = explode('=', $v);
		$Arr[$value[0]] = urldecode($value[1]);
	}
	
	$signatrue = controller::getSignTrue($Arr, KEY);
	
	if($signatrue == $Arr['signature']) {
		//异步通知验签通过，在此处写业务逻辑
		echo 'success=Y';
	} else {
		//异步通知验签失败，在此处写业务逻辑
		echo 'success=Y';
	}