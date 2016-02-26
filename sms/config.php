<?php
/****************************************************************/
//	*닷네임코리아(dotname.co.kr) SMS HOSTING API 방식 프로세서* 
//																
//	SMS 호스팅 관리자 페이지를 통해 허용 가능한 IP 에서만		
//	API 모듈을 사용 하실수 있습니다.							
//																
//	소스 상단에 있는 $smsHosting_id 변수에 SMS 호스팅 ID 넣어주세요.
//	소스 상단에 있는 $smsHosting_pw 에는 패스워드를 입력합니다.
//
// type 변수에 설정.
// A : SMS만 허용(80바이트 넘으면 수신 불가)
// B : SMS만 허용(80바이트 넘으면 나누어서 전송)
// C : LMS 허용
// D : MMS 허용
//	
/****************************************************************/

class base_data{
	var $smsHosting_id="dungzi";	//SMS HOSTING ID
	var $smsHosting_pw="gkdla0915";	//SMS HOSTING PASSWORD
	var $type = "C"; //SMS TYPE 기본적으로 셋팅되는 값 입니다.
}
?>