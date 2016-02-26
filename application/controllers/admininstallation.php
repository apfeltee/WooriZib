<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admininstallation extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function clean(){
		$this->session->unset_userdata('search_installation');
		redirect("admininstallation/index","refresh");
	}

	/**
	 * 분양정보는 가입자가 올리는 기능은 없다.
	 */
	function index(){
		$data["search_installation"] = $this->session->userdata("search_installation");
		$this->load->model("Mmember");
		$data["members"] = $this->Mmember->get_list("admin","","","",true);
		$this->layout->admin('installation_index', $data);
	}

	/**
	 * 관리자에서 리스트 JSON
	 *
	 */
	function listing_json($page=0){

		$this->load->library('pagination');
		$this->load->model("Madmininstallation");
		$this->load->model("Minstallation");
		
		$config['base_url'] = '';
		$config['total_rows'] = $this->Minstallation->get_total_count($this->session->userdata("search_installation"),"admin");
		$query["total"] = $config['total_rows'];

		//현재 검색 조건으로 전체 매물 갯수를 알려준다.
		$param["total"] = $config['total_rows'];

		$per_page = $this->session->userdata("search_installation")["per_page"];
		$config['per_page'] = ($per_page) ? $per_page : 10;
		//$config['uri_segment'] = count($this->uri->segment_array());
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

		$result["installation"] = $this->Minstallation->get_list($this->session->userdata("search_installation"), $config['per_page'], $page,"admin");

		$query["paging"] = $this->pagination->create_links();

		$this->layout->setLayout("list");

		$query["result"]   = $this->layout->view('admin/admin_list_installation',$result,true);

		echo json_encode($query);
	}


	/**
	 * 엑셀 다운로드 (아고 졸려 내일 하자!)
	 */
	function excel(){
		$this->load->model("Madmininstallation");
		
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
			"status"=>$this->input->post("status"),
			"keyword"=>$this->input->post("keyword"),
			"page"=>$page
		);

		//$query = $this->Madmininstallation->get_list($param, -1, 0,"admin");
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
		$this->load->model("Mmember");
		$this->load->model("Mcategory");

		$this->cleanTemp(); //임시 저장 파일을 모두 삭제한다.

		$data["members"] = $this->Mmember->get_list("admin");
		$data["category"] = $this->Mcategory->get_list();

		$this->layout->admin('installation_add', $data);
	}

	/**
	 * 로그인한 관리자의 임시 갤러리 파일을 모두 삭제한다.
	 */
	private function cleanTemp(){
		$this->load->model("Mgalleryinstallationtemp");
		$this->load->model("Mpyeongtemp");
		$gallery = $this->Mgalleryinstallationtemp->get_list($this->session->userdata("admin_id"));
		$pyeong = $this->Mpyeongtemp->get_list($this->session->userdata("admin_id"));

		foreach($gallery as $val){
			if( file_exists(HOME.'/uploads/gallery_installation/temp/'. element("filename",$val)) ){
				@unlink(HOME.'/uploads/gallery_installation/temp/'. element("filename",$val));			//본 이미지 삭제
				$temp = explode(".",element("filename",$val));
				@unlink(HOME.'/uploads/gallery_installation/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}
			$this->Mgalleryinstallationtemp->delete($val["id"]);
		}

		foreach($pyeong as $val){
			if( file_exists(HOME.'/uploads/pyeong/temp/'. element("filename",$val)) ){
				@unlink(HOME.'/uploads/pyeong/temp/'. element("filename",$val));			//본 이미지 삭제
				$temp = explode(".",element("filename",$val));
				@unlink(HOME.'/uploads/pyeong/temp/'. $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
			}
			$this->Mpyeongtemp->delete($val["id"]);
		}
	}


	function add_action(){

		$this->load->model("Mconfig");
		$this->load->model("Madmininstallation");
	 	$this->load->model("Mattachmentinstallation");
		$this->load->model("Minstallationschedule");		

		$config = $this->Mconfig->get();

		if(!$this->input->post("lat") || !$this->input->post("lng")){
			redirect("admininstallation/index","refresh");
		}
		
		$recommand = "0";
		if($this->input->post("recommand")=="on") {
			$recommand = "1";
		}

		$param = Array(
			"title"			=> $this->input->post("title"),
			"secret"		=> $this->input->post("secret"),
			"address_id"	=> $this->input->post("address_id"),
			"address"		=> $this->input->post("address"),			
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"status"		=> $this->input->post("status"),
			"category"		=> $this->input->post("category"),
			"scale"			=> $this->input->post("scale"),
			"heating"		=> $this->input->post("heating"),
			"park"			=> $this->input->post("park"),
			"builder"		=> $this->input->post("builder"),
			"builder_url"	=> $this->input->post("builder_url"),
			"bank"			=> $this->input->post("bank"),
			"is_presale"	=> $this->input->post("is_presale"),
			"notice_year"	=> $this->input->post("notice_year"),
			"enter_year"	=> $this->input->post("enter_year"),
			"content"		=> $this->input->post("content"),
			"recommand"		=> $recommand,
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"is_activated"	=> $this->input->post("is_activated"),
			"member_id"		=> $this->input->post("member_id"),
			"date"			=> date('Y-m-d H:i:s')
		);

		$idx = $this->Madmininstallation->insert($param);

		//스케쥴 저장
		foreach($this->input->post("schedule_name") as $key=>$val){
			if($val){
				$schedule_description = $this->input->post("schedule_description");
				$schedule_date = $this->input->post("schedule_date");

				$schedule_param = Array(
					"installation_id"	=> $idx,
					"name"			=> $val,
					"description"	=> $schedule_description[$key],
					"date"			=> $schedule_date[$key]
				);

				/** 날짜가 입력되어 있지 않으면 데이터가 입력되지 않는다. ***/
				if($schedule_date[$key]!=""){
					$this->Minstallationschedule->insert($schedule_param);
				}
			}		
		}

		//네이버 신디케이션 전송
		//$this->load->helper("syndi");
		//send_ping($idx,"installation");

		//지하철 역 정보 추가
		$subway = $this->Madmininstallation->get_subway($this->input->post("lat"),$this->input->post("lng"));
		foreach($subway as $val){
			$this->Madmininstallation->insert_subway($idx,$val->id);
		}

		$this->move_temp_gallery($idx); //임시로 저장되어 있는
		$this->move_temp_pyeong($idx);
		$this->cleanTemp(); //모두 옮긴 후 템프를 삭제한다.

		/** 파일 첨부 시작 **/		
		if(count($_FILES)>0){

	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment_installation/'.$idx;
	 		$this->upload->initialize(array(
	            "upload_path"   => $folder,
	            "allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
	            "encrypt_name"	=> TRUE
	        ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);	//파일업로드 시 해당 폴더가 없으면 생성한다.
				chmod($folder,0777);
			}

	 		if($this->upload->do_upload("application_file")) {

				$data = array('upload_data' => $this->upload->data());
				$param = Array(
					"installation_id" 	=> $idx,
					"originname"=> $data["upload_data"]["orig_name"],
					"filename"	=> $data["upload_data"]["file_name"],
					"file_ext"	=> $data["upload_data"]["file_ext"],
					"file_size"	=> $data["upload_data"]["file_size"],
					"regdate" 	=> date('Y-m-d H:i:s')
				);

				$this->Mattachmentinstallation->insert($param);
	        }
	    }	    
	    /** 파일 첨부 종료 **/

		redirect("admininstallation/index","refresh");
	}

	private function move_temp_gallery($id){

		//옮겨갈 폴더가 없으면 새로 만든다.
		$target = HOME.'/uploads/gallery_installation/'.$id;
		if(!file_exists($target)){
			mkdir($target,0777);
			chmod($target,0777);
		}

		// 입력화면에서 등록된 갤러리를 옮긴다.
		$this->load->model("Mgalleryinstallationtemp");
		$this->load->model("Mgalleryinstallation");
		$gallery = $this->Mgalleryinstallationtemp->get_list($this->session->userdata("admin_id"));
		foreach($gallery as $val){

			copy(HOME.'/uploads/gallery_installation/temp/' . element("filename",$val), $target . "/" . element("filename",$val));
			$temp = explode(".",element("filename",$val));
			copy(HOME.'/uploads/gallery_installation/temp/'. $temp[0]."_thumb.".$temp[1], $target . "/" . $temp[0]."_thumb.".$temp[1]);

			$param = Array(
				"installation_id" => $id,
				"content" => $val["content"],
				"filename" => element("filename",$val),
				"sorting" => element("sorting",$val),
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mgalleryinstallation->insert($param);
		}
	}

	private function move_temp_pyeong($id){

		//옮겨갈 폴더가 없으면 새로 만든다.
		$target = HOME.'/uploads/pyeong/'.$id;
		if(!file_exists($target)){
			mkdir($target,0777);
			chmod($target,0777);
		}

		// 입력화면에서 등록된 갤러리를 옮긴다.
		$this->load->model("Mpyeongtemp");
		$this->load->model("Mpyeong");
		$pyeong = $this->Mpyeongtemp->get_list($this->session->userdata("admin_id"));
		foreach($pyeong as $val){

			copy(HOME.'/uploads/pyeong/temp/' . element("filename",$val), $target . "/" . element("filename",$val));
			$temp = explode(".",element("filename",$val));
			copy(HOME.'/uploads/pyeong/temp/'. $temp[0]."_thumb.".$temp[1], $target . "/" . $temp[0]."_thumb.".$temp[1]);

			$param = Array(
				"installation_id" => $id,
				"name" => $val["name"],
				"presale_date" => element("presale_date",$val),
				"price_min" => element("price_min",$val),
				"price_max" => element("price_max",$val),
				"tax" => element("tax",$val),
				"real_area" => element("real_area",$val),
				"law_area" => element("law_area",$val),
				"road_area" => $val["road_area"],
				"gate" => element("gate",$val),
				"cnt" => element("cnt",$val),
				"bedcnt"	=> element("bedcnt",$val),
				"bathcnt"	=> element("bathcnt",$val),
				"description" => element("description",$val),
				"filename" => element("filename",$val),
				"sorting" => element("sorting",$val),
				"regdate" => date('Y-m-d H:i:s')
			);

			$this->Mpyeong->insert($param);
		}
	}

	function edit($id){
		$this->load->model("Madmininstallation");
		$this->load->model("Minstallationschedule");
		$this->load->model("Mmember");
		$this->load->model("Maddress");

		$data["members"] = $this->Mmember->get_list("admin");
		$data["query"] = $this->Madmininstallation->get($id);

		$data["address"] = $this->Maddress->get($data["query"]->address_id);
		$data["installation_schedule"] = $this->Minstallationschedule->get_list($id);

		$this->layout->admin('installation_edit', $data);
	}

	/**
	 * 매물 상세 보기 화면
	 * 
	 * 20141007 - 관리자 권한 또는 자신이 작성한 매물이 아닐 경우에는 비정상 접속으로 홈으로 보내버린다.
	 * $type : 상세보기 또는 갤러리(detail , gallery)
	 * 20140422 - (DJ)파일 첨부 기능 추가
	 */
	function view($id,$type="detail"){
		$this->load->model("Madmininstallation");
		$this->load->model("Minstallation");
		$this->load->model("Mgalleryinstallation");
		$this->load->model("Mconfig");
		$this->load->model("Maddress");
		$this->load->model("Mblogapi");
		$this->load->model("Mattachmentinstallation");
		$this->load->model("Mpyeong");
		$this->load->model("Minstallationschedule");
		
		$data["blog"]	= $blog = $this->Mblogapi->get_valid_list();
		$data["query"]	= $this->Madmininstallation->get($id);
		$data["pyeong"]	= $this->Mpyeong->get_list($id);
		$data["schedule"]	= $this->Minstallationschedule->get_list($id);

		if(count($data["query"])<1){
			redirect("admininstallation/index","refresh");
			exit;
		}

		$near_meta = $this->Minstallation->get_near_meta();

		//검색쿼리에 따른 인근정보 가져오기
		foreach($near_meta as $meta){			
			$near_data = $this->local_query($data['query']->lat, $data['query']->lng, $meta->query);
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

		$data["address"] = $this->Maddress->get($data["query"]->address_id);
		$data["installation_subway"] = $this->Minstallation->get_installation_subway($id);
		$data["gallery"] = $this->Mgalleryinstallation->get_list($id);
		$data["config"] = $this->Mconfig->get();
		$data["type"] = $type;
		

		$data["attachment"] = $this->Mattachmentinstallation->get_list($id);

		$this->layout->admin('installation_view', $data);	

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
		$this->load->model("Madmininstallation");
	 	$this->load->model("Mattachmentinstallation");
		$this->load->model("Minstallationschedule");

		$config = $this->Mconfig->get();	

		$recommand = "0";
		if($this->input->post("recommand")=="on") {
			$recommand = "1";
		}

		$idx = $this->input->post("id");

		$param = Array(
			"title"			=> $this->input->post("title"),
			"secret"		=> $this->input->post("secret"),
			"address_id"	=> $this->input->post("address_id"),
			"address"		=> $this->input->post("address"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"status"		=> $this->input->post("status"),
			"category"		=> $this->input->post("category"),
			"scale"			=> $this->input->post("scale"),
			"heating"		=> $this->input->post("heating"),
			"park"			=> $this->input->post("park"),
			"builder"		=> $this->input->post("builder"),
			"builder_url"	=> $this->input->post("builder_url"),
			"bank"			=> $this->input->post("bank"),
			"is_presale"	=> $this->input->post("is_presale"),
			"notice_year"	=> $this->input->post("notice_year"),
			"enter_year"	=> $this->input->post("enter_year"),
			"content"		=> $this->input->post("content"),
			"tag"			=> $this->input->post("tag"),
			"video_url"		=> $this->input->post("video_url"),
			"recommand"		=> $recommand,
			"member_id"		=> $this->input->post("member_id"),
			"moddate"		=> date('Y-m-d H:i:s')
		);	

		$this->Madmininstallation->update($param,$idx);

		$this->Minstallationschedule->delete($idx);

		//스케쥴 저장
		foreach($this->input->post("schedule_name") as $key=>$val){
			if($val){
				$schedule_description = $this->input->post("schedule_description");
				$schedule_date = $this->input->post("schedule_date");

				$schedule_param = Array(
					"installation_id"	=> $idx,
					"name"				=> $val,
					"description"		=> $schedule_description[$key],
					"date"				=> $schedule_date[$key],
				);

				/** 날짜가 입력되어 있지 않으면 데이터가 입력되지 않는다. ***/
				if($schedule_date[$key]!=""){
					$this->Minstallationschedule->insert($schedule_param);
				}				
			}		
		}

		//네이버 신디케이션 전송
		//$this->load->helper("syndi");
		//send_ping($idx,"installation");

		//지하철 역 정보 추가
		$subway = $this->Madmininstallation->get_subway($this->input->post("lat"),$this->input->post("lng"));

		$this->Madmininstallation->delete_subway($idx);
		foreach($subway as $val){
			$this->Madmininstallation->insert_subway($idx,$val->id);
		}

		if(count($_FILES)>0){

	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment_installation/'.$idx;
	 		$this->upload->initialize(array(
	            "upload_path"   => $folder,
	            "allowed_types" => 'doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
	            "encrypt_name"	=> TRUE
	        ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);	//파일업로드 시 해당 폴더가 없으면 생성한다.
				chmod($folder,0777);
			}

	 		if($this->upload->do_upload("application_file")) {

				$data = array('upload_data' => $this->upload->data());
				$param = Array(
					"installation_id" 	=> $idx,
					"originname"=> $data["upload_data"]["orig_name"],
					"filename"	=> $data["upload_data"]["file_name"],
					"file_ext"	=> $data["upload_data"]["file_ext"],
					"file_size"	=> $data["upload_data"]["file_size"],
					"regdate" 	=> date('Y-m-d H:i:s')
				);

				$this->Mattachmentinstallation->insert($param);
	        }
	    }
		redirect("admininstallation/index","refresh");
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
	 * 갤러리 사진 업로드
	 *
	 * 1. 890 픽셀로 본 이미지가 업로드 되고 120픽셀로 썸네일 이미지가 등록된다.
	 * 2. 포토타이틀을 달 수 있도록 했다.
	 * 3. watermark 추가
	 */
	public function upload_image_action($id){
		$folder = HOME.'/uploads/gallery_installation/'.$id;
		if(!file_exists($folder))	{
			mkdir($folder,0777);
			chmod($folder,0777);
		}
		
		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = $folder;
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mgalleryinstallation");
			
			$sorting = $this->Mgalleryinstallation->get_sorting($id);

			$param = Array(
				"installation_id" => $id,
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);
			$this->Mgalleryinstallation->insert($param);
			$this->make_gallery_thumb($data["upload_data"],$id,890,"");
			$this->make_gallery_thumb($data["upload_data"],$id,450,"_thumb");
		}

		echo "1";
	}

	/**
	 * 갤러리 등록 이미지 썸네일 만들기
	 */
	private function make_gallery_thumb($data, $id, $width=300, $folder="thumb"){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		//썸네일 만들기
		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/gallery_installation/'.$id."/".$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/gallery_installation/'.$id."/".$data["file_name"];
		$thumb_config['create_thumb'] = TRUE;
		$thumb_config['thumb_marker'] = $folder;
		$thumb_config['maintain_ratio'] = TRUE;
		$thumb_config['width'] = $width;
		$thumb_config['height'] = intval($data["image_height"])*$thumb_config['width']/intval($data["image_width"]);
		$thumb_config['quality'] = $config->QUALITY;

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($thumb_config);
		
		//관리자 정보를 가져와서 watermark의 취치를 지정한다.
		$this->load->model("Mmember");
		$member = $this->Mmember->get($this->session->userdata("admin_id"));

		if ( ! $CI->image_lib->resize())
		{
			echo $CI->image_lib->display_errors();
			return "0";
		} else {
			return "1";
		}
	}

	/**
	 * 갤러리 이미지 삭제
	 */
	function gallery_delete($gid){
		//파일 삭제
		$this->load->model("Mgalleryinstallation");
		$gallery = $this->Mgalleryinstallation->get($gid);

		if( file_exists(HOME.'/uploads/gallery_installation/'.$gallery->installation_id ."/". $gallery->filename) ){
			@unlink(HOME.'/uploads/gallery_installation/'.$gallery->installation_id ."/". $gallery->filename);			//본 이미지 삭제
			$temp = explode(".",$gallery->filename);
			@unlink(HOME.'/uploads/gallery_installation/'.$gallery->installation_id ."/". $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
		}

		if($gallery->sorting==1){
			$this->Mgalleryinstallation->sorting_refresh($gallery->installation_id);
		}

		// DB 삭제
		$this->Mgalleryinstallation->delete($gallery->installation_id, $gid);
		echo "1";
	}

	/**
	 * 갤러리 이미지 전체 삭제
	 */
	function gallery_all_delete(){
		$this->load->model("Mgalleryinstallation");
		$gallery = $this->Mgalleryinstallation->get_list($this->input->post("id"),"obj");
		foreach($gallery as $val){
			$this->gallery_delete($val->id);
		}
	}

	/**
	 * 갤러리 이미지설명 수정
	 */
	function gallery_content_update($id){
		$this->load->model("Mgalleryinstallation");

		$param = Array(
			"content" => $this->input->post('content')
		);		

		$gallery = $this->Mgalleryinstallation->update($id,$param);
	}

	/**
	 * 로테이트 이미지
	 */
	function change_rotate($id){
		$this->load->model("Mgalleryinstallation");
		
		$gallery = $this->Mgalleryinstallation->get($id);

		$data['rotate'] = 270;

		$data['image'] = HOME.'/uploads/gallery_installation/'.$gallery->installation_id ."/". $gallery->filename;
		$this->make_rotate_image($data); //본문이미지

		$temp = explode(".",$gallery->filename);
		$data['image'] = HOME.'/uploads/gallery_installation/'.$gallery->installation_id ."/". $temp[0]."_thumb.".$temp[1];
		$this->make_rotate_image($data); //썸네일이미지
	}

	/**
	 * 로테이트 이미지 전환
	 */
	private function make_rotate_image($data){

		$config['image_library']	= 'gd2';
		$config['source_image']		= $data['image'];
		$config['create_thumb']		= FALSE;
		$config['maintain_ratio']	= TRUE;
		$config['quality']			= '100%';
		$config['rotation_angle']	= $data['rotate'];

		$CI =& get_instance();
		$CI->load->library('image_lib');
		$CI->image_lib->initialize($config);

		if (!$CI->image_lib->rotate()){
			echo "0";
		} else {
			echo "1";
		}
	}

	function gallery_json($id){
		$this->load->model("Mgalleryinstallation");
		echo json_encode($this->Mgalleryinstallation->get_list($id));
	}

	function gallery_sorting($gallery_id,$sorting){
		$this->load->model("Mgalleryinstallation");
		$this->Mgalleryinstallation->change_sorting($gallery_id,$sorting);
		echo "1";
	}

	/**
	 * 물건의 상태 변경
	 * $type : is_activated, is_finished, is_speed, recommand
	 */
	function change($type, $id, $status, $redirect=""){
		$this->load->model("Madmininstallation");
		$param = Array(
			$type=>$status,
			"moddate"		=> date('Y-m-d H:i:s')
		);
		$this->Madmininstallation->change($param,$id);

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
	function delete_installation($id,$redirect=true){
		$this->load->Model("Madmininstallation");
		$this->load->Model("Minstallationschedule");
		$installation = $this->Madmininstallation->get($id);
		if($installation!=null){
			//매물등록자이거나 관리자라면 삭제가 가능하다.
			if($this->session->userdata("admin_id")==$installation->member_id || $this->session->userdata("auth_id")==1){
				
				//갤러리 삭제		
				if( file_exists(HOME.'/uploads/gallery_installation/'.$id) ){
					$this->rrmdir(HOME.'/uploads/gallery_installation/'.$id);
				}
				$this->load->model("Mgalleryinstallation");
				$this->Mgalleryinstallation->delete_installation($id);

				//평형 사진 삭제		
				if( file_exists(HOME.'/uploads/pyeong/'.$id) ){
					$this->rrmdir(HOME.'/uploads/pyeong/'.$id);
				}
				$this->load->model("Mpyeong");
				$this->Mpyeong->delete_pyeong($id);

				//네이버 신디케이션 전송
				//$this->load->helper("syndi");
				//send_ping($id,"installation","delete");

				//DB 삭제
				$this->Madmininstallation->delete_installation($id);
				$this->Madmininstallation->delete_subway($id);
				$this->Minstallationschedule->delete($id);

				if($redirect) redirect("admininstallation/index","refresh");
			} else {
				echo "<script>alert('Authentification Error!');history.go(-1);</script>";
			}
		} else {
			if($redirect) redirect("admininstallation/index","refresh");
		}
	}

	/**
	 * 매물 다중 삭제
	 */
	function delete_all_installation(){
		$check_installation = $this->input->post('check_installation');
		if($check_installation){
			foreach($check_installation as $value){
				$this->delete_installation($value,false);
			}
		}
		redirect("admininstallation/index","refresh");
	}

	/**
	 * 매물 복사 기능
	 * - 기존 정보를 이용해 새로운 매물을 만들고 이미지들은 복사해온다.
	 * - 
	 */
	public function copy($installation_id,$redirect=""){

		$this->load->model("Madmininstallation");
		$this->load->model("Mgalleryinstallation");

		$source = $this->Madmininstallation->get_raw($installation_id);

		//복사해도 복사되면 안되는 것들.
		unset($source->id); 
		unset($source->viewcnt);
		unset($source->is_blog);
		unset($source->is_cafe);

		$source->date = date('Y-m-d H:i:s');

		$insert_id = $this->Madmininstallation->insert($source);

		//갤러리 정보 옮김
		$gallerys = $this->Mgalleryinstallation->get_list($installation_id);
		foreach($gallerys as &$gallery){
			unset($gallery->id); //기존 id로 넣으면 duplated error 가 발생한다.
			$gallery->installation_id=$insert_id;
			$this->Mgalleryinstallation->insert($gallery);
		}

		//갤러리 디렉토리 이동
		$this->load->helper("directory");
		$src=HOME.'/uploads/gallery_installation/'.$installation_id;
		$dst=HOME.'/uploads/gallery_installation/'.$insert_id;
		directory_copy($src,$dst);

		//지하철역 정보 옮김
		$this->load->model("Msubwayinstallation");
		$subways = $this->Msubwayinstallation->get_installation_list($installation_id);
		foreach($subways as $subway){
			$this->Msubwayinstallation->insert($insert_id,$subway->subway_id);
		}

		if($redirect=="list"){
			redirect($this->input->server('HTTP_REFERER'),"refresh");
		}
		else{
			redirect("admininstallation/view/".$insert_id,"refresh");
		}
	}

	/**
	 * 매물 등록일 갱신
	 */
	public function refresh($installation_id,$redirect=""){
		$this->load->model("Madmininstallation");
		$param = Array("date"=>date('Y-m-d H:i:s'));
		$this->Madmininstallation->update($param,$installation_id);

		if($redirect=="list"){
			redirect($this->input->server('HTTP_REFERER'),"refresh");
		}
		else if($redirect=="none"){	
		}
		else{
			redirect("admininstallation/view/".$installation_id,"refresh");
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
				@unlink($dir."/".$object);
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

		$check_installation = $this->input->post("check_installation");

		if($check_installation){
			switch($exe_type){
				case "refresh" :
					foreach($check_installation as $value){
						$this->refresh($value,"none");
					}
					break;
				case "is_valid" :
				case "is_activated" :
				case "recommand" :
				case "is_finished" :
				case "is_speed" :
				case "is_defer" :
					foreach($check_installation as $value){
						$this->change($exe_type, $value, $exe_value, "none");
					}
					break;
				default :
					break;
			}
		}
		redirect("admininstallation/index","refresh");
	}
}

/* End of file Admininstallation.php */
/* Location: ./application/controllers/Admininstallation.php */