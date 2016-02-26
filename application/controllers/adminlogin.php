<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminlogin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($code=""){
		$this->session->unset_userdata('search');
		$data["code"] = $code;
		$this->layout->admin('login_index',$data);
	}

	/**
	 *  로그인 실행
	 */
	public function login_action(){
		$pw = $this->_prep_password($this->input->post("pw"));
		$this->load->helper('cookie'); 
		if($this->input->post("save")=="on"){
			$cookie1 = array(
                   'name'   => 'admin_id',
                   'value'  => $this->input->post("email"),
                   'expire' => '8650000',
                   'domain' => HOST,
                   'path'   => '/',
                   'prefix' => 'wb_',
               );

			$cookie2 = array(
                   'name'   => 'admin_save',
                   'value'  => "on",
                   'expire' => '8650000',
                   'domain' => HOST,
                   'path'   => '/',
                   'prefix' => 'wb_',
               );
			set_cookie($cookie1); 
			set_cookie($cookie2);

		} else {
			delete_cookie('admin_id',HOST,'/','wb_');
			delete_cookie('admin_save',HOST,'/','wb_');
		}


		$this->load->model("Mmember");

		$result = $this->Mmember->check_login($this->input->post("email"),$pw,'admin');

		if($result!=null){
			$this->session->set_userdata("admin_id",$result->id);		//회원번호
			$this->session->set_userdata("admin_type",$result->type);	//회원구분
			$this->session->set_userdata("admin_name",$result->name);	//회원명
			$this->session->set_userdata("admin_email",$result->email);	//이메일
			$this->session->set_userdata("admin_phone",$result->phone);	//연락처

			//관리자권한
			$this->session->set_userdata("auth_id",$result->auth_id);				//권한그룹
			$this->session->set_userdata("auth_home",$result->auth_home);			//홈
			$this->session->set_userdata("auth_product",$result->auth_product);		//매물관리
			$this->session->set_userdata("auth_member",$result->auth_member);		//회원관리
			$this->session->set_userdata("auth_contact",$result->auth_contact);		//고객관리
			$this->session->set_userdata("auth_request",$result->auth_request);		//의뢰관리
			$this->session->set_userdata("auth_news",$result->auth_news);			//뉴스관리
			$this->session->set_userdata("auth_portfolio",$result->auth_portfolio);	//포트폴리오관리
			$this->session->set_userdata("auth_set",$result->auth_set);				//설정관리
			$this->session->set_userdata("auth_custom",$result->auth_custom);		//커스텀관리
			$this->session->set_userdata("auth_popup",$result->auth_popup);			//팝업관리
			$this->session->set_userdata("auth_layout",$result->auth_layout);		//레이아웃설정
			$this->session->set_userdata("auth_stats",$result->auth_stats);			//통계
			$this->session->set_userdata("auth_pay",$result->auth_pay);				//결제관리

			//모바일인지 여부를 추가한다.
			$mobile_member = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';
			if(preg_match($mobile_member, $_SERVER['HTTP_USER_AGENT'])) {
				$this->session->set_userdata("is_mobile","1");
			} else {
				$this->session->set_userdata("is_mobile","0");			
			}
			
			//로그인에 따른 페이지 이동 처리
			if($result->auth_home=="Y"){
				$this->session->set_flashdata('sub-menu', 'menu-home');//좌측메뉴(홈)
				echo "home";
			}
			else{
				$this->session->set_flashdata('sub-menu', 'menu-product-1');//좌측메뉴(매물관리)
				echo "product";
			}			

		} else {//로그인 실패
			$member_info = $this->Mmember->get_by_email($this->input->post("email"),"admin");
			if(count($member_info) > 0){
				echo ($member_info->valid=='N') ? "valid" : "fail";
			}
			else{
				echo "fail";
			}
		}
	}

	/**
	 *  로그아웃 실행
	 */
	function logout(){
		$this->session->sess_destroy();
		redirect("adminlogin/index","refresh");
	}

	private function _prep_password($password)
	{
		 return sha1($password.$this->config->item('encryption_key'));
	}

	/**
	 *  관리자 간단 회원가입
	 */
	public function simple_add_action(){

		$this->load->model("Mmember");

		//email 유효성체크
		if($this->input->post("email")){
			$cnt = $this->Mmember->have_email($this->input->post("email"));
			if($cnt){
				echo "0";
				exit;
			}
		}
		else{
			echo "0";
			exit;
		}

		if(is_array($this->input->post())){
			$param = Array(
				"name"		=> $this->input->post("name"),
				"type"		=> "admin", //직원유형
				"auth_id"	=> 2, //직원권한 default
				"kakao"	=> $this->input->post("kakao"),
				"email" => $this->input->post("email"),
				"pw"	=> $this->_prep_password($this->input->post("password")),
				"phone" => $this->input->post("phone"),
				"tel"	=> $this->input->post("tel"),
				"valid"	=> "N", //승인여부
				"date"	=> date('Y-m-d H:i:s')
			);

			$this->Mmember->insert($param);
			$result = $this->db->insert_id();
			echo ($result) ? "1" : "0";
		}
	}

	/**
	 * 관리자 이메일 가입 여부 확인
	 */
	function check_email(){
		$email = $this->input->post("email");
		$this->load->model("Mmember");
		if($this->Mmember->have_email($email)){
			echo "false";
		} else {
			echo "true";		
		}
	}

	/**
	 * 관리자 이메일 분실
	 */
	function forget_action(){

		$email = $this->input->post("email");
		$this->load->model("Mmember");
		$random_pw = substr($this->_prep_password(microtime()),0,10); 
		$param = Array(
			"pw" => $this->_prep_password($random_pw)
		);
		$this->Mmember->email_update($email,$param);
		$this->load->helper("sender");
		pw_send("webplug@gmail.com","dungzi",$email,$random_pw);
		echo '1';
	}
}

/* End of file adminlogin.php */
/* Location: ./application/controllers/adminlogin.php */