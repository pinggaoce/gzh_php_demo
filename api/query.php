<?php
/**
 * @author Jupiter
 *
 * 用于对支付信息进行重组和签名，并将请求发往现在支付
 */
	include_once __DIR__.'\..\conf\config.php';
	include_once __DIR__.'\..\controller\controller.php';
	//include_once __DIR__.'\..\controller\log.php';
	
	if (function_exists("date_default_timezone_set")) {
        date_default_timezone_set(TIMEZONE);
    }
	
	if( !empty($_POST) ) {
		if( trim($_POST['mhtOrderName'])   == '' || 
			trim($_POST['mhtOrderAmt'])    == '' ||
			trim($_POST['mhtOrderDetail']) == '' ) {
			die('必传参数不能为空');
		} else {
			$mhtOrderName   = $_POST['mhtOrderName'];
			$mhtOrderAmt    = $_POST['mhtOrderAmt'];
			$mhtOrderDetail = $_POST['mhtOrderDetail'];
		} 
	} else {
		die('请上传参数');
	}

	$req['appId'] = APPID;
	$req['funcode'] = 'WP001';
	$req['mhtOrderNo'] = 'gzh_'.rand().date('YmdHis');
	$req['mhtOrderName'] = $mhtOrderName;
	$req['mhtOrderType'] = '01';
	$req['mhtCurrencyType'] = CURRENCYTYPE;
	$req['mhtOrderAmt'] = $mhtOrderAmt;
	$req['mhtOrderDetail'] = $mhtOrderDetail;
	$req['mhtOrderTimeOut'] = TIMEOUT;
	$req['mhtOrderStartTime'] = date('YmdHis');
	$req['notifyUrl'] = NOTIFY_URL;
	$req['frontNotifyUrl'] = FRONT_URL;
	$req['mhtCharset'] = CHARSET;
	$req['deviceType'] = DEVICE_TYPE;
	$req['payChannelType'] = '13';   //微信13    支付宝12
	$req['mhtReserved'] = 'test';
	$req['mhtSignType'] = SIGN_TYPE;
	$req['version'] = '1.0.0';
	$req['outputType'] = '0';    //0 直接调起    1 返回支付凭证
	$req['mhtSignature'] = controller::getSignTrue($req, KEY);
	//$req["mhtSubAppId"]=" ";//微信1模式必送
	//$req["consumerId"]=" ";//微信1模式必送
	
	//TRADE_URL.'?'.SignTrue::createStr($req);
	$data = controller::getSignTrue($req, 'post');
	header('location:'.TRADE_URL.'?'.$data));
	// $res = controller::post(TRADE_URL,$data);
	//echo $res;