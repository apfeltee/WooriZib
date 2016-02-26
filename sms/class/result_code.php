<?php
class result{
	function normal_result($code){
		$code = trim($code);
		if($code=="@1"){
			//정상적으로 통신사 요청완료
			$tmp = ("발송성공");
			return ($tmp);
			exit;
		}
	}

	function error_result($code){
		$code = trim($code);
		if($code=="#1"){
			$tmp = ("ID랑 비밀번호가 일치하지 않음.");
			return ($tmp);
			exit;
		}
		if($code=="#2"){
			$tmp = ("고객 정보를 불러오지 못했습니다. 닷네임코리아에 문의 해주세요.");
			return ($tmp);
			exit;
		}
		if($code=="#3"){
			$tmp = ("허용되지 않은 IP 입니다. SMS 관리자를 통해 IP를 등록해 주시기 바랍니다.");
			return ($tmp);
			exit;
		}
		if($code=="#4"){
			$tmp = ("SMS 관리자 페이지를 통하여 API 허용을 설정해주세요.");
			return ($tmp);
			exit;
		}
		if($code=="#5"){
			$tmp = ("발신자 전화번호는 숫자 데이터만 가능합니다.");
			return ($tmp);
			exit;
		}
		if($code=="#6"){
			$tmp = ("수신자 전화번호는 숫자 데이터만 가능합니다.");
			return ($tmp);
			exit;
		}
		if($code=="#7"){
			$tmp = ("메세지 데이터가 없습니다.");
			return ($tmp);
			exit;
		}
		if($code=="#8"){
			$tmp = ("타입값이 잘못 되었습니다.");
			return ($tmp);
			exit;
		}
		if($code=="#9"){	
			$tmp = ("A 타입에는 메세지 80 byte를 넘을 수 없습니다. 타입값을 확인하세요.");
			return ($tmp);
			exit;
		}
		if($code=="#10"){
			$tmp = ("포인트 차감 에러 닷네임에 문의주세요.");
			return ($tmp);
			exit;
		}
		if($code=="#11"){
			$tmp = ("고객 SMS_NUMBER 정보를 가져오지 못하였습니다. 닷네임코리아에 문의 해주세요.");
			return ($tmp);
			exit;
		}
		if($code=="#12"){
			$tmp = ("포인트 부족");
			return ($tmp);
			exit;			
		}
		if($code=="#13"){
			$tmp = ("날짜 형식 오류");
			return ($tmp);
			exit;
		}
		if($code=="#14"){
			$tmp = ("예약전송할 날짜 데이터가 없습니다.");
			return ($tmp);
			exit;
		}
		if($code=="#15"){
			$tmp = ("이미지 파일이 존재하지 않거나, sms 서버로 복사 시도에 실패 하였습니다.");
			return ($tmp);
			exit;
		}
		if($code=="#16"){
			$tmp = ("mms 이미지 파일 용량은 1MB 이하만 가능합니다.");
			return ($tmp);
			exit;
		}
		if($code=="#17"){
			$tmp = ("SMS 관리자 페이지를 통하여 웹방식 허용을 설정해주세요.");
			return ($tmp);
			exit;
		}
		if($code=="#18"){
			$tmp = ("메세지는 2000 byte를 넘을 수 없습니다.");
			return ($tmp);
			exit;
		}
	}
}

?>