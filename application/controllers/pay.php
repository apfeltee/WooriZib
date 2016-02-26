<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pay extends CI_Controller {

	public function __construct() {
		parent::__construct();	
	}

	/**
	 * 결제진행
	 */
	public function pay_action(){

		$this->load->model("Mpay");
		$this->load->model("Madminproduct");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		$pay_setting = $this->Mpay->setting_get($this->input->post("pay_setting_id"));

		//----------------------------------------------------------------------
		// 결제 넘기기 전에 필요한 데이터를 저장한다
		// - 가상계좌의 경우에는 입금확인이 필요하므로 상태를 W로 해서 저장한다.
		//----------------------------------------------------------------------
		$state = ($this->input->post("pgcode")=="18") ? "W": "N"; //가상계좌일 경우 W

		$pay_param = Array(
			"member_id"	=> $this->input->post("member_id"),
			"pay_setting_id" => $this->input->post("pay_setting_id"),
			"order_name"=> $pay_setting->name,
			"pay_type"	=> $this->input->post("pgcode"),
			"use_day"	=> $pay_setting->day,
			"use_count"	=> $pay_setting->count,
			"price"		=> $pay_setting->price,
			"state"		=> $state,
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->Mpay->insert($pay_param);

		//주문번호
		$order_no = $this->db->insert_id();

		//-------------------------------------------------------------
		// 무료 결제 처리
		//-------------------------------------------------------------
		if($pay_setting->price==0){

			$day = date('Y-m-d H:i:s');

			$paying_info = $this->Mpay->last_valid_pay($this->input->post("member_id"));
			$pay_info = $this->Mpay->get($order_no);

			if($paying_info){ //이미 사용중인 결제정보가 있을 경우
				$start_date = $paying_info->end_date;
				$end_date = date("Y-m-d H:i:s", strtotime($paying_info->end_date." +".$pay_info->use_day." day"));
			}
			else{ //없을 경우 현재날짜 기준
				$start_date = $day;
				$end_date = date("Y-m-d H:i:s", strtotime($day." +".$pay_info->use_day." day"));
			}			

			$free_param = Array(
				"start_date"	=> $start_date,
				"end_date"		=> $end_date,
				"payed_date"	=> date('Y-m-d H:i:s'),
				"state"			=> "Y"
			);

			$this->Mpay->update($order_no,$free_param);

			//회원이 가지고 있는 결제유효일 업데이트
			$member_param = Array(
				"end_date"	=> $end_date
			);
			$this->load->model("Mmember");
			$this->Mmember->update($this->input->post("member_id"),$member_param);	

			$this->check_all_activated($this->input->post("member_id"));

			redirect("/member/pay","refresh");
			exit;
		}

		//-------------------------------------------------------------
		// 암호화모듈 PLParamEXECV2 require
		//-------------------------------------------------------------
		require("Lib/PLParamEXECV2.php");  // PLParamEXECV2.php 인클루드(plaescmd 이용시)

		//-------------------------------------------------------------
		// 암호화 키 관리 파일 require
		//-------------------------------------------------------------
		require("key/keyconf.php");  // POQKey.php 파일에 POQ로 부터 부여 받는 알맞는 값을 셋팅하여야 합니다.

		//-------------------------------------------------------------
		// Description  : 결제 데이터 세팅항목
		//-------------------------------------------------------------
		// PLParam Component Instance생성
		$objPLParam = new PLParamV2();

		// 암호화 키 관련 Property 추가
		$objPLParam->SetKey($szPVKey);
		$objPLParam->SetKeyID($szKeyID);
		$objPLParam->SetKeyVer($szKeyVer);

		// 필수 설정 데이터
		$objPLParam->SetParam("userid",     $this->input->post("member_id"));	// 사용자아이디
		$objPLParam->SetParam("clientid",   $config->CLIENTID);					// 고객사아이디 (페이레터에서 부여한 아이디)
		$objPLParam->SetParam("mallid",     "PAYONEQCARD");						// 상점아이디
		$objPLParam->SetParam("amt",        $pay_setting->price);				// 결제금액
		$objPLParam->SetParam("svcnm",      iconv("UTF-8","EUC-KR", $config->name));// 결제서비스명
		$objPLParam->SetParam("backurl",    "");								// "돌아가기"클릭시 돌아가는 페이지 URL(오픈창일경우 공백으로)

		// 선택 설정 데이터
		$objPLParam->SetParam("ordno",      $order_no);							// "주문번호"
		$objPLParam->SetParam("ordnm",      iconv("UTF-8","EUC-KR",$this->input->post("name")));// "사용자명"
		$objPLParam->SetParam("pname",      iconv("UTF-8","EUC-KR", $pay_setting->name));		// "결제상품명"
		$objPLParam->SetParam("etcparam",   "");								// "기타정보" (필요한 정보를 설정하면 되돌려줍니다.)
		$objPLParam->SetParam("emailstate", "1");								// 결제 내역 메일 수신여부(0:미사용, 1:사용)
		$objPLParam->SetParam("email",		$this->input->post("email"));		// 결제 내역을 수신할 메일주소(메일 수신 사용시 "emailstate"를 1로 세팅하시고 주석을 풀어 메일주소를 넣어주세요)

		// 노티 설정 데이터
		$objPLParam->SetParam("noti",       "1");	// 노티 방식 사용 여부(0: 미사용, 1: 사용)

		Switch ($this->input->post("pgcode")){
			Case(1) :   // 신용카드일 경우(allthegate)
				$objPLParam->SetParam("clinterest", "0");			// 신용카드 결제시 이자부담(0: 일반, 1: 고객사가 이자부담)[필수]
				//$objPLParam->SetParam("companynm", "xxxxxxxxx");	// "신용카드 결제시 안심클릭창에 나올 판매자명"(고객사 상호명을 기입해주세요, 미 세팅시 "payletter"로 세팅됩니다.)[선택]
				$strPGHostURL = "AllTheGate/ATGPayForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/pay/result");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/pay/noti" ); 
				break;

			Case(4) :   // 계좌이체(KFTC)
				if($config->PG_ACCOUNT=="allthegate") $strPGHostURL = "KFTC/KFTCPayForm.asp";
				if($config->PG_ACCOUNT=="inicis") $strPGHostURL = "INIBank/KFTCPayForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/pay/result");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/pay/noti" ); 
				break;

			Case(18) :  // 가상계좌(dacomvacct)
				//$objPLParam->SetParam("expymd", "20141212"); // 입금 만료일(선택, 기본 7일)
				$strPGHostURL = "DacomVaccount/VAccountAssignForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/pay/resultvacct");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/pay/notivacct" ); 
				break;

		}

		//------------------------------------------------------------
		// Description   : 해당 결제창으로 이동
		//------------------------------------------------------------
		// 모든 데이터가 설정되었고, 설정된 문자열이 암호화가 되었으므로 실제 결제 페이지로 보낸다.
		// 페이레터에서 알려주는 실제로 결제할 URL, 변경불가!!
		// 실제 결제가 됩니다. 테스트를 하고 관리자 페이지(http://pg1.payletter.com:9999)에서 취소할 수 있습니다.

		// 설정한 모든 데이터를 암호화
		$EncryptedData = $objPLParam->Encrypt();

		// POQ 결제페이지로 이동
		$strPayUrl = "https://pg1.payletter.com/PGSVC/" . $strPGHostURL . "?clientparam=" . $EncryptedData;
		echo ("<SCRIPT LANGUAGE='JavaScript'>location.href='$strPayUrl';</SCRIPT>");
	}

	/**
	 * 결과 페이지는 DB처리를 하지 않고 보여주기 위한 목적이다. 저장은 noti
	 * 카드, 계좌이체 결과
	 */
	public function result(){

		//-------------------------------------------------------------
		// 암호화모듈 PLParamEXECV2 require
		//-------------------------------------------------------------
		require("Lib/PLParamEXECV2.php");  // PLParamEXECV2.php 인클루드(plaescmd 이용시)

		//-------------------------------------------------------------
		// 암호화 키 관리 파일 require
		//-------------------------------------------------------------
		require("key/keyconf.php");  // POQKey.php 파일에 POQ로 부터 부여 받는 알맞는 값을 셋팅하여야 합니다.

		//-------------------------------------------------------------
		// Description		: 결제 정보 복호화
		//-------------------------------------------------------------

		//--PLParam Component Instance생성
		$objPLParam = new PLParamV2();

		//--암호화 키 관련 Property 추가
		$objPLParam->SetKey($szPVKey);
		$objPLParam->SetKeyID($szKeyID);
		$objPLParam->SetKeyVer($szKeyVer);
		
		//--페이레터의 암호화된 결제결과(clientparam)을 받아서 resultparam에 설정한다.
		$objPLParam->SetParam("resultparam" ,$_REQUEST["clientparam"]);
		
		$data["result"] = Array(
			"result"=> $objPLParam->GetParam("result"),
			"ordno"	=> $objPLParam->GetParam("ordno"),
			"pname" => iconv("EUC-KR","UTF-8",$objPLParam->GetParam("pname")),
			"pltid" => $objPLParam->GetParam("pltid"),
			"price"	=> $objPLParam->GetParam("amt"),
			"date"	=> $objPLParam->GetParam("txdate")."/".substr($objPLParam->GetParam("txtime"),0,2)."시".substr($objPLParam->GetParam("txtime"),2,2)."분".substr($objPLParam->GetParam("txtime"),4,2)."초",
			"cardname"	=> iconv("EUC-KR","UTF-8",$objPLParam->GetParam("cardname")),
			"errcode"	=> $objPLParam->GetParam("errcode"),
			"errmsg"	=> iconv("EUC-KR","UTF-8",$objPLParam->GetParam("errmsg"))
		);

		$this->load->view("basic/pay_result",$data);
	}

	/**
	 * 카드, 계좌이체 noti url
	 * - 가상계좌는 result 가 OK일 때 성공
	 */
	public function noti(){

		//-------------------------------------------------------------
		// 암호화모듈 PLParamEXECV2 require
		//-------------------------------------------------------------
		require("Lib/PLParamEXECV2.php");  // PLParamEXECV2.php 인클루드(plaescmd 이용시)

		//-------------------------------------------------------------
		// 암호화 키 관리 파일 require
		//-------------------------------------------------------------
		require("key/keyconf.php");  // POQKey.php 파일에 POQ로 부터 부여 받는 알맞는 값을 셋팅하여야 합니다.

		//-------------------------------------------------------------
		// Description		: 결제 정보 복호화
		//-------------------------------------------------------------

		//--PLParam Component Instance생성
		$objPLParam = new PLParamV2();

		//--암호화 키 관련 Property 추가
		$objPLParam->SetKey($szPVKey);
		$objPLParam->SetKeyID($szKeyID);
		$objPLParam->SetKeyVer($szKeyVer);
		
		//--페이레터의 암호화된 결제결과(clientparam)을 받아서 resultparam에 설정한다.
		$objPLParam->SetParam("resultparam" ,$_REQUEST["clientparam"]);
		
		log_message('debug', 'noti result:'.$objPLParam->GetParam("result"));

		if($objPLParam->GetParam("result") == "OK"){

			$ordno = $objPLParam->GetParam("ordno");
			$member_id = $objPLParam->GetParam("userid");

			$this->load->model("Mpay");

			$day = date('Y-m-d H:i:s');

			$paying_info = $this->Mpay->last_valid_pay($member_id);
			$pay_info = $this->Mpay->get($ordno);

			if($paying_info){ //이미 사용중인 결제정보가 있을 경우
				$start_date = $paying_info->end_date;
				$end_date = date("Y-m-d H:i:s", strtotime($paying_info->end_date." +".$pay_info->use_day." day"));
			}
			else{ //없을 경우 현재날짜 기준
				$start_date = $day;
				$end_date = date("Y-m-d H:i:s", strtotime($day." +".$pay_info->use_day." day"));
			}

			$param = Array(
				"pltid"			=>	$objPLParam->GetParam("pltid"),
				"tid"			=>	$objPLParam->GetParam("tid"),
				"cid"			=>	$objPLParam->GetParam("cid"),
				"price"			=>	$objPLParam->GetParam("amt"),
				"pgname"		=>  iconv("EUC-KR","UTF-8",$objPLParam->GetParam("pgname")),
				"cardname"		=>	iconv("EUC-KR","UTF-8",$objPLParam->GetParam("cardname")),
				"dealno"		=>  $objPLParam->GetParam("dealno"),
				"receiptcid"	=>  $objPLParam->GetParam("receiptcid"),
				"paysid"		=>  $objPLParam->GetParam("paysid"),
				"gubun"			=>  $objPLParam->GetParam("gubun"),
				"receiptissuegubun"	=>  $objPLParam->GetParam("receiptissuegubun"),
				"receipterrcode"	=>  $objPLParam->GetParam("receipterrcode"),
				"receipterrmsg"	=>  iconv("EUC-KR","UTF-8",$objPLParam->GetParam("receipterrmsg")),
				"start_date"	=>  $start_date,
				"end_date"		=>  $end_date,
				"payed_date"	=> 	date('Y-m-d H:i:s'),
				"state"			=> "Y"
			);

			$this->Mpay->update($ordno,$param);

			//회원이 가지고 있는 결제유효일 업데이트
			$member_param = Array(
				"end_date"	=> $end_date
			);
			$this->load->model("Mmember");
			$this->Mmember->update($member_id,$member_param);		

			$this->check_all_activated($member_id);

			echo "<RESULT>OK</RESULT>";
		} else {
			echo "<RESULT>FAIL</RESULT>";
		}

	}

	/**
	 * 결과 페이지는 DB처리를 하지 않고 보여주기 위한 목적이다. 저장은 notivacct
	 * 가상계좌 결과
	 */
	public function resultvacct(){

		//-------------------------------------------------------------
		// 암호화모듈 PLParamEXECV2 require
		//-------------------------------------------------------------
		require("Lib/PLParamEXECV2.php");  // PLParamEXECV2.php 인클루드(plaescmd 이용시)

		//-------------------------------------------------------------
		// 암호화 키 관리 파일 require
		//-------------------------------------------------------------
		require("key/keyconf.php");  // POQKey.php 파일에 POQ로 부터 부여 받는 알맞는 값을 셋팅하여야 합니다.

		//-------------------------------------------------------------
		// Description		: 결제 정보 복호화
		//-------------------------------------------------------------

		//--PLParam Component Instance생성
		$objPLParam = new PLParamV2();

		//--암호화 키 관련 Property 추가
		$objPLParam->SetKey($szPVKey);
		$objPLParam->SetKeyID($szKeyID);
		$objPLParam->SetKeyVer($szKeyVer);
		
		//--페이레터의 암호화된 결제결과(clientparam)을 받아서 resultparam에 설정한다.
		$objPLParam->SetParam("resultparam" ,$_REQUEST["clientparam"]);
		
		$data["result"] = Array(
			"result"=> $objPLParam->GetParam("result"),
			"ordno"	=> $objPLParam->GetParam("ordno"),
			"accountno" => $objPLParam->GetParam("accountno"),
			"accountname" => iconv("EUC-KR","UTF-8",$objPLParam->GetParam("accountname")),
			"bankname"	=> iconv("EUC-KR","UTF-8",$objPLParam->GetParam("bankname")),
			"price"		=> $objPLParam->GetParam("amt"),
			"expymd"	=> $objPLParam->GetParam("expymd"),
			"errcode"	=> $objPLParam->GetParam("errcode"),
			"errmsg"	=> iconv("EUC-KR","UTF-8",$objPLParam->GetParam("errmsg"))
		);

		$this->load->view("basic/pay_resultvacct",$data);

	}

	/**
	 * 가상계좌 noti url
	 * - 가상계좌는 result 가 0일 때 성공
	 */
	public function notivacct(){

		//-------------------------------------------------------------
		// 암호화모듈 PLParamEXECV2 require
		//-------------------------------------------------------------
		require("Lib/PLParamEXECV2.php");  // PLParamEXECV2.php 인클루드(plaescmd 이용시)

		//-------------------------------------------------------------
		// 암호화 키 관리 파일 require
		//-------------------------------------------------------------
		require("key/keyconf.php");  // POQKey.php 파일에 POQ로 부터 부여 받는 알맞는 값을 셋팅하여야 합니다.

		//-------------------------------------------------------------
		// Description		: 결제 정보 복호화
		//-------------------------------------------------------------

		//--PLParam Component Instance생성
		$objPLParam = new PLParamV2();

		//--암호화 키 관련 Property 추가
		$objPLParam->SetKey($szPVKey);
		$objPLParam->SetKeyID($szKeyID);
		$objPLParam->SetKeyVer($szKeyVer);
		
		//--페이레터의 암호화된 결제결과(clientparam)을 받아서 resultparam에 설정한다.
		$objPLParam->SetParam("resultparam" ,$_REQUEST["clientparam"]);
	
		if($objPLParam->GetParam("result") == "0"){

			$ordno = $objPLParam->GetParam("ordno");
			$member_id = $objPLParam->GetParam("userid");

			$this->load->model("Mpay");

			$day = date('Y-m-d H:i:s');

			$paying_info = $this->Mpay->last_valid_pay($member_id);
			$pay_info = $this->Mpay->get($ordno);

			if($paying_info){ //이미 사용중인 결제정보가 있을 경우
				$start_date = $paying_info->end_date;
				$end_date = date("Y-m-d H:i:s", strtotime($paying_info->end_date." +".$pay_info->use_day." day"));
			}
			else{ //없을 경우 현재날짜 기준
				$start_date = $day;
				$end_date = date("Y-m-d H:i:s", strtotime($day." +".$pay_info->use_day." day"));
			}

			$param = Array(
				"pltid"			=>	$objPLParam->GetParam("pltid"),
				"tid"			=>	$objPLParam->GetParam("tid"),
				"cid"			=>	$objPLParam->GetParam("cid"),
				"price"			=>	$objPLParam->GetParam("amt"),
				"pgname"		=>  iconv("EUC-KR","UTF-8",$objPLParam->GetParam("pgname")),
				"accountno"		=>  $objPLParam->GetParam("accountno"),
				"accountname"	=>	$objPLParam->GetParam("accountname"),
				"bankname"		=>  $objPLParam->GetParam("bankname"),
				"receiptcid"	=>  $objPLParam->GetParam("receiptcid"),
				"paysid"		=>  $objPLParam->GetParam("paysid"),
				"gubun"			=>  $objPLParam->GetParam("gubun"),
				"receiptissuegubun"	=>  $objPLParam->GetParam("receiptissuegubun"),
				"receipterrcode"	=>  $objPLParam->GetParam("receipterrcode"),
				"receipterrmsg"	=>  $objPLParam->GetParam("receipterrmsg"),
				"dealno"		=> 	$objPLParam->GetParam("dealno"),
				"start_date"	=>  $start_date,
				"end_date"		=>  $end_date,
				"payed_date"	=>	date('Y-m-d H:i:s'),
				"state"			=> "Y"
			);


			$this->Mpay->update($ordno,$param);

			//회원이 가지고 있는 결제유효일 업데이트
			$member_param = Array(
				"end_date"	=> $end_date
			);
			$this->load->model("Mmember");
			$this->Mmember->update($member_id,$member_param);

			$this->check_all_activated($member_id);

			echo "<RESULT>OK</RESULT>";
		} else {
			echo "<RESULT>FAIL</RESULT>";
		}

	}

	/**
	 * 회원의 공개된 매물갯수를 확인 후
	 * 이용건수 이하의 등록건은 공개로 변환
	 */
	public function check_all_activated($member_id){
		
		$this->load->model("Mmember");

		$count = $this->Mmember->get_product_count($member_id);
		$pay_info = $this->use_pay_info();

		if($pay_info){
			if($count <= $pay_info->use_count){
				$this->Mmember->update_all_activated($member_id);
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
}

/* End of file pay.php */
/* Location: ./application/controllers/pay.php */
