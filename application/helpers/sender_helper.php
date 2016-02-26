<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sms_send($dest, $msg, $src="") {

		$CI = get_instance();
		$CI->load->model("Mconfig");
		$config = $CI->Mconfig->get("sms");
		//return false;
		$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
		$sms['user_id'] = base64_encode($config->sms_id); //SMS 아이디.
		$sms['secure'] = base64_encode($config->sms_key) ;//인증키
		$sms['msg'] = base64_encode(stripslashes($msg));

		$sms['rphone'] = base64_encode($dest);

		if($src==""){
			$sms['sphone1'] = base64_encode("010");
			$sms['sphone2'] = base64_encode("8931");
			$sms['sphone3'] = base64_encode("0184");
		} else {
			$src = str_replace("-","",$src);
			$c = strlen($src);

			if($c>7){
				$sms["sphone1"] = base64_encode(substr($src,0,3));
				$sms["sphone2"] = base64_encode(substr($src,3,4));
				$sms["sphone3"] = base64_encode(substr($src,7,$c-7));
			} else {
				exit;	
			}
		}

		$sms['rdate'] = base64_encode("");
		$sms['rtime'] = base64_encode("");
		$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
		$sms['returnurl'] = base64_encode("");
		$sms['testflag'] = base64_encode("");//실제 사용 시 빈 값
		$sms['destination'] = "";
		$returnurl = "";
		$sms['repeatFlag'] = base64_encode("");
		$sms['repeatNum'] = base64_encode("");
		$sms['repeatTime'] = base64_encode("");
		$nointeractive = "1"; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
		$host_info = explode("/", $sms_url);
		$host = $host_info[2];
		$path = $host_info[3];
		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		$data = "";
		foreach($sms AS $index => $value){
			$data .="--$boundary\r\n";
			$data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
			$data .= "\r\n".$value."\r\n";
			$data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

		$fp = fsockopen($host, 80);

		if ($fp) { 
			fputs($fp, $header.$data);
			$rsp = '';
			while(!feof($fp)) { 
				$rsp .= fgets($fp,8192); 
			}
			fclose($fp);
			$msg = explode("\r\n\r\n",trim($rsp));
			$rMsg = explode(",", $msg[1]);
			$Result= $rMsg[0]; //발송결과
			$Count= $rMsg[1]; //잔여건수
			//발송결과 알림

			if($Result=="success") {
				$alert = "성공";
				$alert .= " 잔여건수는 ".$Count."건 입니다.";
			}
			else if($Result=="reserved") {
				$alert = "성공적으로 예약되었습니다.";
				$alert .= " 잔여건수는 ".$Count."건 입니다.";
			}
			else if($Result=="3205") {
				$alert = "잘못된 번호형식입니다.";
			}
			else if($Result=="0044") {
				$alert = "스팸문자는발송되지 않습니다.";
			}
			else {
				$alert = "[Error]".$Result;
			}
		}
		else {
			$alert = "Connection Failed";
		}

		$result = "1";

		if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
			$result = "2";
		}
		else if($nointeractive!="1") {
			$result = "1";
		}

		return $result;
} 

function pw_send($sender_email,$sender, $email, $pw){
	$CI =& get_instance();
    $CI->load->library('email');
	$CI->email->set_mailtype("html");
	$CI->email->from($sender_email, $sender);
	$CI->email->to($email); 
	$msg = "";

	$CI->email->subject('초기 비밀번호입니다.');
	$msg = $msg . "초기화된 비밀번호는 " . $pw . " 입니다. <br>로그인하신 후 비밀번호를 변경하여 사용해 주세요.";
	
	$CI->email->message($msg); 
	$CI->email->send();
}

/**
 * SMS, LMS 발송
 *
 * $caller(발신자)
 * $sms_to(수신자) : Array
 * $subject(제목) : LMS, MMS 이용시 사용가능
 * $type(발 송 타 입) : 
 *  -1 : config.php 설정된 값을 그대로 설정
 *  A : SMS만 허용(80바이트 넘으면 수신 불가)
 *  B : SMS만 허용(80바이트 넘으면 나누어서 전송)
 *  C : LMS 허용
 *  D : MMS 허용
 */
