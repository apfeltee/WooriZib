<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Product extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	/**
	 * Modal로 제품을 보여줄 때 동작한다.
	 */
	public function view_modal(){
		$id = $this->input->post("id");

		$this->load->library("Productview");
		$data = $this->productview->_get($id);

		//대출정보		
		if($data["query"]->type=="sell"){
			$this->load->model("Mloan");
			$data['loan'] = $this->Mloan->get_list();
			foreach($data['loan'] as $val){
				$val->loan_limit = $data['query']->sell_price * $val->rate_loan/100;
			}
		}		

		$this->layout->setLayout("list");
		$query["result"] = $this->layout->view('basic/product_view_modal',$data, true);
		$query["product"] = $data["query"];
		$query["near_data"] = (isset($data["near_data"])) ? $data["near_data"] : "";
		$query["store_data"] = ($data["store_data"]) ? $data["store_data"] : "";
		$query["first_gallery_id"] = (isset($data["gallery"][0])) ? $data["gallery"][0]->id : "";
		
		echo json_encode($query);
	}

	/**
	 * 상품 보기
	 */
	public function view($id=""){
		
		if($id==""){
			redirect("my404","refresh");
			exit;
		}

		$this->load->library("Productview");
		$data = $this->productview->_get($id);

		if($data["query"]==null){
			redirect("my404","refresh");
			exit;
		}
		if($data["query"]->category_opened=="N" && !$this->session->userdata("id")){
			redirect("/member/signin","refresh");
			exit;		
		}
		else{
			if($this->session->userdata("permit_area")){
				$permit_area = @explode(",",$this->session->userdata("permit_area"));
				if(!in_array($data["query"]->parent_id,$permit_area)){
					redirect("/","refresh");
					exit;					
				}
			}
		}

		//대출정보		
		if($data["query"]->type=="sell"){
			$this->load->model("Mloan");
			$data['loan'] = $this->Mloan->get_list();
			foreach($data['loan'] as $val){
				$val->loan_limit = $data['query']->sell_price * $val->rate_loan/100;
			}
		}

		$this->layout->view('basic/product_view',$data);
	}

	/**
	 * 전환율 계산을 위한 로그 분석 스크립트 호출
	 */
	public function view_log($id){
		require_once(HOME.'/uploads/script/logs_target.php');
	}

	public function hope_action($id){
		$this->load->model("Mproduct");
		$param =  Array(
			"session_id"	=> $this->session->userdata("session_id"),
			"member_id"		=> $this->session->userdata("id"),
			"product_id"	=> $id,
			"date"			=> date('Y-m-d H:i:s')
		);
		$this->Mproduct->add_home($param);
		echo "1";
	}

	public function have_num($id){
		$this->load->model("Mproduct");
		$product = $this->Mproduct->get($id);
		if($product!=null){
			echo "1";
		} else {
			echo "0";
		}
	}

	/**
	 * 인근 정보 (code)
	 */
	 public function local($lat,$lng,$code=""){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

	 	if($lat!=""&&$lng!=""&&$code!=""){
			$key = $config->DAUM;
			$url = "https://apis.daum.net/local/v1/search/category.json?location=".$lat.",".$lng."&radius=1000&code=".$code."&sort=2&apikey=".$key."";
			echo get_url($url);
		}
	 }

	 public function add_call_view($product_id,$member_id){
	 	$this->load->model("Mlog");
	 	$this->load->library('user_agent');

		$param =  Array(
			"user_agent"	=> $this->session->userdata("user_agent"),
			"member"		=> $member_id,
			"product_id"	=> $product_id,
			"date"			=> date('Y-m-d H:i:s')
		);
		$this->Mlog->add_call($param);
		echo "1";
	 }

	 public function get_call_log($member_id=""){
	 	$this->load->model("Mlog");
		$param = Array(
			"member"=>$member_id
		);
		$param = array_filter($param);
		echo json_encode($this->Mlog->get_call_log("today",$param));
	 }

}

/* End of file product.php */
/* Location: ./application/controllers/product.php */