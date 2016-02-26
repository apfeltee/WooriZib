<?php

class curlClass{
	function curl_send($str){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://smsapi.dotname.co.kr/index.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		curl_exec($ch);
		$result=ob_get_contents();
		ob_end_clean();
		curl_close ($ch);
		return $result;
	}

	function call_https($buffer)
	{
		return $buffer;
	}

}
?>