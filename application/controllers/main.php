<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {

	public function index(){
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
				"type"		=> "category",
				"mobile"	=> MobileCheck(), 
				"data_id"	=> "0",
				"date"		=> date('Y-m-d H:i:s')
			);
			$this->Mlog->add($param);
			
		}
		/*** LOG START ***/


		$param = "";

		/*** 지도를 사용하지 않을 경우에는 무조건 grid로 이동한다. ***/

		$config = $this->Mconfig->get();

		if($config->MAP_USE){


			 if(strpos($_SERVER['HTTP_USER_AGENT'], 'Dungzi/') !== false){
				if($config->M_MAP_BIG=="1"){
					redirect("main/map".$param,"refresh");
				} else {
					redirect("main/grid".$param,"refresh");
				}		
			 } else if(MobileCheck()){
				if($config->M_MAP_BIG=="1"){
					redirect("main/map".$param,"refresh");
				} else {
					redirect("main/grid".$param,"refresh");
				}		
			} else {
				if($config->MAP_BIG=="1"){
					redirect("main/map".$param,"refresh");
				} else {
					redirect("main/grid".$param,"refresh");
				}		
			}

		} else {
			redirect("main/grid".$param,"refresh");
		}
	}


	/**
	 * 지도 
	 */
	public function map(){

		$this->load->model("Maddress");
		$this->load->model("Mconfig");
		$this->load->model("Mregion");

		$config = $this->Mconfig->get();

		/*** 지도를 사용하지 않을 경우에는 무조건 grid로 이동한다. ***/
		if(!$config->MAP_USE){
			redirect("main/grid","refresh");
		}

		if($config->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("member/signin","refresh");
		}

		$data["page_title"] =  "매물 검색(지도)";

		$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();
		foreach($data["category"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["category"][$key]->category_sub = $category_sub;
			}
		}

		$this->load->model("Mtheme");
		$data["theme"] = $this->Mtheme->get_list();
		$data["search"] = $this->session->userdata("search");

		if($data["search"]["theme"]){
			$query_theme = @explode(",",$data["search"]["theme"]);
			foreach($data["theme"] as $val){
				if(in_array($val->id,$query_theme)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$this->load->model("Mdanzi");
		$data["danzi"] = $this->Mdanzi->get_danzi_name();

		$this->load->model("Msubway");
		$data["local"] = $this->Msubway->get_local();
		foreach($data["local"] as $val){
			if($val->local==1) $val->local_text = toeng("수도권");
			if($val->local==2) $val->local_text = toeng("부산");
			if($val->local==3) $val->local_text = toeng("대구");
			if($val->local==4) $val->local_text = toeng("광주");
			if($val->local==5) $val->local_text = toeng("대전");
		}
		$data["subway_line"] = $this->Msubway->get_registered_list_hosun();

		if($data["search"]["subway_line"]){
			$query_subway_line = @explode(",",$data["search"]["subway_line"]);
			foreach($data["subway_line"] as $val){
				if(in_array($val->hosun_id,$query_subway_line)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$data["sido"] = $this->Maddress->get_sido();

		if($config->REGION_USE){
			$data["region"] = $this->Mregion->get_list();
		}

		$this->layout->view('basic/main_map',$data);

	}

	/**
	 * 그리드
	 *
	 * 2015년 09월 20일 - 뉴스 명을 가져와서 보여주는 기능을 추가하였다. 강대중.
	 * 2016년 01월 31일 - 템플릿 기능 추가
	 */
	public function grid($skin_type="0",$skin_id="0"){

		$this->load->model("Maddress");
		$this->load->model("Mconfig");
		$this->load->model("Mregion");

		$config = $this->Mconfig->get();

		$this->load->model("Mmainmenu");
		$data["news_title"]=$this->Mmainmenu->get_name("news");

		if($config->LIST_ENCLOSED && !$this->session->userdata("id")){
			redirect("member/signin","refresh");
		}

		$data["page_title"] =  "매물 검색(목록)";

		$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();
		foreach($data["category"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["category"][$key]->category_sub = $category_sub;
			}
		}

		$this->load->model("Mtheme");
		$data["theme"] = $this->Mtheme->get_list();
		$data["search"] = $this->session->userdata("search");

		if($data["search"]["theme"]){
			$query_theme = @explode(",",$data["search"]["theme"]);
			foreach($data["theme"] as $val){
				if(in_array($val->id,$query_theme)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$this->load->model("Mdanzi");
		$data["danzi"] = $this->Mdanzi->get_danzi_name();

		$this->load->model("Msubway");
		$data["local"] = $this->Msubway->get_local();
		foreach($data["local"] as $val){
			if($val->local==1) $val->local_text = toeng("수도권");
			if($val->local==2) $val->local_text = toeng("부산");
			if($val->local==3) $val->local_text = toeng("대구");
			if($val->local==4) $val->local_text = toeng("광주");
			if($val->local==5) $val->local_text = toeng("대전");
		}
		$data["subway_line"] = $this->Msubway->get_registered_list_hosun();

		if($data["search"]["subway_line"]){
			$query_subway_line = @explode(",",$data["search"]["subway_line"]);
			foreach($data["subway_line"] as $val){
				if(in_array($val->hosun_id,$query_subway_line)){
					$val->checked = "checked";
				}
				else{
					$val->checked = "";
				}
			}
		}

		$data["sido"] = $this->Maddress->get_sido();

		//스킨 미리보기를 구현한다.
		$data["skin_type"] = $skin_type;
		if($skin_type=="skin"){
			$this->load->model("Mskin");
			$data["skin"] = $this->Mskin->get($skin_id);
		}

		$this->load->library('parser');
		$data["template_left"] = $this->parser->parse('templates/list_left_bottom', $data, TRUE);

		if($config->REGION_USE){
			$data["region"] = $this->Mregion->get_list();
		}

		$this->layout->view('basic/main_grid',$data);
	}

	public function param_json(){
		$param = $this->session->userdata("search");
		if(isset($param["search_type"])){

			if($param["search_type"]=="parent_address"){
				$this->load->model("Mparentaddress");
				$q = $this->Mparentaddress->get($param["search_value"]);
				$param["lat"] = $q->lat;
				$param["lng"] = $q->lng;
				$param["zoom"] = $q->zoom;
				$param["area"] = $q->area;
			}

			if($param["search_type"]=="address"){
				$this->load->model("Maddress");
				$q = $this->Maddress->get($param["search_value"]);
				$param["lat"] = $q->lat;
				$param["lng"] = $q->lng;
				$param["zoom"] = $q->zoom;
			}
			
			if(isset($param["zoom"])){
				$param["zoom"] = 20-$param["zoom"];
			}
		}
		echo json_encode($param);
	}



	/**
	 * 목록 페이지 json 페이지, 페이징 가능하도록 제작
	 * 
	 * 20141007 - FRONT에서는 member검색 부분을 하지 않도록 한다.
	 * 20150224 - 급매(speed) 정렬을 추가한다.
	 * 20150422 - (DJ)공장과 토지 정보를 위하여 ground_use, ground_aim, factory_use, factory_hoist, factory_power 항목을 추가함.
	 * 20150422 - 검색 조건에 member는 삭제한다.
	 */
	public function listing_json($page=0){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->load->library('pagination');
		$this->load->model("Mproduct");

		$total_rows = $this->Mproduct->get_total_count($this->session->userdata("search"));
		$per_page = $this->input->post("per_page");

		//현재 검색 조건으로 전체 매물 갯수를 알려준다.
		$query["total"] = $total_rows;

		if($total_rows <= 9){
			$query["paging"] = 0;
		} else {
			$query["paging"] =  $page+$per_page;
		}

		$result["product"] = $this->Mproduct->get_list($this->session->userdata("search"), $per_page, $page);
		
		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["subway"] = $this->Mproduct->get_product_subway($val["id"]);
		}

		$this->layout->setLayout("list");
		
		// $config->LISTING : 1,2,3(기본)
		$query["result"]   = $this->layout->view("templates/listing_" . $config->LISTING ,$result,true);
	
		echo json_encode($query);
	}

	/**
	 * 업소 소개 페이지
	 */
	public function intro($id=""){
		if($id=="" || $id=="1"){
			$data["page_title"] =  "소개";
			$this->layout->view('basic/main_intro',$data);
		}
		else{
			$this->load->model("Mintro");
			$data["query"] = $this->Mintro->get($id);
			$data["page_title"] =  $data["query"]->title;
			$this->layout->view('basic/main_intro_sub',$data);
		}
	}

	/**
	 * daum의 겨우에는 zoom이 작아지면 작아질수록 지도가 확대가 된다. 즉, 일정 숫자이상이 되면 지하철역을 보이지 말아야 한다. 
	 * zoom은 getLevel로 넘어오기 때문에 거리계산을 위해서는 20-줌을 해 줘야 하는 것은 맞다.
	 * 하지만, 구글에서는 +1 이 확대, daum에서는 -1이 확대이다.
	 * 
	 */
	public function get_json(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$zoom = $this->input->post("zoom");
		if($zoom < 8) {
			$this->load->model("Mproduct");
			$data["subway"] = $this->Mproduct->get_subway_list($this->input->post("swlat"), $this->input->post("nelat"), $this->input->post("swlng"), $this->input->post("nelng"));
		}			

		/** 지하철까지는 처리하고 거리계산을 위해서 구글에 맞춰서 줌을 변경해 준다. **/
		$zoom = 20-$zoom;

		//마커
		define('OFFSET', 268435456);
		define('RADIUS_1', 85445659.4471); /* $offset / pi() */
		$this->load->helper("map");
		$this->load->model("Mproduct");
		$query = $this->Mproduct->get_all_server_latlng($this->input->post("swlat"), $this->input->post("nelat"), $this->input->post("swlng"), $this->input->post("nelng"), $this->session->userdata("search"));
		$data["marker"] = cluster_count($query,50,$zoom,$config->MAP_CLUSTER,$config->MAP_ICON_ONLY);
		
		echo json_encode($data);
	}


	/**
	 * search 를 session에 저장하였을 때 ie8 등에서 문제가 발생하였기 때문에 form submit 하는 형태로 수정하였다.
	 */
	public function get_all_json($lat_s, $lat_e, $lng_s, $lng_e, $page){
		$this->load->model("Mproduct");
		$query = $this->Mproduct->get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		echo json_encode($query);
	}

	public function get_all_server_latlng($zoom, $lat_s, $lat_e, $lng_s, $lng_e){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$zoom = 20-$zoom;

		define('OFFSET', 268435456);
		define('RADIUS', 85445659.4471); /* $offset / pi() */
		$this->load->helper("map");
		$this->load->model("Mproduct");
		$query = $this->Mproduct->get_all_server_latlng($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		echo json_encode(cluster_count($query,50,$zoom,$config->MAP_CLUSTER,$config->MAP_ICON_ONLY));
	}

	public function get_all_server_list($page, $lat_s, $lat_e, $lng_s, $lng_e){
		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$per_page = 20;
		$total_rows = $this->Mproduct->get_all_total($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		$query["total"] = $total_rows;

		//값을 0으로 세팅하면 더보기 버튼이 사라진다.
		if($total_rows>$page){
			$query["paging"] =  $page+$per_page;
		} else {
			$query["paging"] = 0;
		}

		$result["product"] = $this->Mproduct->get_all_server_list($per_page, $page, $lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		/**
		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["subway"] = $this->Mproduct->get_product_subway($val["id"]);
		}
		**/

		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["add_price"] = $this->Madminproduct->get_add_price($val["id"]);
		}

		$this->layout->setLayout("list");
		$query["result"]   = $this->layout->view("templates/map_right",$result,true);

		echo json_encode($query);
	}

	public function get_all_server_cluster_list($title, $zoom, $x, $y, $lat_s, $lat_e, $lng_s, $lng_e){
		$zoom = 20-$zoom;
		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$this->load->helper("map");
		define('OFFSET', 268435456);
		define('RADIUS_1', 85445659.4471); /* $offset / pi() */
 
		$query = $this->Mproduct->get_all_list($lat_s, $lat_e, $lng_s, $lng_e, $this->session->userdata("search"));
		$result["product"] = cluster_list($title, $x, $y,$query,50,$zoom);

		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["add_price"] = $this->Madminproduct->get_add_price($val["id"]);
		}

		$this->layout->setLayout("list");
		$result   = $this->layout->view("templates/map_right",$result,true);

		echo $result;
	}

	public function get_property($id){
		$this->load->model("Mproduct");
		$this->load->model("Madminproduct");
		$result["product"] = $this->Mproduct->get_property($id);
		$result["product"][0]["add_price"] = $this->Madminproduct->get_add_price($id);
		$this->layout->setLayout("list");
		echo $this->layout->view("admin/template/map_info",$result,true);
	}

	public function get_subway_json($lat_s, $lat_e, $lng_s, $lng_e){
		$this->load->model("Mproduct");
		$query = $this->Mproduct->get_subway_list($lat_s, $lat_e, $lng_s, $lng_e);
		echo json_encode($query);
	}

	/**
	 * 
	 * 1. parent_category : 변환 시에는 자체 카테고리가 아닌 메인 카테고리 정보를 가져온다.
	 * 2. theme 정보는 가져오지 않는다.
	 * 3. is_blog 정보는 가져오지 않는다.
	 *
	 * 뷰 페이지에서 보여줄 내용을 모두 가져오는 것은 ?
	 * 
	 */
	public function sync($id,$key){
		if($key=="9009"){
			header('Content-Type: text/html; charset=UTF-8'); 
			
			$this->load->model("Mproduct");
			$this->load->model("Mgallery");

			$query = $this->Mproduct->get_xml($id);

			echo "<products>";
			foreach($query as $val){
				echo "<product>";
					echo "<origin_id>".$val->id."</origin_id>";
					echo "<date>".$val->date."</date>";
					echo "<moddate>".$val->moddate."</moddate>";
					echo "<title>".$val->title."</title>";
					echo "<secret>".$val->secret."</secret>";
					echo "<address_id>".$val->address_id."</address_id>";
					echo "<type>".$val->type."</type>";
					echo "<category>".$val->parent_category."</category>";
					echo "<sell_price>".$val->sell_price."</sell_price>";
					echo "<lease_price>".$val->lease_price."</lease_price>";
					echo "<full_rent_price>".$val->full_rent_price."</full_rent_price>";
					echo "<monthly_rent_price>".$val->monthly_rent_price."</monthly_rent_price>";
					echo "<monthly_rent_deposit>".$val->monthly_rent_deposit."</monthly_rent_deposit>";
					echo "<premium_price>".$val->premium_price."</premium_price>";
					echo "<mgr_price>".$val->mgr_price."</mgr_price>";
					echo "<park_price>".$val->park_price."</park_price>";
					echo "<total_floor>".$val->total_floor."</total_floor>";
					echo "<current_floor>".$val->current_floor."</current_floor>";
					echo "<bedcnt>".$val->bedcnt."</bedcnt>";
					echo "<bathcnt>".$val->bathcnt."</bathcnt>";
					echo "<option>".$val->option."</option>";
					echo "<content>".urlencode($val->content)."</content>";
					echo "<address>".$val->address."</address>";
					echo "<lat>".$val->lat."</lat>";
					echo "<lng>".$val->lng."</lng>";
					echo "<real_area>".$val->real_area."</real_area>";
					echo "<law_area>".$val->law_area."</law_area>";
					echo "<land_area>".$val->land_area."</land_area>";
					echo "<road_area>".$val->law_area."</road_area>";
					echo "<road_conditions>".$val->road_conditions."</road_conditions>";
					echo "<enter_year>".$val->enter_year."</enter_year>";
					echo "<build_year>".$val->build_year."</build_year>";
					echo "<ground_use>".$val->ground_use."</ground_use>";
					echo "<ground_aim>".$val->ground_aim."</ground_aim>";
					echo "<factory_power>".$val->factory_power."</factory_power>";
					echo "<factory_hoist>".$val->factory_hoist."</factory_hoist>";
					echo "<factory_use>".$val->factory_use."</factory_use>";
					echo "<status>".$val->status."</status>";
					echo "<recommand>".$val->recommand."</recommand>";
					echo "<tag>".$val->tag."</tag>";
					echo "<is_activated>".$val->is_activated."</is_activated>";
					echo "<is_valid>".$val->is_valid."</is_valid>";
					echo "<is_finished>".$val->is_finished."</is_finished>";
					echo "<is_speed>".$val->is_speed."</is_speed>";
					echo "<gallery>";
					foreach($this->Mgallery->get_list($val->id) as $gal){
						echo $gal->filename.",";
					}
					echo "</gallery>";
				echo "</product>";
			}
			echo "</products>\n";
		}
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
