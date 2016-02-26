<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminproduct extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function clean(){
		$this->session->unset_userdata('search');
		redirect("adminproduct/index","refresh");
	}

	function index(){
		$data["search"] = $this->session->userdata("search");
		
		$this->load->model("Mcategory");
		$this->load->model("Mmember");
		$this->load->model("Mtheme");

		$data["category"] = $this->Mcategory->get_list();
		$data["members"] = $this->Mmember->get_list("admin");
		$data["theme"] = $this->Mtheme->get_list();

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

		$this->layout->admin('product_index', $data);
	}

	/**
	 * 관리자에서 리스트 JSON 
	 *
	 */
	function listing_json($page=0){

		if($this->session->userdata("search")["now_page"]){
			$page = $this->session->userdata("search")["now_page"];
		}

		$this->load->library('pagination');
		$this->load->model("Madminproduct");
		$this->load->model("Mproduct");
		$this->load->model("Mcontact");
		$this->load->model("Mgallery");
		
		$config['base_url'] = '';
		$config['total_rows'] = $this->Mproduct->get_total_count($this->session->userdata("search"),"admin");
		$query["total"] = $config['total_rows'];

		//현재 검색 조건으로 전체 매물 갯수를 알려준다.
		$param["total"] = $config['total_rows'];

		$per_page = $this->session->userdata("search")["per_page"];
		$config['per_page'] = ($per_page) ? $per_page : 10;
		$config['uri_segment'] = 3;
		$config['first_link'] = '<처음';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '마지막>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '[다음] »';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '« [이전]';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		$result["product"] = $this->Mproduct->get_list($this->session->userdata("search"), $config['per_page'], $page,"admin");

		foreach($result["product"] as $key=>$val){
			$result["product"][$key]["subway"] = $this->Mproduct->get_product_subway($val["id"]);
			$result["product"][$key]["contact"] = $this->Mcontact->get_list_by_product($val["id"]);
			$result["product"][$key]["gallery_thumb"] = $this->Mgallery->get_list($val["id"],"obj",5);
			$result["product"][$key]["gallery_thumb_admin"] = $this->Mgallery->get_list($val["id"],"obj",5,"_admin");
			$result["product"][$key]["memo_count"] = $this->Madminproduct->memo_count($val["id"]);
			$result["product"][$key]["add_price"] = $this->Madminproduct->get_add_price($val["id"]);
			$product_check = $this->Madminproduct->product_check_last($val["id"]);
			if(isset($product_check->date)){
				$result["product"][$key]["last_check_date"] = $product_check->date;
			}
		}

		$query["paging"] = $this->pagination->create_links();

		$this->layout->setLayout("list");

		$query["result"]   = $this->layout->view('admin/admin_list',$result,true);

		echo json_encode($query);
	}


	/**
	 * 엑셀 다운로드 (아고 졸려 내일 하자!)
	 */
	function excel(){
		$this->load->model("Madminproduct");
		
		$subway_id = $this->input->post("subway_id");
		if($this->input->post("subway")==""){
			$subway_id = "";
		}


		//주소 검색에서 시도, 구군, 동에서 상위 주소가 없을 경우에는 하위 주소도 없도록 만든다.
		$sido = $this->input->post("sido");
		
		if($sido==""){
			$gugun = "";
		} else {
			$gugun = $this->input->post("gugun");
		}

		if($gugun=="" || $this->input->post("gugun")==""){
			$dong  = "";
		} else {
			$dong = $this->input->post("dong");
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
			"category"=>$this->input->post("category"),
			"sido"=>$sido,
			"gugun"=>$gugun,
			"dong"=>$dong,
			"subway_id"=>$subway_id,
			"subway"=>$this->input->post("subway"),
			"member"=>$this->input->post("member"),
			"sorting"=>$this->input->post("sorting"),
			"only"=>$this->input->post("only"),
			"keyword"=>$this->input->post("keyword"),
			"page"=>$page
		);

		//$query = $this->Madminproduct->get_list($param, -1, 0,"admin");
		//$headers = "번호\t제목\t등록날짜\t수정날짜\t비밀메모\t주소\t상세주소" ;
		//header("Content-type: application/x-msdownload");
		//header("Content-Disposition: attachment; filename=".date('Y-m-d').".xls");
		//echo "$headers\n$data";
	}


	/**
	 * 매물 추가 화면
	 *
	 * @2014-11-26 : 갤러리를 매물 등록화면에서도 할 수 있도록 한다.
	 * 
	 */
	function add(){

		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");		
		$this->load->model("Maddress");

		$this->cleanTemp(); //임시 저장 파일을 모두 삭제한다.
		$this->cleanTemp("_admin"); //임시 저장 파일을 모두 삭제한다.

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
		
		$data["contact"] = null; /** 입력, 수정 폼을 같이 쓰기 때문에 null을 넣어줘서 에러가 생기지 않도록 한다. **/

		$data["mode"] = "add";
		$data["module"] = "admin";
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);

		$this->layout->admin('product_add', $data);
	}

	/**
	 * 로그인한 관리자의 임시 갤러리 파일을 모두 삭제한다.
	 */
	private function cleanTemp($admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";
		$this->load->model("Mgallerytemp");
		$gallery = $this->Mgallerytemp->get_list($this->session->userdata("admin_id"),$admin_gallery);

		foreach($gallery as $val){
			if( file_exists(HOME.'/uploads/gallery'.$admin_gallery.'/temp/'. element("filename",$val)) ){
				unlink(HOME.'/uploads/gallery'.$admin_gallery.'/temp/'. element("filename",$val));			//본 이미지 삭제
				$temp = explode(".",element("filename",$val));
				unlink(HOME.'/uploads/gallery'.$admin_gallery.'/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}
			$this->Mgallerytemp->delete($val["id"],$admin_gallery);
		}
	}


	function add_action(){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		if(!$this->input->post("lat") || !$this->input->post("lng")){
			redirect("adminproduct/index","refresh");
		}
		
		$recommand = "0";
		if($this->input->post("recommand")=="on") {
			$recommand = "1";
		}

		$is_speed = "0";
		if($this->input->post("is_speed")=="on") {
			$is_speed = "1";
		}

		$is_defer = "0";
		if($this->input->post("is_defer")=="on") {
			$is_defer = "1";
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
			"secret"			=> $this->input->post("secret"),
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
			"mgr_price"		=> $this->input->post("mgr_price"),
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
			"bathcnt"		=> $this->input->post("bathcnt"),
			"loan"			=> $this->input->post("loan"),
			"extension"		=> $this->input->post("extension"),
			"current_floor"	=> $this->input->post("current_floor"),
			"total_floor"	=> $this->input->post("total_floor"),
			"heating"		=> $this->input->post("heating"),
			"enter_year"	=> $this->input->post("enter_year"),
			"build_year"	=> ($this->input->post("build_year")) ? $this->input->post("build_year") : "",
			"option"		=> $option,
			/*"content"		=> $this->input->post("content"), 2016-02-22 매물등록 에이터미사용으로 임근호*/
			"content"		=> $this->input->post("content_01"),
			"recommand"		=> $recommand,
			"is_speed"		=> $is_speed,
			"is_defer"		=> $is_defer,
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"panorama_url"	=> $this->input->post("panorama_url"),
			"is_activated"	=> $this->input->post("is_activated"),
			"member_id"		=> $this->input->post("member_id"),
			"etc"			=> $etc,
			"owner_name"	=> $this->input->post("owner_name"),
			"owner_phone"	=> $this->input->post("owner_phone"),
			"moddate"		=> date('Y-m-d H:i:s'),
			"date"			=> date('Y-m-d H:i:s')
		);

		/** 토지 **/
		$param["ground_use"] = $this->input->post("ground_use");
		$param["ground_aim"] = $this->input->post("ground_aim");			

		/** 공장 **/
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

		//소유주 정보 수정
		$this->load->model("Mcontactproduct");
		$contacts_ids = $this->input->post("contacts_id");

		/** owner_type은 직원권한설정에서 고객관리 기능을 사용안할 경우에는 넘어오지 않기 때문에 체크가 필요하다. **/
		if($this->input->post("owner_type")!=""){
			foreach($this->input->post("owner_type") as $key=>$owner_type){
				
				$contacts_id = $contacts_ids[$key];
				
				$param = Array(
					"type"	=> $owner_type,
					"contacts_id" => $contacts_id,
					"product_id" => $idx,
					"date"	=> date('Y-m-d H:i:s')
				);
				if($contacts_id){
					$this->load->model("Mcontactproduct");
					$this->Mcontactproduct->insert($param);
				}
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

		$this->move_temp_gallery($idx,"_admin"); //임시로 저장되어 있는 
		$this->cleanTemp("_admin"); //모두 옮긴 후 템프를 삭제한다.

		/** 파일 첨부 시작 **/
		
		if(count($_FILES)>0){

	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment/'.$idx;
	 		$this->upload->initialize(array(
			            "upload_path"   => $folder,
			            "allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
			            "encrypt_name"	=> TRUE
			 ));

	 		$this->load->model("Mattachment");
			
			if(!file_exists($folder)){
				mkdir($folder,0777);	//파일업로드 시 해당 폴더가 없으면 생성한다.
				chmod($folder,0777);
			}

	 		if($this->upload->do_multi_upload("userfile")) {
	 			
	            //Print data for all uploaded files.
	            foreach($this->upload->get_multi_upload_data() as $val){

	                    //orig_name 은 encrypt를 사용할 경우 원래의 파일이름이다. 다운로드할 때 필요하다. (다운로드시에는 원래의 파일명으로 다운로드가 되어야 한다.)
	                    $param = Array(
							"product_id" 	=> $idx,
							"originname"=> $val["orig_name"],
							"filename"	=> $val["file_name"],
							"file_ext"	=> $val["file_ext"],
							"file_size"	=> $val["file_size"],
							"regdate" 	=> date('Y-m-d H:i:s')
						);

						$this->Mattachment->insert($param);
	            }
	        }
	    }
	    
	    /** 파일 첨부 종료 **/
		redirect("adminproduct/index","refresh");
	}

	private function move_temp_gallery($id,$admin_gallery=""){
		if(is_numeric($admin_gallery)) $admin_gallery = "";

		//옮겨갈 폴더가 없으면 새로 만든다.
		$target = HOME.'/uploads/gallery'.$admin_gallery.'/'.$id;
		if(!file_exists($target)){
			mkdir($target,0777);
			chmod($target,0777);
		}

		// 입력화면에서 등록된 갤러리를 옮긴다.
		$this->load->model("Mgallerytemp");
		$this->load->model("Mgallery");
		$gallery = $this->Mgallerytemp->get_list($this->session->userdata("admin_id"),$admin_gallery);
		foreach($gallery as $val){

			copy(HOME.'/uploads/gallery'.$admin_gallery.'/temp/' . element("filename",$val), $target . "/" . element("filename",$val));
			$temp = explode(".",element("filename",$val));
			copy(HOME.'/uploads/gallery'.$admin_gallery.'/temp/'. $temp[0]."_thumb.".$temp[1], $target . "/" . $temp[0]."_thumb.".$temp[1]);

			$param = Array(
				"product_id" => $id,
				"content" => $val["content"],
				"filename" => element("filename",$val),
				"sorting" => element("sorting",$val),
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mgallery->insert($param,$admin_gallery);
		}
	}

	function edit($id){
		$this->load->model("Madminproduct");
		$this->load->model("Mcategory");
		$this->load->model("Mmember");
		$this->load->model("Maddress");
		$this->load->model("Mtheme");
		$this->load->model("Mcontactproduct");
		$this->load->model("Mdanzi");

		$data["members"] = $this->Mmember->get_list("admin");
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
		$data["danzi"] = $this->Mdanzi->get_danzi($data["query"]->address_id,true);
		$danzi_info = ($data["query"]->danzi_id) ? $this->Mdanzi->get($data["query"]->danzi_id) : "";
		$data["query"]->danzi_name = ($danzi_info) ? $danzi_info->name : "";

		$data["theme"] = $this->Mtheme->get_list();
		$data["contact"] = $this->Mcontactproduct->get_list($id);
		if($data["contact"]){
			foreach($data["contact"] as $contact){
				$phone = @explode("-dungzi-",$contact->phone);
				$phone = @explode("--",$phone[0]);
				$contact->phone = $phone[2];
			}
		}

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
		$data["module"] = "admin";
		$data["product_form"] = $this->load->view("admin/template/product_form",$data,true);

		$this->layout->admin('product_edit', $data);
	}

	/**
	 * 매물 상세 보기 화면
	 * 
	 * 20141007 - 관리자 권한 또는 자신이 작성한 매물이 아닐 경우에는 비정상 접속으로 홈으로 보내버린다.
	 * 20140422 - (DJ)파일 첨부 기능 추가
	 */
	function view($id){

		$this->load->model("Mgallery");
		$this->load->model("Mattachment");

		$this->load->library("Productview");
		$data = $this->productview->_get($id);

		$data["gallery_admin"] = $this->Mgallery->get_list($id,"","","_admin");

		$data["attachment"] = $this->Mattachment->get_list($id);
		$data["product_view"] = $this->load->view("admin/template/product_view",$data,true);

		$this->layout->admin('product_view', $data);	

	}

	/**
	 * 인근 정보 (query)
	 */
	 public function local_query($lat,$lng,$query){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

	 	if($lat!=""&&$lng!=""&&$query!=""){
			$key = $config->DAUM;
			$url = "https://apis.daum.net/local/v1/search/keyword.json?location=".$lat.",".$lng."&query=".urlencode($query)."&radius=5000&apikey=".$key."";
			$result = json_decode(get_url($url));
			return ($result) ? $result->channel->item : "";
		}
	 }

	/**
	 * 매물 정보 수정하기 실행
	 *
	 * 20150224 - 급매 항목 추가
	 */
	function edit_action(){

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

		$is_defer = "0";
		if($this->input->post("is_defer")=="on") {
			$is_defer = "1";
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
			"secret"		=> $this->input->post("secret"),
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
			/*"content"		=> $this->input->post("content"), 2016-02-22 매물등록폼 에이터제거*/
			"content"		=> $this->input->post("content_01"),
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"panorama_url"	=> $this->input->post("panorama_url"),
			"recommand"		=> $recommand,
			"is_speed"		=> $is_speed,
			"is_defer"		=> $is_defer,
			"member_id"		=> $this->input->post("member_id"),
			"etc"			=> $etc,
			"owner_name"	=> $this->input->post("owner_name"),
			"owner_phone"	=> $this->input->post("owner_phone"),
			"moddate"		=> date('Y-m-d H:i:s')
		);

		if($this->input->post("refresh")){
			$param["date"] = date('Y-m-d H:i:s');			
		}

		/** 토지 **/
		$param["ground_use"] = $this->input->post("ground_use");
		$param["ground_aim"] = $this->input->post("ground_aim");			

		/** 공장 **/
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

		//소유주 정보 수정
		$this->load->model("Mcontactproduct");
		$is_insert = 0;
		$contacts_ids = $this->input->post("contacts_id");

		/** owner_type은 직원권한설정에서 고객관리 기능을 사용안할 경우에는 넘어오지 않기 때문에 체크가 필요하다. **/
		if($this->input->post("owner_type")!=""){
			foreach($this->input->post("owner_type") as $key=>$owner_type){
				
				$contacts_id = $contacts_ids[$key];
				
				//$owner_type값이 있을 경우에만 값을 넣는다.		
				if($owner_type!=""){
					$param = Array(
						"type"	=> $owner_type,
						"contacts_id" => $contacts_id,
						"product_id" => $this->input->post("id"),
						"date"	=> date('Y-m-d H:i:s')
					);
					if($contacts_id){
						if($is_insert==0){
							$this->Mcontactproduct->delete_contacts_product($this->input->post("id"));
						}
						$this->Mcontactproduct->insert($param);
						$is_insert ++;
					}
				}
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

		$idx = $this->input->post("id");
		if(count($_FILES)>0){

	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment/'.$idx;
	 		$this->upload->initialize(array(
	            "upload_path"   => $folder,
	            "allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
	            "encrypt_name"	=> TRUE
	        ));

	 		$this->load->model("Mattachment");
			
			if(!file_exists($folder)){
				mkdir($folder,0777);	//파일업로드 시 해당 폴더가 없으면 생성한다.
				chmod($folder,0777);
			}

	 		if($this->upload->do_multi_upload("userfile")) {
	 			
	            //Print data for all uploaded files.
	            foreach($this->upload->get_multi_upload_data() as $val){

	                    //orig_name 은 encrypt를 사용할 경우 원래의 파일이름이다. 다운로드할 때 필요하다. (다운로드시에는 원래의 파일명으로 다운로드가 되어야 한다.)
	                    $param = Array(
							"product_id" 	=> $idx,
							"originname"=> $val["orig_name"],
							"filename"	=> $val["file_name"],
							"file_ext"	=> $val["file_ext"],
							"file_size"	=> $val["file_size"],
							"regdate" 	=> date('Y-m-d H:i:s')
						);

						$this->Mattachment->insert($param);
	            }
	        }
	    }
		redirect("adminproduct/index","refresh");
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		
		if(!file_exists(HOME.'/uploads/contents')){
			mkdir(HOME.'/uploads/contents',0777);
			chmod(HOME.'/uploads/contents',0777);
		}

		$config['upload_path'] = HOME.'/uploads/contents/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$filename = "";
		if ( ! $this->upload->do_upload("uploadfile"))
		{
			echo $this->upload->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			if(intval($data["upload_data"]["image_width"])>890){
				$this->make_body($data);
			}
			echo "/uploads/contents/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME."/uploads/contents/". $data["upload_data"]["file_name"];
		$thumb_config['create_thumb'] = FALSE;
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = 890;
		$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
		$thumb_config['quality'] = $config->QUALITY;
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

	/**
	 * 물건의 상태 변경
	 * $type : is_activated, is_finished, is_speed, recommand
	 */
	function change($type, $id, $status, $redirect=""){
		$this->load->model("Madminproduct");
		
		$param = Array(
			$type => $status
		);

		if($type=="is_finished"){ //마감된 물건은 수정일을 등록일로 변경
			$product = $this->Madminproduct->get($id);
			$param["moddate"] = $product->date;
		}
		$this->Madminproduct->change($param,$id);

		if($redirect=="none"){
		}
		else{
			echo "1";
		}
	}

	/**
	 * 매물 삭제
	 * 1. 갤러리 삭제, 2. 썸네일 삭제, 3. DB삭제, 4. 지하철역 정보 삭제, 5.소유주 매물 삭제
	 */
	function delete_product($id,$redirect=true){
		$this->load->Model("Madminproduct");
		$product = $this->Madminproduct->get($id);
		if($product!=null){
			//매물등록자이거나 관리자라면 삭제가 가능하다.
			if($this->session->userdata("admin_id")==$product->member_id || $this->session->userdata("auth_id")==1){
				
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

				if($redirect) redirect("adminproduct/index","refresh");
			} else {
				echo "<script>alert('Authentification Error!');history.go(-1);</script>";
			}
		} else {
			if($redirect) redirect("adminproduct/index","refresh");
		}
	}

	/**
	 * 매물 다중 삭제
	 */
	function delete_all_product(){
		$check_product = $this->input->post('check_product');
		if($check_product){
			foreach($check_product as $value){
				$this->delete_product($value,false);
			}
		}
		redirect("adminproduct/index","refresh");
	}

	/**
	 * 매물 복사 기능
	 * - 기존 정보를 이용해 새로운 매물을 만들고 이미지들은 복사해온다.
	 * - 
	 */
	public function copy($product_id,$redirect=""){

		$this->load->model("Madminproduct");
		$this->load->model("Mgallery");

		$source = $this->Madminproduct->get_raw($product_id);

		//복사해도 복사되면 안되는 것들.
		unset($source->id); 
		unset($source->viewcnt);
		unset($source->result);
		unset($source->is_blog);
		unset($source->is_cafe);

		$source->moddate = date('Y-m-d H:i:s');
		$source->date = date('Y-m-d H:i:s');

		$insert_id = $this->Madminproduct->insert($source);

		//갤러리 정보 옮김
		$gallerys = $this->Mgallery->get_list($product_id);
		foreach($gallerys as &$gallery){
			unset($gallery->id); //기존 id로 넣으면 duplated error 가 발생한다.
			$gallery->product_id=$insert_id;
			$this->Mgallery->insert($gallery);
		}

		//갤러리 디렉토리 이동
		$this->load->helper("directory");
		$src=HOME.'/uploads/gallery/'.$product_id;
		$dst=HOME.'/uploads/gallery/'.$insert_id;
		directory_copy($src,$dst);

		//지하철역 정보 옮김
		$this->load->model("Msubway");
		$subways = $this->Msubway->get_product_list($product_id);
		foreach($subways as $subway){
			$this->Msubway->insert($insert_id,$subway->subway_id);
		}

		if($redirect=="list"){
			redirect($this->input->server('HTTP_REFERER'),"refresh");
		}
		else{
			redirect("adminproduct/view/".$insert_id,"refresh");
		}
	}

	/**
	 * 매물 확인 기능
	 */
	public function check_product($product_id,$redirect=""){

		$this->load->model("Madminproduct");

		$param = Array(
			"product_id" => $product_id,
			"date"		 => date('Y-m-d H:i:s')
		);

		$this->Madminproduct->product_check_insert($param);

		if($redirect=="list"){
			redirect($this->input->server('HTTP_REFERER'),"refresh");
		}
		else if($redirect=="none"){
		}
		else{
			redirect("adminproduct/view/".$product_id,"refresh");
		}		
	}

	public function get_check_list(){
		$this->load->model("Madminproduct");
		$result = $this->Madminproduct->product_check_list($this->input->post("product_id"));
		echo json_encode($result);
	}

	/**
	 * 매물 등록일 갱신
	 */
	public function refresh($product_id,$redirect=""){
		$this->load->model("Madminproduct");
		$param = Array(
			"date"=>date('Y-m-d H:i:s'),
			"moddate"=>date('Y-m-d H:i:s')
		);
		$this->Madminproduct->update($param,$product_id);

		if($redirect=="list"){
			redirect($this->input->server('HTTP_REFERER'),"refresh");
		}
		else if($redirect=="none"){	
		}
		else{
			redirect("adminproduct/view/".$product_id,"refresh");
		}		
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
	 * 매물 설정 일괄처리
	 */
	public function exe_all(){	
		$exe_type = $this->input->post("exe_type");
		$exe_value = $this->input->post("exe_value");

		$check_product = $this->input->post("check_product");

		if($check_product){
			switch($exe_type){
				case "check_product" :
					foreach($check_product as $value){
						$this->check_product($value,"none");
					}
					break;
				case "refresh" :
					foreach($check_product as $value){
						$this->refresh($value,"none");
					}
					break;
				case "is_valid" :
				case "is_activated" :
				case "recommand" :
				case "is_finished" :
				case "is_speed" :
				case "is_defer" :
					foreach($check_product as $value){
						$this->change($exe_type, $value, $exe_value, "none");
					}
					break;
				default :
					break;
			}
		}
		redirect("adminproduct/index","refresh");
	}

	/**
	 * 매물 메모 등록
	 */
	public function memo_add(){
		$this->load->model("Madminproduct");

		$param = Array(
			"product_id" => $this->input->post("product_id"),
			"memo" => $this->input->post("memo"),
			"date"	=> date('Y-m-d H:i:s')
		);

		$this->Madminproduct->memo_insert($param);
	}

	/**
	 * 매물 메모 목록
	 */
	public function get_memo_list(){
		$this->load->model("Madminproduct");
		$result = $this->Madminproduct->memo_list($this->input->post("product_id"));
		echo json_encode($result);
	}

	/**
	 * 매물 메모 삭제
	 */
	public function memo_delete($id){
		$this->load->model("Madminproduct");
		$this->Madminproduct->memo_delete($id);
	}
	
}

/* End of file Adminproduct.php */
/* Location: ./application/controllers/Adminproduct.php */

