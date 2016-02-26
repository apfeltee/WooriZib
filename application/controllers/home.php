<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 메인의 형태를 여러 형태로 가져가기 위한 구조
 */
class Home extends CI_Controller {

	public function __construct() {
		parent::__construct(); 

		if(MobileCheck()){
			$Us = '/mobile/';; //모바일이동경로
			$Ds = (strpos($Us,'?') !== false) ? '&' : '?';
			$Qs = ($_SERVER['QUERY_STRING'] ? $Ds.$_SERVER['QUERY_STRING'] : '');
			header('Location: '.$Us.$Qs);
			exit;
		}
	}

	/**
	 * 홈
	 */
	public function index($skin_page=false){

		/** 홈에 오면 검색 조건을 모두 초기화한다. **/
		$this->session->unset_userdata('search');
		$this->session->unset_userdata('search_installation');

		/** 홈을 사용하지 않으면 매물 검색 메뉴로 이동한다. **/
		if(!$this->config->item("use_home")){
			redirect("main/index","refresh");
		}

		$data["page_title"] =  "홈";

		$this->load->model("Mcategory");
		$this->load->model("Mproduct");
		
		$this->load->model("Menquire");
		$this->load->model("Mlayout");

		$data["enquire"] = $this->Menquire->get_home();

		$this->load->model("Mnotice");
		$data["notice"] = $this->Mnotice->get_recent();

		
		$this->load->library("Components");
		$data["home_layout"] = $this->components->get($this->Mlayout->get_list());

		//관리자의 색상 스킨 사용여부
		if($skin_page && $this->session->userdata("admin_id")) $this->session->set_userdata("skin_control",true);
		else $this->session->set_userdata("skin_control",false);

		$this->layout->view(THEME.'/home_index',$data);			

		$this->load->helper("check");

		/*** LOG START ***/
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get("ip");
		if($this->input->ip_address()!=$config->ip){
			$this->load->model("Mlog");
			$this->load->library('user_agent');
			if($this->session->userdata("session_cnt")==""){ $this->session->set_userdata("session_cnt",1); }
			else { $this->session->set_userdata("session_cnt", $this->session->userdata("session_cnt") + 1); }

			$param = Array(
				"session_id"=> $this->session->userdata("session_id"),
				"session_cnt"=> $this->session->userdata("session_cnt"),
				"user_agent"=> $this->session->userdata("user_agent"),
				"user_referrer"=> $this->agent->referrer(),
				"ip"		=> $this->input->ip_address(),
				"type"		=> "home",
				"mobile"	=> MobileCheck(), 
				"data_id"	=> "0",
				"date"		=> date('Y-m-d H:i:s')
			);
			$this->Mlog->add($param);
			
		}
		/*** LOG START ***/
	}

	function recent($category,$line=0){
		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$data["recent"] = $this->Mproduct->get_recent($category,$line);
		//최신 매물 보여주기
		foreach($data["recent"] as $key=>$val){
			$data["recent"][$key]["subway"] = $this->Mproduct->get_product_subway($val["id"]);
			$data["recent"][$key]["add_price"] = $this->Madminproduct->get_add_price($val["id"]);
		}
		$result["product"] = $data["recent"];
		$result["config"] = $config;

		// $config->LISTING : 1,2,3(기본)
		echo $this->load->view("templates/listing_" . $config->LISTING ,$result, true);
	}

	/**
	 * 이용 약관
	 */
	function rule(){
		$data["page_title"] =  "이용약관";
		$this->layout->view('basic/home_rule',$data);
	}

	/**
	 * 개인정보취급방침
	 */
	function privacy(){
		$data["page_title"] =  "개인정보취급방침";
		$this->layout->view('basic/home_privacy',$data);
	}

	/**
	 * 위치정보약관
	 */
	function location(){
		$data["page_title"] =  "위치정보약관";
		$this->layout->view('basic/home_location',$data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
