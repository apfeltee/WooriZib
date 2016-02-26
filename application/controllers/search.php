<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {


	public function keyword(){
		$return = Array();
		
		$this->load->model("Msearch");

		if(is_numeric($this->input->post("search"))){
		
			foreach($this->Msearch->product($this->input->post("search")) as $val){
				$title = " [" . $val->id . "]" . $val->title ;
				$title = str_replace($this->input->post("search"),"<strong>".$this->input->post("search")."</strong>",$title);
				array_push($return, Array("search_type"=>"product", "search_value"=>$val->id,"search"=>$val->id,"title"=>$title));		
			}

		} else {

			foreach($this->Msearch->spot($this->input->post("search")) as $val){
				$title = $val->name ;
				$title = str_replace($this->input->post("search"),"<strong>".$this->input->post("search")."</strong>",$title);
				array_push($return, Array("search_type"=>"spot", "search_value"=>$val->id, "lat"=>$val->lat,"lng"=>$val->lng,"search"=>$val->name,"title"=>$title,"zoom"=>"15","area"=>""));		
			}

			foreach($this->Msearch->parent_address($this->input->post("search")) as $val){
				$title = $val->sido . " " . $val->gugun . "- <small>" . $val->cnt  . "건</small>";
				$title = str_replace($this->input->post("search"),"<strong>".$this->input->post("search")."</strong>",$title);
				array_push($return, Array("search_type"=>"parent_address","search_value"=>$val->id, "lat"=>$val->lat,"lat"=>$val->lat,"lng"=>$val->lng,"search"=>$val->sido . " " . $val->gugun,"title"=>$title,"zoom"=>$val->zoom,"area"=>$val->area));
			}
			
			foreach($this->Msearch->subway($this->input->post("search")) as $val){
				$title = $val->name . "역 (" . $val->hosun . "호선) - <small>" . $val->cnt  . "건</small>";
				$title = str_replace($this->input->post("search"),"<strong>".$this->input->post("search")."</strong>",$title);
				array_push($return, Array("search_type"=>"subway","search_value"=>$val->id, "lat"=>$val->lat,"lat"=>$val->lat,"lng"=>$val->lng,"search"=>$val->name . "역","title"=>$title,"zoom"=>"15","area"=>""));
			}

			foreach($this->Msearch->address($this->input->post("search")) as $val){
				$title = $val->sido . " " . $val->gugun . " " . $val->dong  . " - <small>". $val->cnt . "건</small>";
				$title = str_replace($this->input->post("search"),"<strong>".$this->input->post("search")."</strong>",$title);
				array_push($return, Array("search_type"=>"address","search_value"=>$val->id, "lat"=>$val->lat,"lat"=>$val->lat,"lng"=>$val->lng,"search"=>$val->sido . " " . $val->gugun . " " . $val->dong,"title"=>$title,"zoom"=>"14","area"=>""));
			}

		}
		echo json_encode($return);
	}

	public function direct($search_type, $search_value, $type="all", $sorting="basic"){
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if($type=="all") $type = "";
		$param = Array(
			"search_type" => $search_type,
			"search_value" => $search_value,
			"dong_val" => $search_value,
			"search" => "",
			"theme" => "",
			"danzi" => "",
			"category"	=> "",
			"sell_start"	=> "0",
			"sell_end"		=> $config->SELL_MAX,
			"full_start"	=> "0",
			"full_end"		=> $config->FULL_MAX,
			"month_deposit_start"	=> "0",
			"month_deposit_end"		=> $config->MONTH_DEPOSIT_MAX,
			"month_start"	=> "0",
			"month_end"		=> $config->MONTH_MAX,
			"type"		=> $type,
			"sorting" => $sorting
		);	

		if($search_type=="parent_address"){

			$this->load->model("Mparentaddress");
			$q = $this->Mparentaddress->get($search_value);
			$param["lat"] = $q->lat;
			$param["lng"] = $q->lng;
			$param["zoom"] = $q->zoom;
			$param["area"] = $q->area;
		} else if($search_type=="address"){
			
			$this->load->model("Maddress");
			$q = $this->Maddress->get($search_value);
			$param["lat"] = $q->lat;
			$param["lng"] = $q->lng;
			$param["sido_val"] = $q->sido;
			$param["gugun_val"] = $q->parent_id;

		} else if($search_type=="subway"){
			$this->load->model("Msubway");
			$q = $this->Msubway->get($search_value);
			$param["lat"] = $q->lat;
			$param["lng"] = $q->lng;

		} else if($search_type=="danzi"){
			$this->load->model("Mdanzi");
			$q = $this->Mdanzi->get($search_value);
			$param["lat"] = $q->lat;
			$param["lng"] = $q->lng;
			$param["danzi"] = $search_value;
			$param["search_type"] = "";
			$param["search_value"] = "";
			$param["sido_val"] = "";
			$param["gugun_val"] = "";
			$param["dong_val"] = "";
			$param["subway_local_val"] = "";
			$param["hosun_val"] = "";
			$param["station_val"] = "";
		} else if($search_type=="theme"){
			$param["theme"] = $search_value;
		}

		$this->session->set_userdata("search",$param);
		
		/** 검색 결과로 이동할 때에는 grid로 이동시킨다. **/
		if(MobileCheck()){
			$Us = '/mobile/grid';; //모바일이동경로
			$Ds = (strpos($Us,'?') !== false) ? '&' : '?';
			$Qs = ($_SERVER['QUERY_STRING'] ? $Ds.$_SERVER['QUERY_STRING'] : '');
			header('Location: '.$Us.$Qs);
			exit;
		} else {
			redirect("main/grid","refresh");		
		}
	}

	public function get_search_json(){
		return json_encode($this->session->userdata("search"));
	}

	/**
	 * 금액대별로 매물 목록으로 보내는 로직을 만든다.
	 */
	public function price($type, $start, $end){

		$param = Array(
			"type"		=> $type,
			"amount_sell_start" => $start,
			"amount_sell_end" => $end,
			"amount_full_start" => "0",
			"amount_full_end" => PRICE_2_MAX,
			"amount_rent_deposit_start" => "0",
			"amount_rent_deposit_end" => PRICE_3_MAX,
			"amount_rent_monthly_start" => "0",
			"amount_rent_monthly_end" => PRICE_4_MAX,
			"search_type" => "",
			"search_value" => "",
			"search" => "",
			"theme" => "",
			"category"	=> ""
		);				

		$this->session->set_userdata("search",$param);
	
		redirect("main/grid","refresh");
	}

	public function set_geolocation(){
		$param = $this->session->userdata("search");
		$param["local_lat"] = $this->input->post("local_lat");
		$param["local_lng"] = $this->input->post("local_lng");
		$this->session->set_userdata("search",$param);
	}

	/**
	 * 검색을 실행하는 action
	 *
	 * search_type	: 검색 유형(parent_address, address, subway, google
	 * search_value	: 검색 값
	 * lat			: latitude
	 * lng			: longitude
	 * type			: 거래종류(sell, full_rent, monthly_rent
	 * theme		: 테마
	 * category		: 매물종류(예:원룸, 아파트 ...)
	 *
	 */
	public function set_search($direct="",$mobile=false){

		if(is_Numeric($this->input->post("search"))){
			if($mobile)	redirect("/mobile/view/".$this->input->post("search"),"refresh");
			else redirect("/product/view/".$this->input->post("search"),"refresh");
			exit;
		}

		$search = $this->session->userdata("search");

		$local_lat = (isset($search['local_lat'])) ? $search['local_lat'] : $this->input->post("local_lat");
		$local_lng = (isset($search['local_lng'])) ? $search['local_lng'] : $this->input->post("local_lng");

		$this->session->unset_userdata("search");

		/** category값이 없을 경우 implode하면 warning이 표시되어서 "" 처리를 해 줬다. ***/
		$category = "";
		if($this->input->post("category")!=""){
			$category = $this->input->post("category");
			if(is_array($category)){
				$category = implode(",", $this->input->post("category"));
			}			
		}

		$category_sub = "";
		if($this->input->post("category_sub")!=""){
			$category_sub = $this->input->post("category_sub");
			if(is_array($category_sub)){
				$category_sub = implode(",", $this->input->post("category_sub"));
			}			
		}

		$theme = "";
		if($this->input->post("theme")!="" && $this->input->post("theme")!="0"){
			$theme = $this->input->post("theme");
			if(is_array($theme)){
				$theme = implode(",", $this->input->post("theme"));
			}
		}

		$subway_line = "";
		if($this->input->post("subway_line")!=""){
			$subway_line = $this->input->post("subway_line");
			if(is_array($subway_line)){
				$subway_line = implode(",", $this->input->post("subway_line"));
			}
		}

		/***********************************************************************************************
		 * 이 함수는 메인페이지에서 검색을 할 때에도 지도나 목록에서 검색 조건을 변경해도 함께 사용한다.
		 * $sido 에 값이 없을 경우에는 $gugun, $dong에도 값이 없어야 한다.
		 * 
		 **********************************************************************************************/

		$sido	= $this->input->post("sido");
		$gugun  = $this->input->post("gugun");
		$dong	= $this->input->post("dong");

		if($this->input->post("region")!=""){
			$this->load->model("Mregion");
			$region = $this->Mregion->get($this->input->post("region"));
			$sido	= $region->sido;
			$gugun  = $region->parent_id;
			$dong	= $region->address_id;
		}

		$param = Array(
			"search_type" => $this->input->post("search_type"),
			"search_value" => $this->input->post("search_value"),
			"sido_val" => $sido,
			"gugun_val" => $gugun,
			"dong_val" => $dong,
			"subway_local_val" => $this->input->post("subway_local"),
			"hosun_val" => $this->input->post("hosun"),
			"station_val" => $this->input->post("station"),
			"lat" => $this->input->post("lat"),
			"lng" => $this->input->post("lng"),
			"local_lat" => $local_lat,
			"local_lng" => $local_lng,
			"local_search" => $this->input->post("local_search"),
			"type" => $this->input->post("type"),
			"theme" => $theme,
			"subway_line" => $subway_line,
			"danzi_name" => $this->input->post("danzi_name"),
			"danzi" => $this->input->post("danzi"),
			"category" => $category,
			"category_sub" => $category_sub,
			"sell_start"	=> $this->input->post("sell_start"),
			"sell_end"		=> $this->input->post("sell_end"),
			"full_start"	=> $this->input->post("full_start"),
			"full_end"		=> $this->input->post("full_end"),
			"month_deposit_start"	=> $this->input->post("month_deposit_start"),
			"month_deposit_end"		=> $this->input->post("month_deposit_end"),
			"month_start"	=> $this->input->post("month_start"),
			"month_end"		=> $this->input->post("month_end"),
			"site_area" => $this->input->post("site_area"),
			"law_area" => $this->input->post("law_area"),
			"only" => $this->input->post("only"),
			"valid" => $this->input->post("valid"),
			"per_page" => $this->input->post("per_page"),
			"factory_use" => $this->input->post("factory_use"),
			"factory_hoist" => $this->input->post("factory_hoist"),
			"factory_power" => $this->input->post("factory_power"),
			"search_member_id" => $this->input->post("search_member_id"),
			"search_admin_member_id" => $this->input->post("search_admin_member_id"),			
			"keyword" => $this->input->post("keyword"),
			"keyword_front" => $this->input->post("keyword_front"),
			"region" => $this->input->post("region"),
			"sorting" => $this->input->post("sorting"),
			"now_page" => $this->input->post("now_page")
		);

		if($this->input->post("danzi_name")){
			$danzi_name = explode("|",$this->input->post("danzi_name"));
			$param["danzi_name"] = $danzi_name[1];
		}

		//log_message('error',print_r($param,TRUE));

		if($param["keyword"]){
			$param["keyword_type"] = $this->input->post("keyword_type");	
		}

		if($param["danzi"] || $this->input->post("reset")){
			$param["search_type"] = "";
			$param["search_value"] = "";
			$param["keyword_front"] = "";
			$param["sido_val"] = "";
			$param["gugun_val"] = "";
			$param["dong_val"] = "";
			$param["subway_local_val"] = "";
			$param["hosun_val"] = "";
			$param["station_val"] = "";
		}

		if($this->input->post("reset")){
			$param["lat"] = "";
			$param["lng"] = "";
			$param["type"] = "";
			$param["theme"] = "";
			$param["subway_line"] = "";
			$param["category"] = "";
		}

		if($this->input->post("reset") && $mobile){
			redirect($_SERVER['HTTP_REFERER'],"refresh");
			exit;
		}

		//줌값 조정
		switch($param["search_type"]){
			case "parent_address" :
				$this->load->model("Mparentaddress");
				$param["search_value"] = ($param["search_value"]) ?  $param["search_value"] : $gugun;
				$q = $this->Mparentaddress->get($param["search_value"]);
				$param["sido_val"] = $q->sido;
				$param["gugun_val"] = $q->id;
				$param["zoom"] = $q->zoom;
				$param["lat"] = $q->lat;
				$param["lng"] = $q->lng;
				$param["keyword_front"] = "";
				break;
			case "address" :				
				if($param["search_value"]){
					$this->load->model("Maddress");
					$q = $this->Maddress->get($param["search_value"]);
					$param["sido_val"] = $q->sido;
					$param["gugun_val"] = $q->parent_id;
					$param["dong_val"] = $q->id;
					$param["zoom"] = $q->zoom;
					$param["lat"] = $q->lat;
					$param["lng"] = $q->lng;
				}
				$param["keyword_front"] = "";
				break;
			case "subway" :
				$param["zoom"] = "15";
				$param["keyword_front"] = "";
			case "google" :
				if($this->input->post("zoom")=="")	$param["zoom"] = "14";
				else $param["zoom"] = $this->input->post("zoom");
				break;
			default :
				$param["zoom"] = "14";
				break;
		}

		$param["zoom"] = 20 - $param["zoom"];

		$this->session->set_userdata("search",$param);

		if($direct){
			if($mobile) redirect("/mobile/".$direct,"refresh");
			else redirect($direct,"refresh");
		}

	}

	public function set_theme($theme=''){
		$this->session->unset_userdata("search");
		$param = Array("theme"=>$theme);
		$this->session->set_userdata("search",$param);
		redirect("/main/map","refresh");
	}	

	public function member_list($type=''){
		$this->load->model("Mmember");
		$data = $this->Mmember->get_list($type,$this->input->post("search"));
		echo json_encode($data);
	}

	public function contacts_member_list(){
		$this->load->model("Mcontact");
		$data = $this->Mcontact->contact_member_list($this->input->post("search"));
		echo json_encode($data);
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */
