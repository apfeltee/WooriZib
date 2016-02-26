<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Productview
{
	var $obj;
	var $config;

    public function __construct(){
    	$this->obj =& get_instance();
	}

	public function _get($id){

	   	$this->obj->load->model("Mconfig");
		$this->config = $this->obj->Mconfig->get();

		$this->obj->load->model("Mconfig");
		$this->obj->load->model("Mproduct");
		$this->obj->load->model("Madminproduct");
		$this->obj->load->model("Mgallery");
		$this->obj->load->model("Mmember");
		$this->obj->load->model("Mcategory");			// 카테고리 정보를 가져와 인증이 필요한지 여부를 판단한다.
		$this->obj->load->model("Mlog");
		$this->obj->load->model("Mhope");
		$this->obj->load->model("Maddress");
		$this->obj->load->model("Mdanzi");
		$this->obj->load->model("Mtheme");
		$this->obj->load->model("Mbuilding");
		$this->obj->load->model("Mcontactproduct");
		$this->obj->load->model("Mnews");

		$product = $this->obj->Mproduct->get($id);

		if(!$product){
			redirect("my404","refresh");
			exit;
		}

		$product_check = $this->obj->Madminproduct->product_check_last($id);
		$product->last_check_date = (isset($product_check->date)) ? $product_check->date : "";

		$address = $this->obj->Maddress->get($product->address_id);
		$product->parent_id = $address->parent_id;

		$this->obj->Mproduct->view($id); // 조회수 증가

		$data["config"] = $this->obj->Mconfig->get();	
		$data["page_title"] =  $product->title;

		$data["panel_history"]	=  $this->obj->Mlog->get_list($this->obj->session->userdata("session_id"),10,0);
		foreach($data["panel_history"] as $key=>$val){
			$data["panel_history"][$key]->add_price = $this->obj->Madminproduct->get_add_price($val->id);
		}
		$data["panel_hope"]		=  $this->obj->Mhope->get_list($this->obj->session->userdata("session_id"),10,0);
		foreach($data["panel_hope"] as $key=>$val){
			$data["panel_hope"][$key]->add_price = $this->obj->Madminproduct->get_add_price($val->id);
		}

		$data["hope"] = $this->obj->Mhope->check($id); //모바일에서 사용

		$data["query"] = $product;
		$data["product_subway"] = $this->obj->Mproduct->get_product_subway($id);
		$data["gallery"] = $this->obj->Mgallery->get_list($id);
		$data["member"] = $this->obj->Mmember->get($data["query"]->member_id);
		$data["search"] = $this->obj->session->userdata("search");
		$data["category_one"] = $this->obj->Mcategory->get($data["query"]->category);
		$data["contact"] = $this->obj->Mcontactproduct->get_list($id);
		$data["right_news"] = $this->obj->Mnews->get_right_list();
		foreach($data["right_news"] as $val){
			$val->attachment = $this->obj->Mnews->get_attachment_list($val->id);
		}

		if($data["contact"]){
			foreach($data["contact"] as $contact){
				$phone = @explode("-dungzi-",$contact->phone);
				$phone = @explode("--",$phone[0]);
				$contact->phone = $phone[2];
			}
		}

		$data["theme"] = "";
		if($data["query"]->theme){
			$data["theme"] = $this->obj->Mtheme->get_list_in(explode(",",$data["query"]->theme));
		}

		if($data["query"]->danzi_id){
			$data["danzi"] = $this->obj->Mdanzi->get($data["query"]->danzi_id);
			$data["recent"] = $this->obj->Mproduct->get_products_danzi_recent($id, $data["danzi"]->address_id, $data["danzi"]->name);		
		}
		else{
			$data["recent"] = $this->obj->Mproduct->get_products_recent($id, $data["query"]->category, $data["query"]->lat, $data["query"]->lng);		
		}
		
		foreach($data["recent"] as $key=>$val){
			$data["recent"][$key]["subway"] = $this->obj->Mproduct->get_product_subway($val["id"],2);
			$data["recent"][$key]["add_price"] = $this->obj->Madminproduct->get_add_price($val["id"]);
		}

		$data["gongsil_contact"] = "";
		if($data["query"]->gongsil_contact){
			$data["gongsil_contact"] = multi_view($data["query"]->gongsil_contact,"gongsil");
		}

		$data["query"]->add_price = $this->obj->Madminproduct->get_add_price($id);

		$near_meta = $this->obj->Mproduct->get_near_meta();

		//검색쿼리에 따른 인근정보 가져오기
		foreach($near_meta as $meta){
			$near_data = $this->local_query($data['query']->lat,$data['query']->lng,$data['query']->address_name." ".$meta->query);
			$near_filter = array();
			if($near_data){
				foreach($near_data as $near){
					$pos = strpos($near->category,$meta->query);
					if($pos!==false){
						$near->distance = $this->distance($data['query']->lat, $data['query']->lng, $near->latitude, $near->longitude);
						$near->distance = $near->distance * 0.001;
						$near_filter[] = $near;
					}
				}			
			}	
			$data["near_data"][$meta->title] = $near_filter;
		}
		if(isset($data["near_data"])) $data["near_data"] = array_filter($data["near_data"]);

		//반경300M내 업종분포
		$store_near = $this->local_query($data['query']->lat,$data['query']->lng,$data['query']->store_category,300);
		$store_data = array();
		if($store_near){
			foreach($store_near as $store){
				$store->distance = $this->distance($data['query']->lat, $data['query']->lng, $store->latitude, $store->longitude);
				$store->distance = $store->distance * 0.001;
				$store_data[] = $store;
			}
		}
		$data["store_data"] = $store_data;

		//건물정보
		if($data["config"]->BUILDING_DISPLAY){
			$data["building"] = $this->obj->Mbuilding->get($data["query"]->address_name." ".$data["query"]->address);

			//상가임대차보호법 적용여부
			$data["building_protection"] = building_protection_law($data["query"]->monthly_rent_deposit,$data["query"]->monthly_rent_price);
		}

		$data["id"] 	= $id;
		$data["cate"]	= $this->obj->Mcategory->get($product->category);	// category는 layout에서 넘어가니 여기서는 단일로 cate로 변경한다.
		$data["form"] 	= $this->obj->Mcategory->get_form($data["cate"] ->main);
		$data["product_view"] = $this->obj->load->view("admin/template/product_view",$data,true);

		/*** LOG START ***/
		if($this->obj->input->ip_address()!=$data["config"]->ip){

			$this->obj->load->model("Mlog");
			$this->obj->load->library('user_agent');
			$this->obj->load->helper("check");

			if($this->obj->session->userdata("session_cnt")==""){ $this->obj->session->set_userdata("session_cnt",1); }
			else { $this->obj->session->set_userdata("session_cnt", $this->obj->session->userdata("session_cnt") + 1); }
			
			$param = Array(
				"session_id"=> $this->obj->session->userdata("session_id"),
				"session_cnt"=> $this->obj->session->userdata("session_cnt"),
				"user_agent"=> $this->obj->session->userdata("user_agent"),
				"user_referrer"=> $this->obj->agent->referrer(),
				"ip"		=> $this->obj->input->ip_address(),
				"type"		=> "product",
				"mobile"	=> MobileCheck(), 
				"data_id"	=> $id,
				"date"		=> date('Y-m-d H:i:s')
			);


			$this->obj->Mlog->add($param);

		}
		/*** LOG START ***/

		return $data;
	}

	/**
	 * 거리 m 로 반환
	 */
	private function distance($lat1, $lon1, $lat2, $lon2) {
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		return floor($miles * 1609.344);
	}


	/**
	 * 인근 정보 (query)
	 */
	 private function local_query($lat,$lng,$query,$radius=5000){

		$this->obj->load->model("Mconfig");
		$config = $this->obj->Mconfig->get();

		if($config->DAUM=="") return "";

	 	if($lat!=""&&$lng!=""&&$query!=""){
			$key = $config->DAUM;
			$url = "https://apis.daum.net/local/v1/search/keyword.json?location=".$lat.",".$lng."&query=".urlencode($query)."&radius=".$radius."&apikey=".$key."";
			$result = json_decode(get_url($url));
			return ($result) ? $result->channel->item : "";
		}
	 }
}
?>