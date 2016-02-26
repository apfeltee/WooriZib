<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminpaysms extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 결제페이지
	 */
	public function index(){
		$data = array();
		$this->load->view('admin/pay_sms', $data, false);
	}

	/**
	 * 결제진행
	 */
	public function pay_action(){

		$this->load->model("Mpaysms");
		$this->load->model("Mconfig");

		$config = $this->Mconfig->get();

		//----------------------------------------------------------------------
		// 결제 넘기기 전에 필요한 데이터를 저장한다
		// - 가상계좌의 경우에는 입금확인이 필요하므로 상태를 W로 해서 저장한다.
		//----------------------------------------------------------------------
		$state = ($this->input->post("pgcode")=="18") ? "W": "N"; //가상계좌일 경우 W


		switch($this->input->post("sms_count")){
			case "1000" :
				$order_name = "문자 1000건";
				$price	= "22000";
				break;
			case "2000" :
				$order_name = "문자 2000건";
				$price	= "42000";
				break;
			case "3000" :
				$order_name = "문자 3000건";
				$price	= "62000";
				break;
		}

		$pay_param = Array(
			"order_name"=> $order_name,
			"pay_type"	=> $this->input->post("pgcode"),
			"sms_count"	=> $this->input->post("sms_count"),
			"price"		=> $price,
			"state"		=> $state,
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->Mpaysms->insert($pay_param);

		//주문번호
		$order_no = $this->db->insert_id();

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
		$objPLParam->SetParam("userid",     $_SERVER['HTTP_HOST']);				// 사용자아이디
		$objPLParam->SetParam("clientid",   "iwalker");							// 고객사아이디 (페이레터에서 부여한 아이디)
		$objPLParam->SetParam("mallid",     "PAYONEQCARD");						// 상점아이디
		$objPLParam->SetParam("amt",        $price);							// 결제금액
		$objPLParam->SetParam("svcnm",      iconv("UTF-8","EUC-KR", "문자충전"));	// 결제서비스명
		$objPLParam->SetParam("backurl",    "");								// "돌아가기"클릭시 돌아가는 페이지 URL(오픈창일경우 공백으로)
	
		// 선택 설정 데이터
		$objPLParam->SetParam("ordno",      $order_no);							// "주문번호"
		$objPLParam->SetParam("ordnm",      iconv("UTF-8","EUC-KR",$config->name));// "사용자명"
		$objPLParam->SetParam("pname",      iconv("UTF-8","EUC-KR", $order_name)); // "결제상품명"
		$objPLParam->SetParam("etcparam",   "");								// "기타정보" (필요한 정보를 설정하면 되돌려줍니다.)
		$objPLParam->SetParam("emailstate", "1");								// 결제 내역 메일 수신여부(0:미사용, 1:사용)
		$objPLParam->SetParam("email",		$config->email);					// 결제 내역을 수신할 메일주소(메일 수신 사용시 "emailstate"를 1로 세팅하시고 주석을 풀어 메일주소를 넣어주세요)

		// 노티 설정 데이터
		$objPLParam->SetParam("noti",       "1");	// 노티 방식 사용 여부(0: 미사용, 1: 사용)

		Switch ($this->input->post("pgcode")){
			Case(1) :   // 신용카드일 경우(allthegate)
				$objPLParam->SetParam("clinterest", "0");			// 신용카드 결제시 이자부담(0: 일반, 1: 고객사가 이자부담)[필수]
				//$objPLParam->SetParam("companynm", "xxxxxxxxx");	// "신용카드 결제시 안심클릭창에 나올 판매자명"(고객사 상호명을 기입해주세요, 미 세팅시 "payletter"로 세팅됩니다.)[선택]
				$strPGHostURL = "AllTheGate/ATGPayForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/adminpaysms/result");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/adminpaysms/noti" ); 
				break;

			Case(4) :   // 계좌이체(KFTC)
				$strPGHostURL = "KFTC/KFTCPayForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/adminpaysms/result");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/adminpaysms/noti" ); 
				break;

			Case(18) :  // 가상계좌(dacomvacct)
				//$objPLParam->SetParam("expymd", "20141212"); // 입금 만료일(선택, 기본 7일)
				$strPGHostURL = "DacomVaccount/VAccountAssignForm.asp";
				$objPLParam->SetParam("returnurl",  "http://".$_SERVER['HTTP_HOST']."/adminpaysms/resultvacct");
				$objPLParam->SetParam("notiurl",	"http://".$_SERVER['HTTP_HOST']."/adminpaysms/notivacct" ); 
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

		$this->load->view("admin/pay_sms_result",$data);
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

			$this->load->model("Mpaysms");

			$pay_info = $this->Mpaysms->get($ordno);

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
				"payed_date"	=> 	date('Y-m-d H:i:s'),
				"state"			=> "Y"
			);

			$this->Mpaysms->update($ordno,$param);

			//문자 잔여건수 업데이트
			$this->load->model("Mconfig");
			$config = $this->Mconfig->get();
			$sms_cnt = $pay_info->sms_count + $config->sms_cnt;
			$this->Mconfig->update(array("sms_cnt"=>$sms_cnt),"");		

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

		$this->load->view("admin/pay_resultvacct",$data);

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

			$this->load->model("Mpaysms");

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
				"payed_date"	=>	date('Y-m-d H:i:s'),
				"state"			=> "Y"
			);


			$this->Mpaysms->update($ordno,$param);

			//문자 잔여건수 업데이트
			$this->load->model("Mconfig");
			$config = $this->Mconfig->get();
			$sms_cnt = $pay_info->sms_count + $config->sms_cnt;
			$this->Mconfig->update(array("sms_cnt"=>$sms_cnt),"");
			
			echo "<RESULT>OK</RESULT>";
		} else {
			echo "<RESULT>FAIL</RESULT>";
		}
	}

	/**
	 * 문자 결제 내역
	 */
	public function pay_log(){

		$this->load->model("Mpaysms");

		$data['total'] = $this->Mpaysms->get_total();
		$data['query'] = $this->Mpaysms->get_list();

		$this->layout->admin('pay_sms_log', $data);
	}

}

/* End of file adminpaysms.php */
/* Location: ./application/controllers/adminpaysms.php */