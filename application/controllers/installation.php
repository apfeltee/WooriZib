<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Installation extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}
	
	/**
	 * 분양 메뉴 메인페이지
	 */
	public function index(){
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();
		$data["search_installation"] = $this->session->userdata("search_installation");

		$this->load->model("Minstallation");
		$data["installation"] = $this->Minstallation->get_recommand("all",10); //추천 분양 10건 가져오기
		$data["favorite"] =  $this->Minstallation->get_favorite(); //인기 분양 3건 가져오기

		$this->layout->view('basic/installation_index',$data);
	}


	/**
	 * 분양 목록을 보여주는 컨트롤러
	 * 
	 */
	public function listing_json($page=0){

		$this->load->model("Mconfig");
		$config = $this->Mconfig->get();

		$this->load->library('pagination');
		$this->load->model("Minstallation");

		$total_rows = $this->Minstallation->get_total_count($this->session->userdata("search_installation"));
		$per_page = 9;

		//현재 검색 조건으로 전체 매물 갯수를 알려준다.
		$query["total"] = $total_rows;

		if($total_rows <= 9){
			$query["paging"] = 0;
		} else {
			$query["paging"] =  $page+$per_page;
		}

		$result["installation"] = $this->Minstallation->get_list($this->session->userdata("search_installation"), $per_page, $page);

		$this->layout->setLayout("list");
		
		// $config->LISTING : 1,2,3(기본)
		$query["result"]   = $this->layout->view("templates/listing_installation",$result,true);

		echo json_encode($query);
	}


	/**
	 * 검색을 실행하는 action
	 *
	 * search_type	: 검색 유형(parent_address, address, subway, google
	 * search_value	: 검색 값
	 * lat			: latitude
	 * lng			: longitude
	 * type			: 거래 종류(sell, full_rent, monthly_rent
	 * theme		: 테마
	 * category		: 매물종류(예:원룸, 아파트 ...)
	 *
	 */
	public function set_search($direct="",$mobile=false){

		if(is_Numeric($this->input->post("search_installation"))){
			if($mobile)	redirect("/mobile/view_installation/".$this->input->post("search_installation"),"refresh");
			else redirect("/installation/view/".$this->input->post("search_installation"),"refresh");
			exit;
		}

		$this->session->unset_userdata("search_installation");
		
		/** category값이 없을 경우 implode하면 warning이 표시되어서 "" 처리를 해 줬다. ***/
		$category = "";
		if($this->input->post("category")!=""){
			$category = implode(",", $this->input->post("category"));
		}

		$sido	= $this->input->post("sido");
		$gugun= $this->input->post("gugun");
		$dong	= $this->input->post("dong");

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
			"category" => $category,
			"only" => $this->input->post("only"),
			"per_page" => $this->input->post("per_page"),
			"keyword" => $this->input->post("keyword"),
			"sorting" => $this->input->post("sorting")
		);


		$this->session->set_userdata("search_installation",$param);

		if($direct){
			if($mobile) redirect("/mobile/".$direct,"refresh");
			else redirect($direct,"refresh");
		}

	}

	/**
	 * 분양 보기
	 *
	 * 매물 쪽에서는 _get으로 가져왔었는데 여기서는 그냥 view에 모두 구현한다. (모달 창으로 보여주지 않을 것이기 때문이다.)
	 * 분양은 공개된 정보이기 때문에 로그인을 해야만 볼 수 있도록 할 필요가 없다.
	 */
	public function view($id=""){
		
		/*** $id 값이 없으면 아무런 실행을 하지 않고 종료한다. ***/
		if($id==""){
			redirect("my404","refresh");
			exit;
		}
		
		$this->load->model("Mgalleryinstallation");
		$this->load->model("Mlog");
		$this->load->model("Mhope");
		$this->load->model("Minstallation");
		$this->load->model("Mmember");
		$this->load->model("Mpyeong");
		$this->load->model("Minstallationschedule");

		$installation = $this->Minstallation->get($id);

		/*** 보여줄 분양 정보가 없으면 종료한다. ***/
		if(!$installation){
			redirect("my404","refresh");
			exit;
		}

		$this->Minstallation->view($id); // 조회수 증가

		$data["query"] = $installation;
		$data["gallery"] = $this->Mgalleryinstallation->get_list($id);
		$data["page_title"] =  $installation->title;
		$data["id"] = $id;
		$data["member"] = $this->Mmember->get($data["query"]->member_id);	/** 담당 직원 정보 **/
		$data["panel_history"]	=  $this->Mlog->get_list_installation($this->session->userdata("session_id"),10,0);
		$data["panel_hope"]	=  $this->Mhope->get_list_installation($this->session->userdata("session_id"),10,0);
		$data["recent"] = $this->Minstallation->get_nearby($id, $data["query"]->category, $data["query"]->lat, $data["query"]->lng);	
		$data["pyeong"]	= $this->Mpyeong->get_list($id);
		$data["schedule"]	= $this->Minstallationschedule->get_list($id);

		$this->layout->view('basic/installation_view',$data);

		
		/*** LOG START ***/
		$this->load->model("Mconfig");
		$config = $this->Mconfig->get("ip");
		if($this->input->ip_address()!=$config->ip){

			$this->load->model("Mlog");
			$this->load->library('user_agent');
			$this->load->helper("check");

			if($this->session->userdata("session_cnt")==""){ $this->session->set_userdata("session_cnt",1); }
			else { $this->session->set_userdata("session_cnt", $this->session->userdata("session_cnt") + 1); }
			
			$param = Array(
				"session_id"=> $this->session->userdata("session_id"),
				"session_cnt"=> $this->session->userdata("session_cnt"),
				"user_agent"=> $this->session->userdata("user_agent"),
				"user_referrer"=> $this->agent->referrer(),
				"ip"		=> $this->input->ip_address(),
				"type"		=> "installation",
				"mobile"	=> MobileCheck(), 
				"data_id"	=> $id,
				"date"		=> date('Y-m-d H:i:s')
			);

			$this->Mlog->add($param);

		}
		/*** LOG START ***/		
	}

	/**
	 * 전환율 계산을 위한 로그 분석 스크립트 호출
	 */
	public function view_log($id){
		require_once(HOME.'/uploads/script/logs_target.php');
	}

	public function hope_action($id){
		$this->load->model("Minstallation");
		$param =  Array(
			"session_id"	=> $this->session->userdata("session_id"),
			"member_id"		=> $this->session->userdata("id"),
			"installation_id"	=> $id,
			"date"			=> date('Y-m-d H:i:s')
		);
		$this->Minstallation->add_home($param);
		echo "1";
	}

	public function have_num($id){
		$this->load->model("Minstallation");
		$installation = $this->Minstallation->get($id);
		if($installation!=null){
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

	 public function add_call_view($installation_id,$member_id){
	 	$this->load->model("Mlog");
	 	$this->load->library('user_agent');

		$param =  Array(
			"user_agent"	=> $this->session->userdata("user_agent"),
			"member"		=> $member_id,
			"installation_id"	=> $installation_id,
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
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
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
		$folder = HOME.'/uploads/gallery/'.$id;
		if(!file_exists($folder)){
			mkdir($folder,0777);
			chmod($folder,0777);
		}
		
		$CI =& get_instance();
		$CI->load->library('image_lib');

		$config['upload_path'] = $folder;
		$config['allowed_types'] = '*';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload',$config);

		if ( ! $this->upload->do_upload("file")){
			$error = array('error' => $this->upload->display_errors());
			echo $this->upload->display_errors();
		} else {
			$data = array('upload_data' => $this->upload->data());
			$this->load->model("Mgallery");
			
			$sorting = $this->Mgallery->get_sorting($id);

			$param = Array(
				"installation_id" => $id,
				"filename" => $data["upload_data"]["file_name"],
				"sorting" => (int)$sorting + 1,
				"regdate" => date('Y-m-d H:i:s')
			);
			$this->Mgallery->insert($param);
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
		$thumb_config['source_image'] = HOME.'/uploads/gallery/'.$id."/".$data["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/gallery/'.$id."/".$data["file_name"];
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
		$member = $this->Mmember->get($this->session->userdata("id"));

		if ( ! $CI->image_lib->resize())
		{
			echo $CI->image_lib->display_errors();
			return "0";
		} else {
			return "1";
		}
	}

	/**
	 * 갤러리 이미지설명 수정
	 */
	function gallery_content_update($id){
		$this->load->model("Mgallery");

		$param = Array(
			"content" => $this->input->post('content')
		);		

		$gallery = $this->Mgallery->update($id,$param);
	}

	/**
	 * 로테이트 이미지
	 */
	function change_rotate($id){
		$this->load->model("Mgallery");
		
		$gallery = $this->Mgallery->get($id);

		$data['rotate'] = 270;

		$data['image'] = HOME.'/uploads/gallery/'.$gallery->installation_id ."/". $gallery->filename;
		$this->make_rotate_image($data); //본문이미지

		$temp = explode(".",$gallery->filename);
		$data['image'] = HOME.'/uploads/gallery/'.$gallery->installation_id ."/". $temp[0]."_thumb.".$temp[1];
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
		$this->load->model("Mgallery");
		echo json_encode($this->Mgallery->get_list($id));
	}

	function gallery_sorting($gallery_id,$sorting){
		$this->load->model("Mgallery");
		$this->Mgallery->change_sorting($gallery_id,$sorting);
		echo "1";
	}

	/**
	 * 갤러리 이미지 삭제
	 */
	function gallery_delete($gid){
		//파일 삭제
		$this->load->model("Mgallery");
		$gallery = $this->Mgallery->get($gid);
		if( file_exists(HOME.'/uploads/gallery/'.$gallery->installation_id ."/". $gallery->filename) ){
			unlink(HOME.'/uploads/gallery/'.$gallery->installation_id ."/". $gallery->filename);			//본 이미지 삭제
			$temp = explode(".",$gallery->filename);
			unlink(HOME.'/uploads/gallery/'.$gallery->installation_id ."/". $temp[0]."_thumb.".$temp[1]);	//썸네일 이미지 삭제
		}

		if($gallery->sorting==1){
			$this->Mgallery->sorting_refresh($gallery->installation_id);
		}

		// DB 삭제
		$this->Mgallery->delete($gallery->installation_id, $gid);
		echo "1";
	}

	/**
	 * 갤러리 이미지 전체 삭제
	 */
	function gallery_all_delete(){
		$this->load->model("Mgallery");
		$gallery = $this->Mgallery->get_list($this->input->post("id"),"obj");
		foreach($gallery as $val){
			$this->gallery_delete($val->id);
		}
	}
}

/* End of file installation.php */
/* Location: ./application/controllers/installation.php */