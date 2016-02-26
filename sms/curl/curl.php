<?php
class curlClass{
	function curl_send($str){
			$cu = curl_init("http://smsapi.dotname.co.kr/index.php");
			curl_setopt($cu, CURLOPT_SSL_VERIFYHOST,  0);
			curl_setopt($cu, CURLOPT_SSL_VERIFYPEER,  0);
			curl_setopt($cu, CURLOPT_POST,        1);
			curl_setopt($cu, CURLOPT_POSTFIELDS,    $str);
			curl_setopt($cu, CURLOPT_TIMEOUT,      100);

			ob_start();
			curl_exec($cu);
			$result=ob_get_contents();
			ob_end_clean();
			curl_close ($cu);
			return $result;
	}

function call_https($buffer)
{
	return $buffer;
}

}
?>