<?
	class reservation_mms_send{
		var $caller;
		var $toll;
		var $msg;
		var $type;
		var $html;
		var $subject;
		var $mms_file;
		var $reservation;
		var $set_ok;

		function set($caller, $toll, $msg, $html, $mms_file,$reservation ,$subject , $type = -1 ){
		
			if(is_array($toll)){
				if(sizeof($toll) == 1) $toll = $toll[0];
			}else{
				$toll = explode(",",$toll);
				if(sizeof($toll) == 1) $toll = $toll[0];
			}
			
			$result = array();
			if($this->char_filter($caller)==false){
				$result[0] = false;
				$result[1] = "발신자 전화번호는 숫자 데이터만 가능합니다.";
				$this->set_ok = 0;
				return $result;
				exit;
			}else if($this->char_filter($toll)==false){
				$result[0] = false;
				$result[1] = "수신자 전화번호는 숫자 데이터만 가능합니다.";
				$this->set_ok = 0;
				return $result;
				exit;
			}

			if(file_exists("./mms_file/".$mms_file)==false){
				$result[0] = false;
				$result[1] = "파일이 존재 하지 않습니다.";
				$this->set_ok = 0;
				return $result;
				exit;
			}
			
			if(filesize("./mms_file/".$mms_file) > 1048576){
				$result[0] = false;
				$result[1] = "첨부파일의 사이즈는 1MB 미만만 가능합니다.";
				$this->set_ok = 0;
				return $result;
				exit;
			}
			
			$file_name_ext = substr($mms_file,strrpos($mms_file,"."));
			//철환수정
			if($file_name_ext!=".jpg" && $file_name_ext!=".JPG" && $file_name_ext!=".jpeg" && $file_name_ext!=".JPEG"
			 && $file_name_ext!=".jpe" && $file_name_ext!=".JPE" && $file_name_ext!=".jfif" && $file_name_ext!=".JFIF"){
				$result[0] = false;
				$result[1] = "파일 형식은 jpg 만 가능합니다.";
				$this->set_ok = 0;
				return $result;
				exit;
				
			}
				

			$this->mms_file = $mms_file;

			if($type == -1){
				$this->type = -1;
			}else{
				if(strtoupper($type)=="A" || strtoupper($type)=="B" || strtoupper($type)=="C" || strtoupper($type)=="D" || strtoupper($type)=="-1"){
					$this->type = strtoupper($type);
				}else{
					$result[0] = false;
					$result[1] = "TYPE 데이터는 'A', 'B', 'C', 'D' '-1' 데이터만 사용 가능합니다.";
					$this->set_ok = 0;
					return $result;
				}
			}

			if($html==1){
				$this->html = 1;
			}else{
				$this->html = "x";
			}

			if($this->char_filter(trim($reservation))==false){
				$result[0] = false;
				$result[1] = "날짜 형식이 잘못 되었습니다.";
				$this->set_ok = 0;
				return $result;
				exit;
			}else{
				$this->reservation = trim($reservation);
			}


			$this->caller = trim(str_replace("-","",$caller));
			if(is_array($toll)){
				foreach($toll as $key => $val){
					$toll[$key] = trim(str_replace("-","",$val));
				}
				$this->toll = $toll;
			}else{
				$this->toll = trim(str_replace("-","",$toll));
			}

			$this->subject = trim($subject);
			$this->msg = trim($msg);
			$this->set_ok = 1;
			
			$result[0] = true;
			$result[1] = "데이터가 Set 되었습니다. send() 함수를 실행시켜 SMS를 발신 하세요. ";
			return $result;
		}

		function send(){
			if($this->set_ok==0){
				$tmp = array("result"=>0,"msg"=>"데이터가 Set 되지 못하였습니다. 데이터를 다시 한번 확인해 주세요.");
				return $tmp;
				exit;
			}

			$json = new Services_JSON();
			$base = new base_data;
			$curl_send = new curlClass;
			$result_code = new result;
			$id = $base->smsHosting_id;
			$pw = $base->smsHosting_pw;

			if($this->type==-1){
				$this->type = $base->type;
			}

			$str = array();
			$str['data'] = $json->encode(array("id"=>$id, 
				"pw"=>md5(trim($pw)),
				"code"=>"2658",
				"type"=>$this->type,
				"caller"=>$this->caller,
				"toll"=>$this->toll,
				"reservation"=>$this->reservation,
				"html"=>$this->html));
			$str['msg'] = $this->msg;
			$str['file_box'] = "@./mms_file/".trim($this->mms_file);
			$str['subject'] = $this->subject;
			$result = $curl_send->curl_send($str);
			
			if(strstr($result, "#")){
				return $result_code->error_result($result);
			}else if(strstr($result, "@")){
				return $result_code->normal_result($result);
			}
		}

		function char_filter($data){
			if(is_array($data)){
				foreach($data as $key => $val){
					$val = trim(str_replace("-","",$val));
					$val_len = strlen($val);
					for($i=0;$i<$val_len;$i++){
						if(!(ord(substr($val, $i, 1))>47 && ord(substr($val, $i, 1))<58)){
							return false;
							exit;
						}
					}
				}
				return true;
			}else{
				$data = trim(str_replace("-","",$data));
				$data_len = strlen($data);
				for($i=0;$i<$data_len;$i++){
					if(!(ord(substr($data, $i, 1))>47 && ord(substr($data, $i, 1))<58)){
						return false;
						exit;
					}
				}
				return true;
			}
		}
	}
?>