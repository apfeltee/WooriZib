<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 메인의 형태를 여러 형태로 가져가기 위한 구조
 */
class Json extends CI_Controller {

	public function type_json(){
		$this->load->model("Mcategory");
		echo json_encode($this->Mcategory->get_list_cnt());
	}

	/**
	 * 카테고리 목록 가져오기
	 */
	public function category_json(){
		$this->load->model("Mcategory");
		$data["categories"] = $this->Mcategory->get_list_cnt();
		echo json_encode($data);
	}

	/**
	 * 매물 상세에서 추가 입력 필드에 입력한 내용을 보여주기 위해서 해당 카테고리의 메타 정보를 가져감
	 */
	public function category_meta_json($category){
		$this->load->model("Mcategory");
		$metas = $this->Mcategory->get($category);
		$data["metas"] = explode(",",$metas->meta);
		echo json_encode($data);
	}

	/**
	 * 매물 목록, 검색 결과, 지하철, 매물 유형, 가격대, 테마, 매매 유형 등 다양한 조건에 의한 매물 목록을 보여줘야 한다. 모든 것을 파라미터로 넘기면 되려나?
	 */
	public function properties_json(){
		$this->load->model("Mproduct");
		$per_page=6;

		$category = "";
		if($this->input->post("category")!="undefined" && $this->input->post("category")!=""){
			$category = Array($this->input->post("category"));
		}

		$param = Array(
			"type" => $this->input->post("type"),
			"sell_start" => $this->input->post("sell_start"),
			"sell_end" => $this->input->post("sell_end"),
			"full_start" => $this->input->post("full_start"),
			"full_end" => $this->input->post("price_2_end_value"),
			"month_deposit_start" => $this->input->post("month_deposit_start"),
			"month_deposit_end" => $this->input->post("month_deposit_end"),
			"month_start" => $this->input->post("month_start"),
			"month_end" => $this->input->post("month_end"),
			"theme"=>$this->input->post("theme"),
			"category"=>$category,
			"search_type"=>$this->input->post("search_type"),
			"search_value"=>$this->input->post("search_value"),
			"search"=>$this->input->post("search"),
			"member" => "",
			"law_area"	=>$this->input->post("law_area"),
			"real_area"	=>$this->input->post("real_area"),
			"sorting"=>$this->input->post("sorting")
		);
		
		$query["total"] = $this->Mproduct->get_total_count($param);
		$query["posts"] = $this->Mproduct->get_list($param, $per_page, $this->input->post("page"));
		echo json_encode($query);
	}

	/**
	 * 찜 매물 보기
	 */
	public function favorite_json($page){
		$this->load->model("Mhope");
		$per_page=20;
		$query["total"] = $this->Mhope->get_total($this->session->userdata("session_id"));
		$query["favorites"]	=  $this->Mhope->get_list($this->session->userdata("session_id"),$per_page, $page);
		echo json_encode($query);
	}

	/**
	 * 본 매물 보기
	 */
	public function seen_json($page){
		$this->load->model("Mlog");
		$per_page=20;
		$query["total"] = $this->Mlog->get_total($this->session->userdata("session_id"));
		$query["seens"]	=  $this->Mlog->get_list($this->session->userdata("session_id"),$per_page, $page);
		echo json_encode($query);
	}