function sms($sms_from, $sms_to, $subject="제목없음", $msg, $type="A"){
	
	$sms_from = "010-8931-0184";

	$CI = get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get("sms_cnt");

	if($config->sms_cnt < count($sms_to)){
		return "현재 SMS 잔여건수가 부족합니다.";
		exit;
	}
	
	include_once HOME."/sms/class/json.class.php";
	include_once HOME."/sms/config.php";
	include_once HOME."/sms/class/now_sms_send.php";
	include_once HOME."/sms/curl/curl.php";
	include_once HOME."/sms/class/result_code.php";

	$data = new now_sms_send;

	$html_type = 1; //단문형식:0, html형식:1

	$rs = $data->set($sms_from, $sms_to, $msg, 1, $subject, $type);

	if($rs[0]==true){
		return $data->send();
	}else{
		return $rs[1];
	}
}

/**
 * MMS 발송
 * 이미지 최적 사이즈 : 176 x 144, 160 x 120
 */
function mms($sms_from, $sms_to, $subject="제목없음", $msg, $filename){

	$sms_from = "010-8931-0184";

	$CI = get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get("sms_cnt");

	if($config->sms_cnt < count($sms_to)){
		return "현재 SMS 잔여건수가 부족합니다.";
		exit;
	}

	include_once HOME."/sms/class/json.class.php";
	include_once HOME."/sms/config.php";
	include_once HOME."/sms/class/now_mms_send.php";
	include_once HOME."/sms/curl/mms_curl.php";
	include_once HOME."/sms/class/result_code.php";

	$data = new now_mms_send;

	$html_type = 0; //단문형식:0, html형식:1

	$rs = $data->set($sms_from, $sms_to, $msg, 1, $filename, $subject, "D");

	if($rs[0]==true){
		return $data->send();
	}else{
		return $rs[1];
	}
}

/**
 * SMS, LMS 예약발송
 *
 * $date(예약시간) Y-m-d H:i:s
 */
function sms_rv($sms_from, $sms_to, $subject="제목없음", $msg, $date, $type="A"){

	$sms_from = "010-8931-0184";

	$CI = get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get("sms_cnt");

	if($config->sms_cnt < count($sms_to)){
		return "현재 SMS 잔여건수가 부족합니다.";
		exit;
	}
	
	include_once HOME."/sms/class/json.class.php";
	include_once HOME."/sms/config.php";
	include_once HOME."/sms/class/reservation_sms_send.php";
	include_once HOME."/sms/curl/curl.php";
	include_once HOME."/sms/class/result_code.php";

	$data = new reservation_sms_send;

	$html_type = 1; //단문형식:0, html형식:1	

	$year = date("Y",strtotime($date));//년
	$month = date("m",strtotime($date));//월
	$day = date("d",strtotime($date));//일
	$c = date("H",strtotime($date));//시
	$m = date("i",strtotime($date));//분
	$s = "00";//초

	$reservation = mktime($c,$m,$s,$month,$day,$year);

	$rs = $data->set($sms_from, $sms_to, $msg, 1, $reservation , $subject, $type);

	if($rs[0]==true){
		return $data->send();
	}else{
		return $rs[1];
	}
}

/**
 * MMS 예약발송
 *
 * $date(예약시간) Y-m-d H:i:s
 */
function mms_rv($sms_from, $sms_to, $subject="제목없음", $msg, $filename, $date){

	$sms_from = "010-8931-0184";

	$CI = get_instance();
	$CI->load->model("Mconfig");
	$config = $CI->Mconfig->get("sms_cnt");

	if($config->sms_cnt < count($sms_to)){
		return "현재 SMS 잔여건수가 부족합니다.";
		exit;
	}
	
	include_once HOME."/sms/class/json.class.php";
	include_once HOME."/sms/config.php";
	include_once HOME."/sms/class/reservation_mms_send.php";
	include_once HOME."/sms/curl/mms_curl.php";
	include_once HOME."/sms/class/result_code.php";

	$data = new reservation_mms_send;

	$html_type = 1; //단문형식:0, html형식:1	

	$year = date("Y",strtotime($date));//년
	$month = date("m",strtotime($date));//월
	$day = date("d",strtotime($date));//일
	$c = date("H",strtotime($date));//시
	$m = date("i",strtotime($date));//분
	$s = "00";//초

	$reservation = mktime($c,$m,$s,$month,$day,$year);

	$rs = $data->set($sms_from, $sms_to, $msg, 1, $filename, $reservation,$subject , "D");

	if($rs[0]==true){
		return $data->send();
	}else{
		return $rs[1];
	}
}