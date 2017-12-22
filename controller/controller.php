<?php
/**
 *实现功能类
 *
 *@author: Ping
 */
class controller
{
	//对请求报文做拼接和生成签名
	static function getSignTrue( $Arr, $key )
	{
		if( !empty($Arr) ) {
			ksort($Arr);    
			$str = '';
			foreach( $Arr as $k => $v) {
				if( $v == '' || $k == 'signature') {
					continue;
				}
				$str .= $k.'='.$v.'&';
			}
			if( $key == 'post' ) {
				return substr($str, 0, -1);
			} else {
				return strtolower(md5($str.md5($key)));
			}
		}
		return false;
	}
	//发送post请求
	static function post($url, $data)
	{
		$curl = curl_init(); // 启动一个CURL会话
	    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
	    //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
	    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	    //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	    curl_setopt($curl, CURLOPT_TIMEOUT, 40); // 设置超时限制防止死循环
	    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	    $tmpInfo = curl_exec($curl); // 执行操作
	    if (curl_errno($curl)) {
	       return 'Errno'.curl_error($curl);//捕抓异常
	    }
	    curl_close($curl); // 关闭CURL会话
	    return $tmpInfo; // 返回数据
	}
	static function log($path, $Arr)
	{
		if( !$query = self::getSignTrue($Arr, 'post') ) {
			return false;
		}
		$str  = date('Y-m-d H:i:s');
		$str .= ': appid=';
		$str .= $Arr['appId'];
		$str .= ',交易金额=';
		$str .= $Arr['mhtOrderAmt'];
		$str .= ',请求报文：';
		$str .= $query;
		$str .= "\r\n";
		$fileName = $path.date('Ymd').'.log';
		file_put_contents($fileName, $str, FILE_APPEND);
	}
}