	/**
	 * 지도에 매물을 표시해 주기 위함
	 * 성능문제로 아래 map_server_json를 사용하고 이건 사용하지 않는다.
	 */
	public function map_json($lat_s, $lat_e, $lng_s, $lng_e){

		$category = "";
		if($this->input->post("category")!="undefined" && $this->input->post("category")!=""){
			$category = Array($this->input->post("category"));
		}

		$param = Array(
			"type" => $this->input->post("type"),
			"sell_start" => $this->input->post("sell_start"),
			"sell_end" => $this->input->post("sell_end"),
			"full_start" => $this->input->post("full_start"),
			"full_end" => $this->input->post("full_end"),
			"month_deposit_start" => $this->input->post("month_deposit_start"),
			"month_deposit_end" => $this->input->post("month_deposit_end"),
			"month_start" => $this->input->post("month_start"),
			"month_end" => $this->input->post("month_end"),
			"theme"=>$this->input->post("theme"),
			"category"=>$category,
			"search_type"=>$this->input->post("search_type"),
			"search_value"=>$this->input->post("search_value"),
			"search"=>$this->input->post("search"),
			"member" => "",			
			"sorting"=>$this->input->post("sorting")
		);
	
		$this->load->model("Mproduct");
		$query["markers"] = $this->Mproduct->get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $param);
		echo json_encode($query);
	}

	public function map_server_json($zoom, $lat_s, $lat_e, $lng_s, $lng_e){
	
		//검색조건
		$category = "";
		if($this->input->post("category")!="undefined" && $this->input->post("category")!=""){
			$category = Array($this->input->post("category"));
		}

		$param = Array(
			"type" => $this->input->post("type"),
			"sell_start" => $this->input->post("sell_start"),
			"sell_end" => $this->input->post("sell_end"),
			"full_start" => $this->input->post("full_start"),
			"full_end" => $this->input->post("full_end"),
			"month_deposit_start" => $this->input->post("month_deposit_start"),
			"month_deposit_end" => $this->input->post("month_deposit_end"),
			"month_start" => $this->input->post("month_start"),
			"month_end" => $this->input->post("month_end"),
			"theme"=>$this->input->post("theme"),
			"category"=>$category,
			"search_type"=>$this->input->post("search_type"),
			"search_value"=>$this->input->post("search_value"),
			"search"=>$this->input->post("search"),
			"member" => "",
			"law_area"	=>$this->input->post("law_area"),
			"real_area"	=>$this->input->post("real_area"),			
			"sorting"=>$this->input->post("sorting")
		);

		$this->session->set_userdata("search",$param);

		//지하철
		if($zoom > 11) {
			$this->load->model("Mproduct");
			$data["subway"] = $this->Mproduct->get_subway_list($lat_s, $lat_e, $lng_s, $lng_e);
		}

		//마커
		define('OFFSET', 268435456);
		define('RADIUS_1', 85445659.4471); /* $offset / pi() */
		$this->load->helper("map");
		$this->load->model("Mproduct");
		$query = $this->Mproduct->get_all_server_latlng($lat_s, $lat_e, $lng_s, $lng_e, $param);
		$data["markers"] = cluster_count($query,50,$zoom);
		
		echo json_encode($data);
	}

	public function cluster_json($id, $zoom, $x, $y, $lat_s, $lat_e, $lng_s, $lng_e){

		if($id==""){
			echo "1";
			$data["markers"] = "";
			$data["total"] = "0";
		} else {

			$this->load->model("Mproduct");
			$this->load->helper("map");
			define('OFFSET', 268435456);
			define('RADIUS_1', 85445659.4471); /* $offset / pi() */
			$query = $this->Mproduct->get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
			$data["markers"] = cluster_list($id, $x, $y,$query,50,$zoom);
			$data["total"] = "10";
		}
		echo json_encode($data);		
	}

	/**
	 * 해당 매물의 사진들을 가져온다.
	 */
	public function gallery_json($id){
		
		$this->load->model("Mlog");
		
		if($this->session->userdata("session_cnt")==""){ 
			$this->session->set_userdata("session_cnt",1); 
		}
		else 
		{ 
			$this->session->set_userdata("session_cnt", $this->session->set_userdata("session_cnt") + 1); 
		}
		$this->load->library('user_agent');
		$param = Array(
				"session_id"=> $this->session->userdata("session_id"),
				"session_cnt"=> $this->session->userdata("session_cnt"),
				"user_agent"=> $this->session->userdata("user_agent"),
				"user_referrer"=> $this->agent->referrer(),
				"ip"		=> $this->input->ip_address(),
				"type"		=> "product",
				"mobile"	=> '3', 
				"data_id"	=> $id,
				"date"		=> date('Y-m-d H:i:s')
		);
		$this->Mlog->add($param);

		$this->load->model("Mgallery");
		$query["galleries"] = $this->Mgallery->get_list($id);
		echo json_encode($query);

		//로그 남기기
		$this->load->model("Mlog");
		$this->load->library('user_agent');
	}

	public function subway_recent_json($lat,$lng){
		$this->load->model("Msubway");
		$query["subways"] = $this->Msubway->get_recent_list($lat,$lng);
		echo json_encode($query);
	}

	/**
	 * 매물이 등록되어 있는 지하철역 정보를 반환한다.
	 */
	public function subway_json(){
		$this->load->model("Msubway");
		$query["hosuns"] = $this->Msubway->get_registered_hosun();
		$query["subways"] = $this->Msubway->get_registered_list();
		echo json_encode($query);	
	}

	/**
	 * 매물의 지하철 정보를 반환하여 준다.
	 */
	public function subway_one_json($id){
		$this->load->model("Mproduct");
		$query["subways"] = $this->Mproduct->get_product_subway($id);
		echo json_encode($query);		
	}

	/**
	 * 매물이 등록되어 있는 지하철역 정보를 반환한다.
	 */
	public function local_json(){
		$this->load->model("Maddress");
		$query["sidos"] = $this->Maddress->get_local("sido");
		$query["guguns"] = $this->Maddress->get_local("gugun");
		$query["dongs"] = $this->Maddress->get_local("dong");
		echo json_encode($query);	
	}

	public function dong_recent_json($lat,$lng){
		$this->load->model("Maddress");
		$query["dongs"] = $this->Maddress->get_recent_list($lat,$lng);
		echo json_encode($query);
	}

	/**
	 * 부동산의 정보를 표시
	 */
	public function about_json(){
		$this->load->model("Mconfig");
		$query["abouts"] = $this->Mconfig->get();
		echo json_encode($query);	
	}

	public function hope_json($id){
		$this->load->model("Mhope");
		$query["hope"] = $this->Mhope->check($id);
		echo json_encode($query);	
	}

	public function hope_add($id){
		$this->load->model("Mhope");
		$param = Array(
				"session_id"=>$this->session->userdata("session_id"),
				"product_id"=>$id,
				"date"=>date('Y-m-d H:i:s')
			);
		$this->Mhope->add($param);
	}

	public function hope_remove($id){
		$this->load->model("Mhope");
		$this->Mhope->remove($id,$this->session->userdata("session_id"));
	}

	public function price_setting_json(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$query["price"] = Array(
				"sellprice"=>$config->SELL_MAX,
				"installationprice"=>$config->SELL_MAX,
				"fullrentprice"=>$config->FULL_MAX,
				"rentdepositprice"=>$config->MONTH_DEPOSIT_MAX,
				"rentmonthprice"=>$config->MONTH_MAX
			);
		echo json_encode($query);
	}

	/**
	 * App에서 회원 로그인
	 *
	 */
	public function login_action($email, $password){
		$pw = $this->_prep_password($this->input->post("password"));
		$this->load->model("Mmember");
		$result = $this->Mmember->check_login($this->input->post("email"),$pw);
		if($result!=null){
			if($result->valid=='N'){
				$data["id"] = "";
				
			} else {

				$this->session->set_userdata("email",$result->email);
				$this->session->set_userdata("id",$result->id);
				$this->session->set_userdata("type",$result->type);
				$this->session->set_userdata("biz_name",$result->biz_name);
				$this->session->set_userdata("name",$result->name);
				$this->session->set_userdata("phone",$result->phone);
				$this->session->set_userdata("tel",$result->tel);
				$this->session->set_userdata("kakao",$result->kakao);

				$data["id"] = $result->id;
			}
		} else {
			
			$data["id"] = "";
		}

		echo json_encode($data);
	}

	private function _prep_password($password){
		 return sha1($password.$this->config->item('encryption_key'));
	}

}

/* End of file json.php */
/* Location: ./application/controllers/json.php */
