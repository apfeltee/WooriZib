<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends CI_Controller {

	
	/**
	 * 회원 로그인
	 */
	public function signin(){

		if($this->session->userdata("id")!=""){
			redirect("/","refresh");
			exit;
		}

		$data["page_title"] =  "회원로그인";
		$this->layout->view('basic/member_signin',$data);	
	}

	public function signup_pre(){

		$this->layout->view('basic/member_signup_pre');
	}

	/**
	 * 회원 가입
	 */
	public function signup($type=""){

		if($this->session->userdata("id")!=""){
			redirect("/","refresh");
			exit;
		}

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$type = (!$type) ? $config->MEMBER_TYPE : $type;
		$type = ($type=="both") ? "general" : $type;

		$data["type"] = $type;
		$data["page_title"] =  "회원가입";
		$data["real_name"] = $this->input->post("real_name");

		if($config->CP_CODE || $config->IPIN_CODE){
			if(!$data["real_name"]){
				$data["enc_data"] = "";
				redirect("member/checkplus","refresh");
				exit;
			}
		}

		$this->layout->view('basic/member_signup',$data);
	}

	/**
	 * 실명인증 페이지
	 */
	public function checkplus(){
		$data["page_title"] =  "회원가입";
		$data["real_name"] = "";

		$data["enc_data"] = $this->input->post("EncodeData");
		$sReserved1 = $this->input->post("param_r1");
		$sReserved2 = $this->input->post("param_r2");
		$sReserved3 = $this->input->post("param_r3");

		if($data["enc_data"]){//인증체크 페이지에서 넘어 왔을 경우 실명정보 추출
			$data["real_name"] = $this->get_checkplus_info($data["enc_data"],$sReserved1,$sReserved2,$sReserved3);
		}
		else{//인증체크 진행
			$data["enc_data"] = $this->cp_check();
		}

		$this->layout->view('basic/member_checkplus',$data);
	}

	/**
	 * 휴대폰 실명인증체크
	 */
	public function cp_check(){
		
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$sitecode = $config->CP_CODE;// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $config->CP_PASSWORD;// NICE로부터 부여받은 사이트 패스워드		
		
		$authtype = "";// 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
			
		$popgubun 	= "N";//Y : 취소버튼 있음 / N : 취소버튼 없음
		$customize 	= "";//없으면 기본 웹페이지 / Mobile : 모바일페이지			
			 
		$reqseq = "REQ_0123456789";// 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
		
		$reqseq = get_cprequest_no($sitecode);
		
		// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
		$returnurl = "http://".HOST."/member/checkplus";	// 성공시 이동될 URL
		$errorurl = "http://".HOST."/member/checkplus";	// 실패시 이동될 URL
		
		// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.		
		$this->session->set_userdata("REQ_SEQ",$reqseq);

		// 입력될 plain 데이타를 만든다.
		$plaindata ="7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
					"8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
					"9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
					"7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
					"7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
					"11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
					"9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

		$enc_data = get_encode_data($sitecode, $sitepasswd, $plaindata);

		return $enc_data;
	}

	/**
	 * 실명인증 반환 정보
	 */
	function get_checkplus_info($enc_data,$sReserved1,$sReserved2,$sReserved3){
		
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$sitecode = $config->CP_CODE;// NICE로부터 부여받은 사이트 코드
		$sitepasswd = $config->CP_PASSWORD;// NICE로부터 부여받은 사이트 패스워드

		$name = "";

		//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
		if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {echo "입력 값 확인이 필요합니다 : ".$match[0]; exit;} // 문자열 점검 추가. 
		if(base64_encode(base64_decode($enc_data))!=$enc_data) {echo "입력 값 확인이 필요합니다"; exit;}
		
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {/*echo "문자열 점검 : ".$match[0];*/ exit;}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////
			
		if ($enc_data != "") {

			$plaindata = get_decode_data($sitecode, $sitepasswd, $enc_data);// 암호화된 결과 데이터의 복호화

			$plaindata = iconv("EUC-KR","UTF-8", $plaindata);
			//echo "[plaindata]  " . $plaindata . "<br>";

			if ($plaindata == -1){
				$returnMsg  = "암/복호화 시스템 오류";
			}else if ($plaindata == -4){
				$returnMsg  = "복호화 처리 오류";
			}else if ($plaindata == -5){
				$returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
			}else if ($plaindata == -6){
				$returnMsg  = "복호화 데이터 오류";
			}else if ($plaindata == -9){
				$returnMsg  = "입력값 오류";
			}else if ($plaindata == -12){
				$returnMsg  = "사이트 비밀번호 오류";
			}else{
				// 복호화가 정상적일 경우 데이터를 파싱합니다.  
				$requestnumber = $this->GetValue($plaindata , "REQ_SEQ");
				//$responsenumber = $this->GetValue($plaindata , "RES_SEQ");
				//$authtype = $this->GetValue($plaindata , "AUTH_TYPE");
				$name = $this->GetValueName($plaindata , "NAME");
				//$birthdate = $this->GetValue($plaindata , "BIRTHDATE");
				//$gender = $this->GetValue($plaindata , "GENDER");
				//$nationalinfo = $this->GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
				//$dupinfo = $this->GetValue($plaindata , "DI");
				//$conninfo = $this->GetValue($plaindata , "CI");

				if(strcmp($this->session->userdata("REQ_SEQ"), $requestnumber) != 0){
					//echo "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.<br>";
					$requestnumber = "";
					$responsenumber = "";
					$authtype = "";
					$name = "";
					$birthdate = "";
					$gender = "";
					$nationalinfo = "";
					$dupinfo = "";
					$conninfo = "";
				}
			}
		}
		return $name;
	}

	/**
	 * encode문제와 글씨 잘림현상이 나타나 GetValue를 사용하지 않고 이름만 추출 하도록 새로 만듬
	 */
	function GetValueName($str, $name){
		$str = explode(":",$str);
		$new_str = array();
		foreach($str as $key=>$val){
			$str[$key] = substr($val,0,-1);
			if(isset($str[$key+1])){
				$new_str[$str[$key]] = substr($str[$key+1],0,-1);
			}
		}
		return $new_str["NAME"];
	}

	/**
	 * NICE신용정보꺼 문제 있음
	 */
    function GetValue($str , $name){
        $pos1 = 0;  //length의 시작 위치
        $pos2 = 0;  //:의 위치

        while( $pos1 <= strlen($str) ){
            $pos2 = strpos( $str , ":" , $pos1);
            $len = substr($str , $pos1 , $pos2 - $pos1);
            $key = substr($str , $pos2 + 1 , $len);
            $pos1 = $pos2 + $len + 1;
            if( $key == $name ){
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $value = substr($str , $pos2 + 1 , $len);
                return $value;
            }
            else{
                // 다르면 스킵한다.
                $pos2 = strpos( $str , ":" , $pos1);
                $len = substr($str , $pos1 , $pos2 - $pos1);
                $pos1 = $pos2 + $len + 1;
            }            
        }
    }

	/**
	 * 회원 로그인 체크
	 */
	public function get(){
		$this->load->model("Mmember");

		if($this->session->userdata("id")!=""){
			echo json_encode($this->Mmember->get_array($this->session->userdata("id")));
		}
	}

	/**
	 * 이메일 가입 여부 확인
	 */
	function check_email(){
		$email = $this->input->post("suEmail");
		$this->load->model("Mmember");
		if($this->Mmember->have_email($email)){
			echo "false";
		} else {
			echo "true";		
		}
	}

	/**
	 * 회원 로그인
	 */
	public function signin_action(){
		$pw = $this->_prep_password($this->input->post("siPw"));
		$this->load->model("Mmember");
		$result = $this->Mmember->check_login($this->input->post("siEmail"),$pw);
		if($result!=null){

			if($result->valid=='N'){
				echo "2";
				exit;
			}

			if($result->permit_ip){
	
				$ip = explode("\n", $result->permit_ip);

				$ip_check = false;

				foreach($ip as $val){
					if(preg_match("/".trim($val)."/", $_SERVER['REMOTE_ADDR'])){
						$ip_check = true;
					}				
				}

				if(!$ip_check){
					echo "3";
					exit;
				}
			}

			if($result->expire_date){
				if(str_replace("-","",$result->expire_date) < date("Ymd")){
					echo "4";
					exit;
				}
			}

			$this->session->set_userdata("email",$result->email);
			$this->session->set_userdata("id",$result->id);
			$this->session->set_userdata("type",$result->type);
			$this->session->set_userdata("biz_name",$result->biz_name);
			$this->session->set_userdata("biz_auth",$result->biz_auth);
			$this->session->set_userdata("name",$result->name);
			$this->session->set_userdata("phone",$result->phone);
			$this->session->set_userdata("tel",$result->tel);
			$this->session->set_userdata("kakao",$result->kakao);
			$this->session->set_userdata("permit_area",$result->permit_area);
			$this->session->set_userdata("timeout",time());	/* 자동로그아웃을 구현하기 위해서 추가한 세션 */
			
			//비회원으로 등록된 관심매물이 있으면 회원매물로 전환한다.			
			$this->load->model("Mhope");
			$this->Mhope->convert($result->id, $this->session->userdata("session_id"));
			echo "1";
		} else {
			//해당 사용자가 없으면 이메일이 존재하는지 검사한다.
			$cnt = $this->Mmember->have_email($this->input->post("siEmail"));
			if($cnt==1){
				echo "0";
			} else {
				echo "9";
			}
		}
	}

	/**
	 * 회원 가입
	 */
	public function signup_action(){

		$this->load->model("Mmember");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		if($this->session->userdata("id")!=""){
			echo "1";
			exit;
		}

		//email 유효성체크
		if($this->input->post("suEmail")){
			$cnt = $this->Mmember->have_email($this->input->post("suEmail"));
			if($cnt){
				echo "1";
				exit;
			}
		}
		else{
			echo "1";
			exit;
		}

		//SMS 인증번호 유효성 체크
		if($config->MEMBER_PHONE_CONFIRM){
			$this->load->model("Msmshistory");
			$query = $this->Msmshistory->get_last($this->input->post("suPhone"));
			if($query->msg != $this->input->post("sms_confirm_num")){
				echo '3';
				exit;
			}
		}

		/*** biz_auth의 값이 없을 경우에는 0을 넣는다. ***/
		$param = Array(
			"type"		=> $this->input->post("type"),
			"email"		=> $this->input->post("suEmail"),
			"name"		=> $this->input->post("suName"),
			"pw"		=> $this->_prep_password($this->input->post("suPw")),
			"phone"		=> $this->input->post("suPhone"),
			"biz_name"	=> ($this->input->post("biz_name")) ? $this->input->post("biz_name") : "",
			"biz_ceo"	=> ($this->input->post("biz_ceo")) ? $this->input->post("biz_ceo") : "",
			"biz_auth"	=> ($this->input->post("biz_auth")) ? $this->input->post("biz_auth") : "0", 
			"biz_num"	=> ($this->input->post("biz_num")) ? $this->input->post("biz_num") : "",
			"re_num"	=> ($this->input->post("re_num")) ? $this->input->post("re_num") : "",
			"tel"		=> ($this->input->post("tel")) ? $this->input->post("tel") : "",
			"address"	=> ($this->input->post("address")) ? $this->input->post("address") : "",
			"address_detail" => ($this->input->post("address_detail")) ? $this->input->post("address_detail") : "",
			"kakao"		=> ($this->input->post("kakao")) ? $this->input->post("kakao") : "",
			"date"		=> date('Y-m-d H:i:s')
		);

		if($config->MEMBER_APPROVE){
			$param['valid'] = "N";
			$id = $this->Mmember->insert($param);
			echo '2';
			exit;
		}
		else{
			$id = $this->Mmember->insert($param);
		}

		if($id!=""){

			//SMS전송
			if($config->sms_cnt){
				$this->load->helper("sender");
				$this->load->model("Msmshistory");

				$msg = $this->input->post("suName") . "님이 회원 가입하셨습니다.";
				$sms_result = sms($config->mobile,$config->mobile,"",$msg);

				if($sms_result=="발송성공"){						 
					$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - 1)),"");
				}
				$param = Array(
					"sms_from" => $config->mobile,
					"sms_to" => $config->mobile,
					"msg" => $msg,
					"type" => "A",
					"minus_count" => ($sms_result=="발송성공") ? 1 : 0,
					"result" => $sms_result,
					"page" => "signup",
					"date" => date('Y-m-d H:i:s')
				);
				$this->Msmshistory->insert($param);				
			}		

			//로그인 처리를 한다.
			$result = $this->Mmember->get($id);
			$this->session->set_userdata("email",$result->email);
			$this->session->set_userdata("id",$result->id);
			$this->session->set_userdata("type",$result->type);
			$this->session->set_userdata("biz_name",$result->biz_name);
			$this->session->set_userdata("biz_auth",$result->biz_auth);
			$this->session->set_userdata("name",$result->name);
			$this->session->set_userdata("phone",$result->phone);
			$this->session->set_userdata("tel",$result->tel);
			$this->session->set_userdata("kakao",$result->kakao);

			//비회원으로 등록된 관심매물이 있으면 회원매물로 전환한다.
			$this->load->model("Mhope");
			$this->Mhope->convert($id, $this->session->userdata("session_id"));

			echo "1";
		}
	}


	public function search(){
		$data["page_title"] =  "비밀번호 분실";
		$this->layout->view('basic/member_search',$data);
	}

	/**
	 * 비밀번호 찾기
	 */
	public function search_action(){
		$this->load->model("Mmember");
		
		$member = $this->Mmember->get_by_email($this->input->post("spEmail"));
		if($member!=null){
			//비밀번호 초기화
			$random_pw = substr($this->_prep_password(microtime()),0,10); 
			$param = Array(
				"pw" => $this->_prep_password($random_pw)
			);
			$this->Mmember->email_update($this->input->post("spEmail"),$param);
			
			//이메일 발송
			$this->load->model("Mconfig");
			$config = $this->Mconfig->get();
			$this->load->helper("sender");
			pw_send($config->email, $config->name, $this->input->post("spEmail"), $random_pw);
			echo "1";
		}
	}

	public function profile(){
		if($this->session->userdata("id")==""){
			redirect("member/signin","refresh");
			exit;
		}
		$data["page_title"] =  "회원정보 수정";
		$this->load->model("Mmember");
		$data["query"] = $this->Mmember->get($this->session->userdata("id"));
		$this->layout->view('basic/member_profile',$data);			
	}

	/**
	 * 회원 정보 수정
	 */
	public function profile_action(){
		$this->load->model("Mmember");
		
		if($this->session->userdata("id")!=""){

			//email 유효성체크
			if(!$this->input->post("prEmail")){
				echo json_encode(array("result"=>"0"));
				exit;
			}

			$param = Array(
				"email"		=> $this->input->post("prEmail"),
				"name"		=> $this->input->post("prName"),
				"phone"	=> $this->input->post("prPhone"),
				"biz_name"	=> ($this->input->post("biz_name")) ? $this->input->post("biz_name") : "",
				"tel"		=> ($this->input->post("tel")) ? $this->input->post("tel") : "",
				"address"	=> ($this->input->post("address")) ? $this->input->post("address") : "",
				"address_detail" => ($this->input->post("address_detail")) ? $this->input->post("address_detail") : "",
				"kakao"	=> ($this->input->post("kakao")) ? $this->input->post("kakao") : "",
				"moddate"	=> date('Y-m-d H:i:s')
			);

			if($this->input->post("prPw")!=""){
				$param["pw"] = $this->_prep_password($this->input->post("prPw"));
			}

			$config['upload_path'] = HOME.'/uploads/member';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);

			$member = $this->Mmember->get($this->session->userdata("id"));

			//프로필 사진
			$profile = "";
			if(!$this->upload->do_upload("profile")){
				$error = array('error' => $this->upload->display_errors());
			}
			else{
				$data = array('upload_data' => $this->upload->data());
				$profile = $data["upload_data"]["file_name"];
				$this->make_profile_thumb($data,100);
			}

			if($profile){
				$param["profile"] = $profile;
				@unlink($config['upload_path']."/".$member->profile);
			}

			$this->Mmember->update($this->session->userdata("id"), $param);
			echo json_encode(array("result"=>"1","profile"=>$profile));
		} else {
			echo json_encode(array("result"=>"0"));
		}
	}

	/**
	 *  회원 프로필사진 썸네일
	 */
	private function make_profile_thumb($data, $width=100){

		if($data["upload_data"]["image_width"] < $width){	
			$width = $data["upload_data"]["image_width"];
		}

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/member/'.$data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/member/'.$data["upload_data"]["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = "";
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
		$thumb_config['quality'] = "100%";

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);

		if ( ! $CI->image_lib->resize()){
			echo $CI->image_lib->display_errors();
			return "0";
		} else {
			return "1";
		}
	}

	/**
	 * 의뢰하기
	 */
	public function enquire($page=0){

		$this->load->model("Mconfig");
		$this->load->model("Menquire");

		$config = $this->Mconfig->get();

		$data["page_title"] =  "의뢰하기";
		$data["enGubun"] = (!is_numeric($page)) ? $page : "";

		$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();

		if($config->ENQUIRE_TYPE){

			$this->load->library('pagination');
			$page_config['base_url'] = "/member/enquire/";
			$page_config['total_rows'] = $this->Menquire->get_total_count("");
			$page_config['per_page'] = 15;
			$page_config['uri_segment'] = count($this->uri->segment_array());
			$page_config['first_link'] = '<<';
			$page_config['first_tag_open'] = '<li>';
			$page_config['first_tag_close'] = '</li>';

			$page_config['last_link'] = '>>';
			$page_config['last_tag_open'] = '<li>';
			$page_config['last_tag_close'] = '</li>';

			$page_config['num_tag_open'] = "<li>";
			$page_config['num_tag_close'] = "</li>";
			$page_config['cur_tag_open'] = '<li class="active"><a href="#">';
			$page_config['cur_tag_close'] = '</a></li>';

			$page_config['next_link'] = '>';
			$page_config['next_tag_open'] = '<li>';
			$page_config['next_tag_close'] = '</li>';

			$page_config['prev_link'] = '<';
			$page_config['prev_tag_open'] = '<li>';
			$page_config['prev_tag_close'] = '</li>';

			$data["query"] = $this->Menquire->get_list("", $page_config['per_page'], $page);

			foreach($data["query"] as $key=>$val){
				$data["query"][$key]["category_list"] = $this->Mcategory->get_list_multi($val["category"]);
			}

			$this->pagination->initialize($page_config);
			$data["pagination"] = $this->pagination->create_links();

			$this->layout->view('basic/member_enquire_list',$data);		
		}
		else{

			$this->layout->view('basic/member_enquire',$data);
		}
	}

	/**
	 * 의뢰등록 페이지
	 */
	public function enquire_add($enGubun=""){

		$data["page_title"] =  "의뢰하기";
		$data["enGubun"] =  $enGubun;

		$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();

		$this->layout->view('basic/member_enquire',$data);

	}

	/**
	 * 매도, 매수 문의
	 *
	 * 20141005 - 로그인한 회원의 경우에는 member_id에 값을 남기도록 추가함
	 */
	public function enquire_action(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();		

		$category = "";
		if(is_array($this->input->post("enCategory"))){
			$category = implode(",",$this->input->post("enCategory"));
		}
		$param = Array(
			"gubun"		=>$this->input->post("enGubun"),
			"name"		=>$this->input->post("enName"),
			"phone"		=>$this->input->post("enPhone"), 
			"location"	=>$this->input->post("enLocation"), 
			"visitdate"	=>$this->input->post("visitdate"), 
			"movedate"	=>$this->input->post("movedate"),
			"price"		=>$this->input->post("enPrice"), 
			"area"		=>$this->input->post("enArea"), 
			"type"		=>$this->input->post("enType"), 
			"category"	=>$category,
			"content"	=>$this->input->post("enContent"),
			"date"		=> date('Y-m-d H:i:s')
		);

		if($this->input->post("open")){
			$param["open"] = $this->input->post("open");
		}

		if($this->input->post("pw")){
			$param["pw"] = $this->_prep_password($this->input->post("pw"));
		}

		if($config->INSTALLATION_FLAG=="2"){
			$param["type"] = "installation";
		}
		$this->load->model("Menquire");
		$this->Menquire->add($param);

		$this->load->helper("sender");
		$gubun = "매도";
		if($this->input->post("enGubun")=="buy"){
			$gubun = "매수";
		}

		$this->load->model("Mmember");
		$member = $this->Mmember->get($config->site_admin);		

		if($member){
			$sms_to = $member->phone;			
		}
		else{
			$sms_to = $config->phone;
		}

		if($config->sms_cnt){
			$this->load->helper("sender");
			$this->load->model("Msmshistory");
			
			$msg = "[".str_replace("-","",$this->input->post("enPhone"))."] ".$this->input->post("enName") . "님께서 ".$gubun." 의뢰를 접수하셨습니다.";
			$sms_result = sms($config->mobile,$sms_to,"",$msg);

			if($sms_result=="발송성공"){						 
				$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - 1)),"");
			}
			$param = Array(
				"sms_from" => $config->mobile,
				"sms_to" => $sms_to,
				"msg" => $msg,
				"type" => "A",
				"minus_count" => ($sms_result=="발송성공") ? 1 : 0,
				"result" => $sms_result,
				"page" => "user_enquire",
				"date" => date('Y-m-d H:i:s')
			);
			$this->Msmshistory->insert($param);				
		}

		echo "1";
	}

	/**
	 * 의뢰하기 비밀번호 체크 후 정보 리턴
	 */
	public function enquire_pw(){

		$this->load->model("Menquire");
		$this->load->model("Mcategory");

		$query = $this->Menquire->get($this->input->post("enquire_id"));

		$category_list = $this->Mcategory->get_list_multi($query->category);

		$query->category_list = "";
		foreach($category_list as $val){
			$query->category_list .= $query->category_list." ".$val->name;
		}		

		$pw = $this->_prep_password($this->input->post("pw"));

		if($pw==$query->pw){
			echo json_encode($query);
		}
	}

	/**
	 * 의뢰하기 정보 리턴
	 */
	public function enquire_get(){

		$this->load->model("Menquire");
		$this->load->model("Mcategory");
		
		$query = $this->Menquire->get($this->input->post("enquire_id"));

		$category_list = $this->Mcategory->get_list_multi($query->category);
		
		$query->category_list = "";
		foreach($category_list as $val){
			$query->category_list .= $query->category_list." ".$val->name;
		}	
		echo json_encode($query);
	}

	/**
	 * 회원 관심 매물 가져오기
	 */
	public function hope(){
		$data["page_title"] =  "관심 매물";
		$this->load->model("Mhope");
		if($this->session->userdata("id")!=""){
			$data["query"] = $this->Mhope->get_list_by_member($this->session->userdata("id"));
		} else {
			$data["query"] = $this->Mhope->get_list_by_session($this->session->userdata("session_id"));
		}
		$this->layout->view('basic/member_hope',$data);
	}

	public function history(){
		$data["page_title"] =  "본 매물";
		$this->load->model("Mhistory");
		$data["query"] = $this->Mhistory->get_list_by_session($this->session->userdata("session_id"));
		$this->layout->view('basic/member_history',$data);		
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect("/","refresh");
	}

	/**
	 * 비밀번호 인크립션
	 */
	private function _prep_password($password){
		 return sha1($password.$this->config->item('encryption_key'));
	}

	/**
	 * 회원 매물 목록
	 */	
	public function product($page=0){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		$this->load->library('pagination');
	
		$this->load->model("Mmember");
		$this->load->model("Mpay");

		$config['base_url'] = "/member/product/";
		$config['total_rows'] = $this->Mmember->get_member_product_total($this->session->userdata("id"));
		$config['per_page'] = 10;
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		$data['total'] = $config['total_rows'];
		$data["page_title"] = lang('product')."관리";
		$data["query"] = $this->Mmember->get_member_product($this->session->userdata("id"),$config['per_page'], $page);

		$data["pay_info"] = $this->use_pay_info();

		$this->layout->view('basic/member_product',$data);
	}

	/**
	 * 회원 매물등록 페이지
	 */	
	public function product_add(){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}
		
		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");		
		$this->load->model("Maddress");
		
		$data["page_title"] = lang('product')."등록";

		$this->cleanTemp(); //임시 저장 파일을 모두 삭제한다.

		$data["config"] = $this->Mconfig->get();
		$data["category"] = $this->Mcategory->get_list();
		foreach($data["category"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["category"][$key]->category_sub = $category_sub;
			}
		}
		$data["theme"] = $this->Mtheme->get_list();
		$this->Maddress->set_type("full");
		$data["sido"] = $this->Maddress->get_sido();

		if($data["config"]->INIT_SIDO !=""){
			$init_gugun = ($data["config"]->INIT_GUGUN) ? $this->Maddress->get_parent($data["config"]->INIT_GUGUN)->gugun : "";
			$init_dong = ($data["config"]->INIT_DONG) ? $this->Maddress->get($data["config"]->INIT_DONG)->dong : "";
			$data["address_text"] = $data["config"]->INIT_SIDO." ".$init_gugun." ".$init_dong;
		}
		else{
			$data["address_text"] = "";
		}

		$data["mode"] = "add";
		$data["module"] = "front";
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);

		$this->layout->view('basic/member_product_add',$data);		
	}

	/**
	 * 회원 매물수정 페이지
	 */
	public function product_edit($id){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		$this->load->model("Madminproduct");
		$this->load->model("Mcategory");
		$this->load->model("Mmember");
		$this->load->model("Maddress");
		$this->load->model("Mtheme");
		$this->load->model("Mdanzi");

		$data["page_title"] = "매물 수정";
		$data["members"] = $this->Mmember->get_list();
		$data["query"] = $this->Madminproduct->get($id);
		$data["category"] = $this->Mcategory->get_list();
		foreach($data["category"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["category"][$key]->category_sub = $category_sub;
			}
		}
		$data["address"] = $this->Maddress->get($data["query"]->address_id);
		$data["address_text"] = $data["address"]->sido." ".$data["address"]->gugun." ".$data["address"]->dong;
		$this->Maddress->set_type("full");
		$data["sido"] = $this->Maddress->get_sido();
		$data["danzi"] = $this->Mdanzi->get_danzi($data["query"]->address_id);
		$danzi_info = ($data["query"]->danzi_id) ? $this->Mdanzi->get($data["query"]->danzi_id) : "";
		$data["query"]->danzi_name = ($danzi_info) ? $danzi_info->name : "";

		$data["theme"] = $this->Mtheme->get_list();

		if($data["query"]->theme){
			$query_theme = @explode(",",$data["query"]->theme);
			foreach($data["theme"] as $val){
				if(in_array($val->id,$query_theme)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$data["query"]->add_price = $this->Madminproduct->get_add_price($data["query"]->id);

		$data["config"] = $this->Mconfig->get();
		$data["mode"] = "edit";
		$data["module"] = "front";
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);

		$this->layout->view('basic/member_product_edit',$data);
	}

	/**
	 * 로그인한 회원의 임시 갤러리 파일을 모두 삭제한다.
	 */
	private function cleanTemp(){
		$this->load->model("Mgallerytemp");
		$gallery = $this->Mgallerytemp->get_list($this->session->userdata("id"));

		foreach($gallery as $val){
			if( file_exists(HOME.'/uploads/gallery/temp/'. element("filename",$val)) ){
				unlink(HOME.'/uploads/gallery/temp/'. element("filename",$val));			//본 이미지 삭제
				$temp = explode(".",element("filename",$val));
				unlink(HOME.'/uploads/gallery/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}
			$this->Mgallerytemp->delete($val["id"]);
		}
	}

	/**
	 * 회원 매물등록
	 */
	function product_add_action($mobile=false){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if(!$this->input->post("lat") || !$this->input->post("lng")){
			redirect("/member/product","refresh");
		}

		$recommand = "0";
		if($this->input->post("recommand")=="on") {
			$recommand = "1";
		}

		$is_speed = "0";
		if($this->input->post("is_speed")=="on") {
			$is_speed = "1";
		}


		//option을 하나도 선택 안 했을 경우에 오류 발생
		$option = "";
		if($this->input->post("option")!=""){
			$option = implode(",",$this->input->post("option"));
		}

		$etc = "";
		if($this->input->post("etc") != ""){
			$etc =  implode("--dungzi--",$this->input->post("etc"));
		}

		$theme = "";
		if($this->input->post("theme")){
			$theme = implode(",",$this->input->post("theme"));
		}

		$type = $this->input->post("type");
		if($type=="") $type = "sell";

		$param = Array(
			"title"				=> $this->input->post("title"),
			"theme"				=> $theme,
			"part"				=> $this->input->post("part"),
			"type"				=> $type,
			"sell_price"		=> $this->input->post("sell_price"),
			"lease_price"		=> $this->input->post("lease_price"),
			"full_rent_price"	=> $this->input->post("full_rent_price"),
			"monthly_rent_deposit"		=> $this->input->post("monthly_rent_deposit"),
			"monthly_rent_price"		=> $this->input->post("monthly_rent_price"),
			"premium_price"				=> $this->input->post("premium_price"),
			"monthly_rent_deposit_min"	=> $this->input->post("monthly_rent_deposit_min"),
			"price_adjustment"			=> $this->input->post("price_adjustment"),
			"mgr_price"					=> $this->input->post("mgr_price"),
			"mgr_price_full_rent"		=> $this->input->post("mgr_price_full_rent"),
			"mgr_include"	=> $this->input->post("mgr_include"),
			"park_price"	=> $this->input->post("park_price"),
			"park"			=> $this->input->post("park"),
			"address"		=> $this->input->post("address"),
			"address_unit"	=> $this->input->post("address_unit"),
			"apt_dong"		=> $this->input->post("apt_dong"),
			"apt_ho"		=> $this->input->post("apt_ho"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"address_id"	=> $this->input->post("address_id"),
			"danzi_id"		=> $this->input->post("danzi_id"),
			"category"		=> $this->input->post("category"),
			"category_sub"	=> $this->input->post("category_sub"),
			"real_area"		=> $this->input->post("real_area"),
			"law_area"		=> $this->input->post("law_area"),
			"land_area"		=> $this->input->post("land_area"),
			"road_area"		=> $this->input->post("road_area"),
			"road_conditions"=> $this->input->post("road_conditions"),
			"bedcnt"		=> $this->input->post("bedcnt"),
			"loan"			=> $this->input->post("loan"),
			"extension"		=> $this->input->post("extension"),
			"bathcnt"		=> $this->input->post("bathcnt"),
			"current_floor"	=> $this->input->post("current_floor"),
			"total_floor"	=> $this->input->post("total_floor"),
			"heating"		=> $this->input->post("heating"),
			"enter_year"	=> $this->input->post("enter_year"),
			"build_year"	=> ($this->input->post("build_year")) ? $this->input->post("build_year") : "",
			"option"		=> $option,
			/*"content"		=> $this->input->post("content"), 2016-02-22 임근호*/
			"content"		=> $this->input->post("content_01"),
			"recommand"		=> $recommand,
			"is_speed"		=> $is_speed,
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"is_activated"	=> $this->input->post("is_activated"),
			"member_id"		=> $this->input->post("member_id"),
			"etc"			=> $etc,
			"moddate"		=> date('Y-m-d H:i:s'),
			"date"			=> date('Y-m-d H:i:s')
		);

		$param["ground_use"] = $this->input->post("ground_use");
		$param["ground_aim"] = $this->input->post("ground_aim");			

		$param["factory_power"] = $this->input->post("factory_power");
		$param["factory_hoist"] = $this->input->post("factory_hoist");
		$param["factory_use"] = $this->input->post("factory_use");

		if($config->USE_APPROVE) $param["is_valid"] = "0";
		if(!$config->USE_PAY) $param["is_activated"] = "1";

		/** 공실 정보 추가 **/
		if($config->GONGSIL_FLAG){

			$phone = "";
			if($this->input->post("phone")!=""){
				$type = $this->input->post("phone_type");
				foreach($this->input->post("phone") as $key=>$val){
					$phone .= $type[$key] ."--type--". $val . "---dungzi---";
				}
			}

			$param["gongsil_contact"] 	= $phone;
			$param["gongsil_status"] 	= $this->input->post("gongsil_status");
			$param["gongsil_see"] 	= $this->input->post("gongsil_see");
		}

		/** 상가 정보 추가 **/
		$param["store_category"]  = $this->input->post("store_category");
		$param["store_name"] 	    = $this->input->post("store_name");
		$param["profit_income"] 	= $this->input->post("profit_income");
		$param["profit_outcome"] 	= $this->input->post("profit_outcome");
		$param["outcome_matcost"] 	= $this->input->post("outcome_matcost");
		$param["outcome_salary"] 	= $this->input->post("outcome_salary");
		$param["outcome_etc"] 	    = $this->input->post("outcome_etc");

		$this->load->model("Madminproduct");
		$idx = $this->Madminproduct->insert($param);

		/** 월세, 전월세 가격 추가 **/
		$monthly_rent_deposit_add = $this->input->post("monthly_rent_deposit_add");
		$monthly_rent_price_add = $this->input->post("monthly_rent_price_add");
		if(is_array($monthly_rent_deposit_add) && count($monthly_rent_deposit_add)){
			foreach($monthly_rent_deposit_add as $key=>$val){
				$add_price_param = Array(
					"product_id"=>$idx,
					"monthly_rent_deposit" => $val,
					"monthly_rent_price" => $monthly_rent_price_add[$key]
				);
				$this->Madminproduct->insert_add_price($add_price_param);
			}
		}

		//네이버 신디케이션 전송
		$this->load->helper("syndi");
		send_ping($idx,"product");

		//지하철 역 정보 추가
		$this->load->model("Madminproduct");
		$subway = $this->Madminproduct->get_subway($this->input->post("lat"),$this->input->post("lng"));
		foreach($subway as $val){
			$this->Madminproduct->insert_subway($idx,$val->id);
		}

		$this->move_temp_gallery($idx); //임시로 저장되어 있는 
		$this->cleanTemp(); //모두 옮긴 후 템프를 삭제한다.
		
		if($config->USE_PAY){
			
			$this->load->model("Mpay");

			$pay = $this->Mpay->is_valid_pay($this->input->post("member_id"));
			
			if(!$pay){
				redirect("/member/product_pay","refresh");
				exit;
			}
			else{

				$pay_info = $this->use_pay_info();

				if($pay_info->enabled_count){
					$this->Madminproduct->update(Array("is_activated"=>"1"),$idx);
				}
			}
		}
		if($mobile){
			redirect("/mobile/product","refresh");
		}
		else{
			redirect("/member/product","refresh");
		}
	}

	/**
	 * 회원 매물수정
	 */
	function product_edit_action($mobile=false){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$recommand = "0";
		
		if($this->input->post("recommand")=="on") {
			$recommand = "1";
		}

		$is_speed = "0";
		if($this->input->post("is_speed")=="on") {
			$is_speed = "1";
		}

		//매물 수정 시 옵션이 하나도 선택이 되지 않았을 경우에 오류 메세지가 출력되는 현상 수정(2014.11.17)
		$option = "";
		if($this->input->post("option")!=""){
			$option = implode(",",$this->input->post("option"));
		}

		$etc = "";
		if($this->input->post("etc") != ""){
			$etc =  implode("--dungzi--",$this->input->post("etc"));
		}

		$theme = "";
		if($this->input->post("theme")){
			$theme = implode(",",$this->input->post("theme"));
		}

		$param = Array(
			"title"			=> $this->input->post("title"),
			"theme"			=> $theme,
			"type"			=> $this->input->post("type"),
			"part"			=> $this->input->post("part"),
			"sell_price"	=> $this->input->post("sell_price"),
			"lease_price"	=> $this->input->post("lease_price"),
			"full_rent_price"			=> $this->input->post("full_rent_price"),
			"monthly_rent_deposit"		=> $this->input->post("monthly_rent_deposit"),
			"monthly_rent_price"		=> $this->input->post("monthly_rent_price"),
			"premium_price"				=> $this->input->post("premium_price"),
			"monthly_rent_deposit_min"	=> $this->input->post("monthly_rent_deposit_min"),
			"price_adjustment"			=> $this->input->post("price_adjustment"),
			"mgr_price"					=> $this->input->post("mgr_price"),
			"mgr_price_full_rent"		=> $this->input->post("mgr_price_full_rent"),
			"mgr_include"	=> $this->input->post("mgr_include"),
			"park_price"	=> $this->input->post("park_price"),
			"park"			=> $this->input->post("park"),
			"address_id"	=> $this->input->post("address_id"),
			"danzi_id"		=> $this->input->post("danzi_id"),
			"address"		=> $this->input->post("address"),
			"address_unit"	=> $this->input->post("address_unit"),
			"apt_dong"		=> $this->input->post("apt_dong"),
			"apt_ho"		=> $this->input->post("apt_ho"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"category"		=> $this->input->post("category"),
			"category_sub"	=> $this->input->post("category_sub"),
			"real_area"		=> $this->input->post("real_area"),
			"law_area"		=> $this->input->post("law_area"),
			"land_area"		=> $this->input->post("land_area"),
			"road_area"		=> $this->input->post("road_area"),
			"road_conditions"=> $this->input->post("road_conditions"),
			"bedcnt"		=> $this->input->post("bedcnt"),
			"bathcnt"		=> $this->input->post("bathcnt"),
			"loan"			=> $this->input->post("loan"),
			"extension"		=> $this->input->post("extension"),
			"current_floor"	=> $this->input->post("current_floor"),
			"total_floor"	=> $this->input->post("total_floor"),
			"heating"		=> $this->input->post("heating"),
			"enter_year"	=> $this->input->post("enter_year"),
			"build_year"	=> ($this->input->post("build_year")) ? $this->input->post("build_year") : "",
			"option"		=> $option,
			/*"content"		=> $this->input->post("content_01"), 매물등록 에디터때문에 2016-02-22 임근호*/
			"content"		=> $this->input->post("content_01"),
			"recommand"		=> $recommand,
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"recommand"		=> $recommand,
			"is_speed"		=> $is_speed,
			"etc"			=> $etc,
			"moddate"		=> date('Y-m-d H:i:s')
		);

		/** 토지 면적 정보 **/
		$param["ground_use"] = $this->input->post("ground_use");
		$param["ground_aim"] = $this->input->post("ground_aim");			

		/** 공장 정보 **/
		$param["law_area"] = $this->input->post("law_area");
		$param["real_area"] = $this->input->post("real_area");
		$param["factory_power"] = $this->input->post("factory_power");
		$param["factory_hoist"] = $this->input->post("factory_hoist");
		$param["factory_use"] = $this->input->post("factory_use");

		/** 공실 정보 추가 **/
		$phone = "";
		if($this->input->post("phone")!=""){
			$type = $this->input->post("phone_type");
			foreach($this->input->post("phone") as $key=>$val){
				$phone .= $type[$key] ."--type--". $val . "---dungzi---";
			}
		}
		$param["gongsil_contact"] 	= $phone;
		$param["gongsil_status"] 	= $this->input->post("gongsil_status");
		$param["gongsil_see"] 	= $this->input->post("gongsil_see");

		/** 상가 정보 추가 **/
		$param["store_category"]	= $this->input->post("store_category");
		$param["store_name"] 	    = $this->input->post("store_name");
		$param["profit_income"] 	= $this->input->post("profit_income");
		$param["profit_outcome"] 	= $this->input->post("profit_outcome");
		$param["outcome_matcost"] 	= $this->input->post("outcome_matcost");
		$param["outcome_salary"] 	= $this->input->post("outcome_salary");
		$param["outcome_etc"] 	    = $this->input->post("outcome_etc");

		$this->load->model("Madminproduct");
		$this->Madminproduct->update($param,$this->input->post("id"));

		/** 월세, 전월세 가격 수정 **/
		$this->Madminproduct->delete_add_price($this->input->post("id"));
		$monthly_rent_deposit_add = $this->input->post("monthly_rent_deposit_add");
		$monthly_rent_price_add = $this->input->post("monthly_rent_price_add");
		if(is_array($monthly_rent_deposit_add) && count($monthly_rent_deposit_add)){
			foreach($monthly_rent_deposit_add as $key=>$val){
				$add_price_param = Array(
					"product_id"=>$this->input->post("id"),
					"monthly_rent_deposit" => $val,
					"monthly_rent_price" => $monthly_rent_price_add[$key]
				);
				$this->Madminproduct->insert_add_price($add_price_param);
			}
		}

		//네이버 신디케이션 전송
		$this->load->helper("syndi");
		send_ping($this->input->post("id"),"product");

		//지하철 역 정보 추가
		$this->load->model("Madminproduct");
		$subway = $this->Madminproduct->get_subway($this->input->post("lat"),$this->input->post("lng"));

		$this->Madminproduct->delete_subway($this->input->post("id"));
		foreach($subway as $val){
			$this->Madminproduct->insert_subway($this->input->post("id"),$val->id);
		}
		if($mobile){
			redirect("/mobile/product","refresh");
		}
		else{
			redirect("/member/product","refresh");
		}
	}

	/**
	 * 대표 이미지 썸네일 만들기
	 * - 대표사진의 경우 사이즈가 지정된 사이즈보다 작을 경우라면 사이즈를 조정하지 않고 그대로 저장하도록 한다.
	 */
	private function make_thumb($data, $member_id, $width=300, $folder="thumb/"){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$thumb_config['source_image'] = HOME.'/uploads/products/'. $data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/products/'.$folder.$data["upload_data"]["file_name"];

		if($data["upload_data"]["image_width"] > $width){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['create_thumb'] = TRUE;
			$thumb_config['thumb_marker'] = "";
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = $width;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = $config->QUALITY;

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				echo $CI->image_lib->display_errors();
				return "0";
			} else {
				return "1";
			}
		} else {
			//이미지가 지정된 크기보다 작을 경우 그냥 카피만 한다.
			copy($thumb_config['source_image'],$thumb_config['new_image']);
		}
	}

	private function move_temp_gallery($id){

		//옮겨갈 폴더가 없으면 새로 만든다.
		$target = HOME.'/uploads/gallery/'.$id;
		if(!file_exists($target)){
			mkdir($target,0777);
			chmod($target,0777);
		}

		// 입력화면에서 등록된 갤러리를 옮긴다.
		$this->load->model("Mgallerytemp");
		$this->load->model("Mgallery");
		$gallery = $this->Mgallerytemp->get_list($this->session->userdata("id"));
		foreach($gallery as $val){

			copy(HOME.'/uploads/gallery/temp/' . element("filename",$val), $target . "/" . element("filename",$val));
			$temp = explode(".",element("filename",$val));
			copy(HOME.'/uploads/gallery/temp/'. $temp[0]."_thumb.".$temp[1], $target . "/" . $temp[0]."_thumb.".$temp[1]);

			$param = Array(
				"product_id" => $id,
				"content" => $val["content"],
				"filename" => element("filename",$val),
				"sorting" => element("sorting",$val),
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mgallery->insert($param);
		}
	}

	/**
	 * 회원매물 삭제
	 * 1. 갤러리 삭제, 2. 썸네일 삭제, 3. DB삭제, 4. 지하철역 정보 삭제
	 */
	function delete_product($id,$redirect=true){
		$this->load->Model("Madminproduct");
		$product = $this->Madminproduct->get($id);
		if($product!=null){
			//매물등록자이거나 관리자라면 삭제가 가능하다.
			if($this->session->userdata("id")==$product->member_id || $this->session->userdata("auth_id")==1){
				
				//갤러리 삭제
				if( file_exists(HOME.'/uploads/gallery/'.$id) ){
					$this->rrmdir(HOME.'/uploads/gallery/'.$id);
				}
				$this->load->model("Mgallery");
				$this->Mgallery->delete_product($id);

				//네이버 신디케이션 전송
				$this->load->helper("syndi");
				send_ping($id,"product","delete");

				//DB 삭제
				$this->Madminproduct->delete_product($id);
				$this->Madminproduct->delete_subway($id);

				//소유주 매물 삭제
				$this->load->model("Mcontactproduct");
				$this->Mcontactproduct->delete_contacts_product($id);

				//추가된 월세,전월세 가격 삭제
				$this->Madminproduct->delete_add_price($id);

				if($redirect) redirect("/member/product","refresh");
			} else {
				echo "<script>alert('Authentification Error!');history.go(-1);</script>";
			}
		} else {
			if($redirect) redirect("/member/product","refresh");
		}
	}

	/**
	 * 회원매물 다중 삭제
	 */
	function delete_all_product(){
		$check_product = $this->input->post('check_product');
		if($check_product){
			foreach($check_product as $value){
				$this->delete_product($value,false);
			}
		}
		redirect("/member/product","refresh");
	}

	/**
	 * 비어 있지 않은 디렉토리를 삭제한다.
	 */
	private function rrmdir($dir) {
	   if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object){
		   if ($object != "." && $object != "..") {
			 if (filetype($dir."/".$object) == "dir"){
				rrmdir($dir."/".$object);
			 }else{ 
				unlink($dir."/".$object);
			 }
		   }
		 }
		 reset($objects);
		 rmdir($dir);
	  }
	}

	/**
	 * 유료 광고상품 페이지
	 */	
	public function product_pay(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		if(!$config->USE_PAY){
			redirect("member/product","refresh");
		}

		$this->load->Model("Mpay");

		$data["page_title"] = lang("pay");
		$data["query"] = $this->Mpay->get_setting_list();

		if($data["query"]){
			foreach($data["query"] as $val){
				$pay_info = $this->Mpay->get_by_paysetting($this->session->userdata("id"),$val->id);
				if($pay_info){
					if($pay_info->price == 0 && ($val->id == $pay_info->pay_setting_id)){
						$val->in_use = true;
					}				
				}
				else{
					$val->in_use = false;				
				}
			}
		}

		$this->layout->view('basic/member_product_pay',$data);
	}

	/**
	 * 회원 매물 상태 변경
	 */
	function state_change($type){
		$this->load->model("Mconfig");
		$this->load->model("Madminproduct");

		$config = $this->Mconfig->get();

		$param = Array(
			$type => $this->input->post('state')
		);

		if(!$config->USE_PAY || $this->session->userdata("auth_id") == '1'){
			$this->Madminproduct->change($param,$this->input->post('id'));
			echo "success";
			exit;
		}


		$pay_info = $this->use_pay_info();

		if($pay_info){
			if(($pay_info->enabled_count > 0 || $this->input->post('state') == 0)){
				$this->Madminproduct->change($param,$this->input->post('id'));
				echo "success";
				exit;
			}
			else{
				echo "fail";
				exit;
			}	
		}
		else{
			if($this->input->post('state')=="0"){
				$this->Madminproduct->change($param,$this->input->post('id'));
				echo "success";
				exit;
			}
			else{
				echo "no_pay";
				exit;			
			}
		}
	}

	/**
	 * 회원이 현재 사용중인 상품 정보
	 */
	public function use_pay_info(){
		$this->load->model("Mpay");
		$this->load->model("Mmember");

		$open_count = $this->Mmember->get_member_open_product_total($this->session->userdata("id"));
		$paying_info = $this->Mpay->is_valid_pay($this->session->userdata("id"));
		
		if($paying_info){
			$paying_info->enabled_count = $paying_info->use_count - $open_count;
		}
		return $paying_info;
	}

	/**
	 * 회원의 결제내역 페이지
	 */
	public function pay($page=0){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		if(!$config->USE_PAY){
			redirect("member/product","refresh");
		}

		$this->load->model("Mpay");

		$this->load->library('pagination');

		$page_config['base_url'] = "/member/pay/";
		$page_config['total_rows'] = $this->Mpay->get_total($this->session->userdata("id"));
		$page_config['per_page'] = 10;
		$page_config['first_link'] = '<<';
		$page_config['first_tag_open'] = '<li>';
		$page_config['first_tag_close'] = '</li>';

		$page_config['last_link'] = '>>';
		$page_config['last_tag_open'] = '<li>';
		$page_config['last_tag_close'] = '</li>';

		$page_config['num_tag_open'] = "<li>";
		$page_config['num_tag_close'] = "</li>";
		$page_config['cur_tag_open'] = '<li class="active"><a href="#">';
		$page_config['cur_tag_close'] = '</a></li>';

		$page_config['next_link'] = '>';
		$page_config['next_tag_open'] = '<li>';
		$page_config['next_tag_close'] = '</li>';

		$page_config['prev_link'] = '<';
		$page_config['prev_tag_open'] = '<li>';
		$page_config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($page_config);
		$data["pagination"] = $this->pagination->create_links();

		$data["page_title"] = "결제내역";

		$data["query"] = $this->Mpay->get_list($this->session->userdata("id"),$page_config['per_page'], $page);
		$paying_info = $this->Mpay->is_valid_pay($this->session->userdata("id"));

		$data["now_order"] = ($paying_info) ? $paying_info->id : "";

		$this->layout->view('basic/member_pay',$data);
	}

	public function get_member_by_product($id){
		$this->load->model("Mmember");
		$member = $this->Mmember->get_by_product($id);
		echo json_encode($member);
	}

	/**
	 * 나의 블로그 페이지
	 */	
	public function blog($page=0){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		$this->load->model("Mblogapi");

		$this->load->library('pagination');

		$config['base_url'] = "/member/blog/";
		$config['total_rows'] = $this->Mblogapi->get_total($this->session->userdata("id"));
		$config['per_page'] = 10;
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		$data["page_title"] = "나의 블로그";

		$data["query"] = $this->Mblogapi->get_list($this->session->userdata("id"),$config['per_page'], $page);

		$this->layout->view('basic/member_blog',$data);
	}

	/**
	 * 회원 블로그 등록
	 */
	public function blog_add_action(){

		$param = Array(
			"member_type" => "member",
			"member_id" => $this->session->userdata("id"),
			"type" => $this->input->post("type"),
			"valid" => $this->input->post("valid"),
			"user_id" => trim($this->input->post("user_id")),
			"address" => trim($this->input->post("address")),
			"blog_id" => trim($this->input->post("blog_id")),
			"blog_key" => trim($this->input->post("blog_key"))
		);

		if($param['type']=='naver'){
			$param['address'] = $param['user_id'];
			$param['blog_id'] = $param['user_id'];
		}

		$this->load->model("Mblogapi");
		$this->Mblogapi->insert($param);

		redirect("/member/blog","refresh");
	}

	/**
	 * 회원 블로그 수정
	 */
	public function blog_edit_action(){
		$param = Array(
			"type" => $this->input->post("type"),
			"valid" => $this->input->post("valid"),
			"user_id" => trim($this->input->post("user_id")),
			"address" => trim($this->input->post("address")),
			"blog_id" => trim($this->input->post("blog_id")),
			"blog_key" => trim($this->input->post("blog_key"))
		);

		if($param['type']=='naver'){
			$param['address'] = $param['user_id'];
			$param['blog_id'] = $param['user_id'];
		}

		$this->load->model("Mblogapi");
		$this->Mblogapi->update($this->input->post("id"),$param);

		redirect("/member/blog","refresh");
	}

	/**
	 * 회원 블로그 다중 삭제
	 */
	function delete_all_blog(){
		$this->load->model("Mblogapi");

		$check_blog = $this->input->post('check_blog');
		if($check_blog){
			foreach($check_blog as $value){
				$this->Mblogapi->delete_blog($value);
			}
		}
		redirect("/member/blog","refresh");
	}

	/**
	 * 블로그 정보 반환
	 */
	public function get_json_blog($id){
		$this->load->model("Mblogapi");
		$query = $this->Mblogapi->get($id);
		echo json_encode($query);
	}

	/**
	 * 회원 매물 포스팅 기능
	 */
	public function posting($product_id){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
		}

		$blog_ids = $this->input->post("blog_id");
		$blog_title = $this->input->post("blog_title");

		$this->load->model("Madminproduct");
		$this->load->model("Mproduct");
		$this->load->model("Mgallery");
		$this->load->model("Mmember");
		$this->load->library('blogapi');
		$this->load->model("Mblogapi");	
		$this->load->model("Mbloghistory"); //블로그 등록 히스토리 내역
		$this->load->model("Mconfig");
		$this->load->model("Mcategory");
		$this->load->model("Mviral");

		$blog = $this->Mblogapi->get_in_list($blog_ids);

		foreach($blog as $val){
			
			$this->blogapi->init($val->type,$val->address, $val->user_id, $val->blog_id, $val->blog_key);
			$data["query"]=$this->Mproduct->get($product_id);
			$data["product_subway"] = $this->Mproduct->get_product_subway($product_id);
			$data["gallery"] = $this->Mgallery->get_list($product_id);
			$data["member"] = $this->Mmember->get($data["query"]->member_id);
			$data["recent"]= $this->Mproduct->get_products_recent($data["query"]->id, $data["query"]->category, $data["query"]->lat, $data["query"]->lng, 10);
			$data["category"] = $this->Mcategory->get_list();
			$data["category_one"] = $this->Mcategory->get($data["query"]->category);

			$data["proverb1"] = $this->Mviral->get_proverb();
			$data["proverb2"] = $this->Mviral->get_proverb();
			$data["proverb3"] = $this->Mviral->get_proverb();
			$data["statement"] = $this->Mviral->get_statement();
			$data["youtube"] = $this->Mviral->get_youtube();

			$this->layout->setLayout("list");
			$content   = $this->layout->view("basic/template/blog_product_1",$data,true);
			$content = str_replace("class=\"border-table\"","border=\"0\" style=\"width:100%;border:1px solid #dddddd;border-spacing:0;font-family:dotum;font-size:14px;\"",$content); 
			$content = str_replace("<th","<th style=\"background-color:#f4f4f4;padding:5px;color:#222;border-collapse:collapse;border:1px solid #dddddd;\"",$content);
			$content = str_replace("<td","<td  style=\"border:1px solid #dddddd;padding:5px;\"",$content);


			preg_match_all('/src="([^"]+)"/', $content, $imgs); 
			$result = array_unique($imgs["1"]);	//중복 삭제
			
			$this->load->helper('security');
			foreach($result as $key=>$val2){
				if($val2!=""){
					// 원격 파일일 경우에는 로컬에 저장한 후에 처리가 끝나면 삭제처리한다.
					if (strpos($val2, 'https') !== false){

					} else if (strpos($val2, '/photo/gallery_image/') !== false){
						//포함되어 있다.

						$filename = "/uploads/gallery/temp/".do_hash($val2,'md5') . ".jpg";
						$filedata = get_url($val2);
						write_file(HOME.$filename, $filedata);
						$r = $this->blogapi->add_file($filename);
						if(is_array($r)){
							$content = str_replace($val2, $r["url"]->me["string"], $content);
						} else {
							echo "0";
							exit;
						}

						unlink(HOME.$filename);
					} else if (strpos($val2, 'http') !== false){
						/** 갤러리 이미지가 아닌 http붙는 것들은 변환없이 그대로 넣는다.  **/
					} else {

						$r = $this->blogapi->add_file($val2);	
											if(is_array($r)){
						$content = str_replace($val2, $r["url"]->me["string"], $content);
						} else {
							echo "0";
							exit;
						}

					}
					
					$this->Mbloghistory->ping();
				}
			}

			//=========================================================================================================================
			// 포스팅을 하기 전에 히스토리를 추가한 후 포스팅이 완료되면 업데이트를 한다.
			// 결과값은 포스팅을 해야 알 수 있고 해당 포스팅의 조회수를 카운팅학기 위해서는 사전에 이력의 id를 넣어 줘야 하기 때문이다.
			//=========================================================================================================================
			$param = Array(
				"blog_id"	=> $val->id,
				"type"		=> "product",
				"data_id"	=> $product_id,
				"title"		=> $blog_title,
				"date"		=> date('Y-m-d H:i:s')
			);
	
			$blog_history_id = $this->Mbloghistory->insert($param);

			//위에서 구해온 블로그 히스토리 id를 넣어서 로고 URL을 만든다. 그러면 굳이 product_id를 넘길 필요는 없어진다.
			$post_footer = "<br><table border=\"0\" style=\"margin-top:50px\">";
			$post_footer .= "<tr>";
			$post_footer .= "	<td style=\"border:0px;border-right:1px solid #cacaca;padding:10px;\">";
			if($data["member"]->profile){
				$post_footer .= "	<img src='http://".HOST."/uploads/member/".$data["member"]->profile."' style='width:150px;'/>";
			}
			else{
				$post_footer .= "	<img src='http://".HOST."/logo/st/".$blog_history_id."'>";
			}
			$post_footer .= "	</td>";
			$post_footer .= "	<td style=\"padding-left:20px;\">";
			$post_footer .= "		<p><b style=\"font-size:16px;\">".$data["member"]->name."</b> <br/><br/> " . $data["member"]->address . " ".$data["member"]->address_detail.", 사업자명: ".$data["member"]->biz_name . " <br/>";
			$post_footer .= "		전화번호 : ".$data["member"]->tel." , 전화번호 : " . $data["member"]->phone ." <br/>";
			$post_footer .= "홈페이지 : <a href=\"http://".HOST."/product/view/".$product_id."\">http://".HOST."/product/view/".$product_id."</a>";
			$post_footer .= "	</p></td>";
			$post_footer .= "</tr>";
			$post_footer .= "</table>";

			/** 제목에 거래종류을 표시해 준다. **/
			$title_head = "";
			if($config->BLOG_TITLE_HEAD){
				if($data["query"]->type=="sell") {
					$title_head = lang("sell") ;
				} else if($data["query"]->type=="installation"){
					$title_head = lang("installation") ;
				} else if($data["query"]->type=="full_rent"){
					$title_head = lang("full_rent") ;
				} else if($data["query"]->type=="monthly_rent"){
					$title_head = lang("monthly_rent") ;
				}
			}

			$return = $this->blogapi->post($blog_title  . " " .  $title_head,$content.$post_footer,$data["query"]->tag);
			if($return!=""){
				$param = Array("return"=>$return);
				$this->Mbloghistory->update($param, $blog_history_id);
				$this->Madminproduct->update_blog($product_id);
				echo "success";
			} else {
				echo "fail";
			}			
		}
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		$config['upload_path'] = HOME.'/uploads/contents/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$filename = "";
		if ( ! $this->upload->do_upload("uploadfile"))
		{
			echo $CI->image_lib->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			$this->make_body($data);
			echo "/uploads/contents/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data){
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 500){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/contents/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 500;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = "100%";
			$thumb_config['overwrite'] = TRUE;

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				$CI->image_lib->display_errors();
				return false;
			} 
		}
	}

	/**
	 * 회원탈퇴 페이지
	 */	
	public function delete(){
		if($this->session->userdata("id")==""){
			redirect("member/signin","refresh");
			exit;
		}
		$data["page_title"] =  "회원탈퇴";
		$this->layout->view('basic/member_delete',$data);				
	}

	/**
	 * 회원탈퇴 처리
	 */	
	public function delete_action(){
		$this->load->model("Mmember");
		$this->load->model("Mpay");

		$pw = $this->_prep_password($this->input->post("pw"));
		$member = $this->Mmember->get($this->session->userdata("id"));

		if($pw == $member->pw){

			//프로필 삭제
			$this->delete_profile_image($this->session->userdata("id"));
			@unlink(HOME."/uploads/member/".$member->profile);

			//워터마크 삭제
			$this->delete_watermark_image($this->session->userdata("id"));
			@unlink(HOME."/uploads/member/".$member->watermark);

			//결제내역 삭제
			$this->Mpay->member_pay_delete($this->session->userdata("id"));

			//매물삭제
			$product = $this->Mmember->get_member_product($this->session->userdata("id"));
			foreach($product as $val){
				$this->delete_product($val->id);
			}

			//탈퇴한 회원의 정보 기록
			$param = Array(
				"email" => $member->email,
				"name" => $member->name,
				"reason" => $this->input->post("reason"),
				"date" => date('Y-m-d H:i:s')
			);
			$this->Mmember->delete_log($param);

			//회원DB삭제
			$this->Mmember->delete_area($this->session->userdata("id"));

			$this->session->sess_destroy();
			echo "1";
		}
	}

	//프로필사진 삭제
	public function delete_profile_image(){
		$this->load->model("Mmember");
		$member_id = $this->input->post("member_id");
		$profile_img_name = $this->input->post("profile_img_name");
		if($member_id && $profile_img_name){
			$this->Mmember->delete_profile_image($member_id);
			@unlink(HOME."/uploads/member/".$profile_img_name);
		}
	}

	//회원워터마크 삭제
	public function delete_watermark_image(){
		$this->load->model("Mmember");
		$member_id = $this->input->post("member_id");
		$watermark_img_name = $this->input->post("watermark_img_name");
		if($member_id && $watermark_img_name){
			$this->Mmember->delete_watermark_image($member_id);
			@unlink(HOME."/uploads/member/".$watermark_img_name);
		}
	}

	/**
	 * 건물의뢰 등록페이지
	 */
	public function building_enquire(){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
			exit;
		}

		$data["page_title"] =  "건축물자가진단 의뢰하기";

		$this->layout->view('basic/member_building_enquire',$data);

	}

	/**
	 * 건물검색
	 */
	public function building_search($address){

		$this->load->model("Mbuilding");

		$address = urldecode($address);
		$query = $this->Mbuilding->get($address);
		echo json_encode($query);
	}

	/**
	 * 건축 용도정보 가져오기
	 */
	public function building_use_info($id,$lat,$lng){

		$this->load->model("Mbuilding");
		$query = $this->Mbuilding->get_id($id);

		$api_url = "http://openAPI.seoul.go.kr:8089/OpenAPI/SearchUrbanPlanInfoByCoord.jsp";
		
		$key = "634c62466464756e39366e5474547a";

		$url = $api_url."?key=".$key."&lon=".$lng."&lat=".$lat;

		$curl_handle=curl_init();
		curl_setopt($curl_handle, CURLOPT_URL,$url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_handle, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
		$xml = curl_exec($curl_handle);
		curl_close($curl_handle);

		$this->load->library('simplexml');
		$xmlData = $this->simplexml->xml_parse($xml);

		$city_planning = "";	//도시계획
		$code_name = "";		//용도지역
		$coverage_upper = 0;	//건폐율상한
		$ratio_upper = 0;		//용적율상한

		$result = Array();
		if(isset($xmlData["item"])){
			if(isset($xmlData["item"][0])){//item이 복수
				foreach($xmlData['item'] as $key=>$val){
					$result[$key] = $val["category"]." (".$val["name"].")";
					if($val["category"]=="용도지역"){
						$split = explode("(",$val["name"]);
						$supremum = $this->Mbuilding->get_supremum($split[0]);
						$code_name = $supremum->code_name;
						$coverage_upper = $supremum->coverage_upper;
						$ratio_upper = $supremum->ratio_upper;
					}
				}
			}
			else{//item이 단건 (*단건일 경우 xml자료에 array key가 설정 안되고 반환하여 이와같이 분기를 하였음)
				$result[0] = $xmlData["item"]["category"]." (".$xmlData["item"]["name"].")";
				if($xmlData["item"]["category"]=="용도지역"){
					$split = explode("(",$xmlData["item"]["name"]);
					$supremum = $this->Mbuilding->get_supremum($split[0]);
					$code_name = $supremum->code_name;
					$coverage_upper = $supremum->coverage_upper;
					$ratio_upper = $supremum->ratio_upper;
				}
			}

			$city_planning = implode(" / ",$result);
		}

		//건축가능용도
		$data["building_limit"] = $this->Mbuilding->get_building_limit($code_name);
		$building_limit = $this->load->view("/basic/template/building_limit",$data,true);
		

		//건축면적상한 = 대지면적 X 건폐율상한
		$building_area_uppper = $query->plottage * ($coverage_upper * 0.01);

		//지상연면적상한 = 대지면적 X 용적율상한
		$ground_total_floor_area = $query->plottage * ($ratio_upper * 0.01);

		$result = Array(
			"city_planning" => $city_planning,
			"coverage_upper" => $coverage_upper,
			"ratio_upper" => $ratio_upper,
			"building_area_uppper" => $building_area_uppper,
			"ground_total_floor_area" => $ground_total_floor_area,
			"building_limit" => $building_limit
		);

		echo json_encode($result);
	}

	/**
	 * 건축 비용정보 가져오기
	 */
	public function building_expense($kind,$grade,$ground_total_floor_area,$elevator){

		$this->load->model("Mbuilding");

		//건축비용산정(공사비) 정보
		$construction_info = $this->Mbuilding->get_expense($kind,$grade);

		//건축비용산정(설계감리비) 정보
		$design_supervision_info = $this->Mbuilding->get_expense(4,$grade);

		//엘리베이터비용 정보
		$elevator_info = $this->Mbuilding->get_expense(5,"normal");

		//공사비 = 건축비용산정(공사비) X 지상연면적상한(평기준)
		$construction_cost = floor($construction_info->price * ($ground_total_floor_area / 3.305785));

		//설계감리비 = 건축비용산정(설계감리비) X 지상연면적상한(평기준)
		$coverage_upper = floor($design_supervision_info->price * ($ground_total_floor_area / 3.305785));

		//예상건축비용 = 공사비 + 설계감리비 + 엘리베이터(유 선택한 경우)
		$probable_cost = $construction_cost + $coverage_upper;

		$probable_cost = ($elevator) ? floor($probable_cost + $elevator_info->price) :  floor($probable_cost);

		$result = Array(
			"construction_cost" => $construction_cost,
			"design_supervision_cost" => $coverage_upper,
			"probable_cost" => $probable_cost
		);

		echo json_encode($result);
	}

	public function building_enquire_action(){

		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
			exit;
		}
		
		$this->load->model("Mbuilding");

		$param = Array(
			"building_id" => $this->input->post("id"),
			"member_id" => $this->input->post("member_id"),
			"type" => $this->input->post("type"),
			"city_planning" => $this->input->post("city_planning"),
			"coverage_upper" => $this->input->post("coverage_upper"),
			"ratio_upper" => $this->input->post("ratio_upper"),
			"building_area_uppper" => $this->input->post("building_area_uppper"),
			"ground_total_floor_area" => $this->input->post("ground_total_floor_area"),
			"expense_kind" => $this->input->post("expense_kind"),
			"expense_grade" => $this->input->post("expense_grade"),
			"expense_elevator" => $this->input->post("expense_elevator"),
			"construction_cost" => $this->input->post("construction_cost"),
			"design_supervision_cost" => $this->input->post("design_supervision_cost"),
			"probable_cost" => $this->input->post("probable_cost"),
			"date" => date('Y-m-d H:i:s')
		);

		$this->Mbuilding->enquire_insert($param);

		redirect("/member/building_enquire_list","refresh");
	}

	/**
	 * 건물의뢰 마이페이지
	 */
	public function building_enquire_list($page=0){
		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
			exit;
		}

		$data["page_title"] =  "건축물자가진단 의뢰";

		$this->load->library('pagination');
		$this->load->model("Mbuilding");
		$this->load->model("Mattachment");

        $config['base_url'] = "/member/building_enquire_list/";
        $config['total_rows'] = $this->Mbuilding->get_enquire_count($this->session->userdata("id"));
        $config['per_page'] = 20;
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['num_tag_open'] = "<li>";
        $config['num_tag_close'] = "</li>";
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $data["pagination"] = $this->pagination->create_links();

        $data['total'] = $config['total_rows'];
        $data['query'] = $this->Mbuilding->get_enquire_list($config['per_page'], $page,$this->session->userdata("id"));


		//첨부파일
		if(count($data['query'])){
			foreach($data['query'] as $key=>$val){
				$data['query'][$key]->estimate = $this->Mattachment->get_estimate_list($val->id);
			}		
		}

		$this->layout->view('basic/member_building_enquire_list',$data);
	}

	/**
	 * 건물의뢰 상세페이지
	 */
	public function building_enquire_view($id){
		if($this->session->userdata("id")==""){
			redirect("/member/signin","refresh");
			exit;
		}

		$data["page_title"] =  "건축물자가진단 보기";

		$this->load->model("Mbuilding");
		$this->load->model("Mattachment");

		$data["query"] = $this->Mbuilding->get_enquire($id,$this->session->userdata("id"));
		$data["building"] = $this->Mbuilding->get_id($data["query"]->building_id);
		$data["attachment"] = $this->Mattachment->get_estimate_list($id);

		$this->layout->view('basic/member_building_enquire_view',$data);
	}

	/**
	 * 회원가입시 SMS인증문자 보내기
	 */
	public function sms_confirm_send($phone){
		$this->load->model("Mconfig");
		$this->load->model("Msmshistory");

		$phone = urldecode($phone);

		$config = $this->Mconfig->get();
		$query = $this->Msmshistory->get_three_minute($phone);

		//3분전에 인증 요청한 번호가 없을 경우
		if(!$query){
			//SMS전송
			if($config->sms_cnt){
				$this->load->helper("sender");
				$msg = rand(100000,999999);
				$sms_result = sms($config->mobile,$phone,"",$msg);
				if($sms_result=="발송성공"){						 
					$this->Mconfig->update(Array("sms_cnt" => ($config->sms_cnt - 1)),"");
				}
				$param = Array(
					"sms_from" => $config->mobile,
					"sms_to" => $phone,
					"msg" => $msg,
					"type" => "A",
					"minus_count" => ($sms_result=="발송성공") ? 1 : 0,
					"result" => $sms_result,
					"page" => "confirm",
					"date" => date('Y-m-d H:i:s')
				);
				$this->Msmshistory->insert($param);			
			}

			echo json_encode(Array("result"=>"send"));
		}
		else{

			//현재시간과 쿼리 시간의 초차이
			$second = strtotime(date("Y-m-d H:i:s")) - strtotime($query->date);

			$minute = floor($second / 60);
			
			$remain_minute = 2 - $minute;
			$remain_second = 60 - ($second - ($minute * 60));

			$result = Array(
				"result"=>"ing",
				"minute"=>$remain_minute,
				"second"=>$remain_second
			);

			echo json_encode($result);
		}
	}

	/**
	 * 회원가입시 SMS인증 하기
	 */
	public function sms_confirm_check($phone,$confirm_num){

		$this->load->model("Msmshistory");
		$phone = urldecode($phone);
		$query = $this->Msmshistory->get_three_minute($phone);

		if($query){
			if($query->msg == $confirm_num) $result = "success";
			else $result = "fail";
		}
		else {
			$result = "fail";		
		}
		echo json_encode($result);
	}

}

/* End of file member.php */
/* Location: ./application/controllers/member.php */
