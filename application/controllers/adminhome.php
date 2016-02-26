<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminhome extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}
	
	function index($page=0){
		$this->load->model("Madminhome");
		$this->load->model("Mstatistics");
		$this->load->model("Mlog");
		
		$data["active_product_count"] = $this->Madminhome->get_active_product_count();
		$data["inactive_product_count"] = $this->Madminhome->get_inactive_product_count();
		$data["recommand_product_count"] = $this->Madminhome->get_recommand_product_count();
		$data["speed_product_count"] = $this->Madminhome->get_speed_product_count();
		$data["blog_product_count"] = $this->Madminhome->get_blog_product_count();
		$data["enquire_count"] = $this->Madminhome->get_enquire_count();
		$data["ask_count"] = $this->Madminhome->get_ask_count();
		$data["today_site_count"] = $this->Madminhome->get_today_site_count();
		$data["today_blog_count"] = $this->Madminhome->get_today_blog_count();

		// 오늘 날짜 사이트 시간대별 방문자 수
		$data["today_site_visit"] = $this->Mstatistics->get_today_site_visit();
		$data["today_blog_visit"] = $this->Mstatistics->get_today_blog_visit();

		// 최근 30일간 일별 접속 현황
		$data["month_site_visit"] = $this->Mstatistics->get_month_site_visit();
		$data["month_blog_visit"] = $this->Mstatistics->get_month_blog_visit();

		// 연락처 조회 통계
		$data["site_log"] = $this->Mlog->get_site_product_log();	
		
		// 연락처 조회 담당자
		$data["call_log_members"] = $this->Mlog->call_log_members("today");

		// 연락처 조회 통계
		$data["call_log"] = $this->Mlog->get_call_log("today");		

		$this->layout->admin('home_index', $data);
	}

	/**
	 * 기본 설정 보기
	 */
	function config(){
		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$data["config"] = $this->Mconfig->get();
		$data["site_admin"] = $this->Mmember->get($data["config"]->site_admin);
		$this->layout->admin('config', $data);
	}

	function config_etc(){
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$this->layout->admin('config_etc', $data);
	}

	/**
	 * 기본 설정 수정
	 */
	function config_edit(){
		$this->load->model("Mconfig");
		$this->load->model("Mmember");
		$data["config"] = $this->Mconfig->get();
		$data["members"] = $this->Mmember->get_list("admin");
		$this->layout->admin('config_edit', $data);
	}

	/**
	 * 고급 설정 수정
	 */
	function config_high_edit(){
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$this->layout->admin('config_high_edit', $data);
	}

	function config_etc_edit($code="normal"){
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$data["code"] = $code;
		$this->layout->admin('config_etc_edit', $data);
	}

	/**
	 * 기본 설정 수정 저장
	 */
	function config_action(){
		
		$param = Array(
			"name"			=> $this->input->post("name"),
			"site_name"			=> $this->input->post("site_name"),
			"ceo"			=> $this->input->post("ceo"),
			"ip"			=> $this->input->post("ip"),
			"maxzoom"			=> $this->input->post("maxzoom"),
			"description"	=> $this->input->post("description"),
			"keyword"		=> $this->input->post("keyword"),
			"email"			=> $this->input->post("email"),
			"biznum"		=> $this->input->post("biznum"),
			"renum"			=> $this->input->post("renum"),
			"tel"			=> $this->input->post("tel"),
			"fax"			=> $this->input->post("fax"),
			"mobile"			=> $this->input->post("mobile"),
			"kakaochat"			=> $this->input->post("kakaochat"),
			"address"		=> $this->input->post("address"),
			"lat"			=> $this->input->post("lat"),
			"lng"			=> $this->input->post("lng"),
			"new_address"	=> $this->input->post("new_address"),
			"year"			=> $this->input->post("year"),
			"content"		=> $this->input->post("content"),
			"naverwebmasterkey"	=> $this->input->post("naverwebmasterkey"),
			"naverwebmastertoken"	=> $this->input->post("naverwebmastertoken"),
			"navercskey"	=> $this->input->post("navercskey"),
			"navercssecret"	=> $this->input->post("navercssecret"),
			"naverclientkey"	=> $this->input->post("naverclientkey"),
			"naverclientsecret"	=> $this->input->post("naverclientsecret"),
			"daumclientkey"	=> $this->input->post("daumclientkey"),
			"daumclientsecret"	=> $this->input->post("daumclientsecret"),
			"glogkey"	=> $this->input->post("glogkey"),
			"site_admin"	=> $this->input->post("site_admin")
		);

		$this->load->model("Mconfig");
		$this->Mconfig->update($param,$this->input->post("id"));

		redirect("adminhome/config_edit","refresh");
	}

	/**
	 * 고급 설정 수정 저장
	 */
	function config_high_action(){

		$param = Array();

		foreach($this->input->post() as $key=>$val){
			$param[$key] = $val;
		}

		$this->load->model("Mconfig");
		$this->Mconfig->update_high($param,$this->input->post("id"));

		redirect("adminhome/config_high_edit","refresh");
	}

	/**
	 * 확장 설정 수정 저장
	 * 2015-03-11 이미지업로드시 height의 최대값이 60픽셀을 초과할 수 없도록 한다.
	 */
	function config_etc_action(){
	/** 평면도 업로드 ***/
		$config['upload_path'] = HOME.'/uploads/logo';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		
		$logo = "";
		if (!$this->upload->do_upload("logo")){
			$error = array('error' => $this->upload->display_errors());
			//echo $this->upload->display_errors();
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$logo = $data["upload_data"]["file_name"];
		
			if($data["upload_data"]["image_height"]>60){
				@unlink($config['upload_path']."/".$logo);
				redirect("adminhome/config_etc_edit/error","refresh");
				exit;
			}
		
		}

		$footer_logo = "";
		if (!$this->upload->do_upload("footer_logo")){
			$error = array('error' => $this->upload->display_errors());
			//echo $this->upload->display_errors();
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$footer_logo = $data["upload_data"]["file_name"];

			if($data["upload_data"]["image_height"]>60){
				@unlink($config['upload_path']."/".$footer_logo);
				redirect("adminhome/config_etc_edit/error","refresh");
				exit;
			}
		}


		$no = "";
		if (!$this->upload->do_upload("no")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			
			$data = array('upload_data' => $this->upload->data());
			$no = $data["upload_data"]["file_name"];

			if($data["upload_data"]["image_width"]<=268){	/** 리스트에 사용 **/
			
				$this->make_thumb($data,268, "thumb/",true);
			
			} else {
			
				$this->make_thumb($data,268, "thumb/");
			
			}

			$this->make_thumb($data,890, "");			//본문에 사용
		}

		$watermark = "";
		if (!$this->upload->do_upload("watermark")){
			$error = array('error' => $this->upload->display_errors());
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$watermark = $data["upload_data"]["file_name"];

			/*** 워터마크이미지는 좀 더 클 수 있다.
			if($data["upload_data"]["image_height"]>200){
				@unlink($config['upload_path']."/".$watermark);
				redirect("adminhome/config_etc_edit/error_watermark","refresh");
				exit;
			}
			if($data["upload_data"]["image_width"]>200){
				@unlink($config['upload_path']."/".$watermark);
				redirect("adminhome/config_etc_edit/error_watermark","refresh");
				exit;
			}***/
		}

		$param = Array(
			"watermark_position_vertical"	=> $this->input->post("watermark_position_vertical"),
			"watermark_position_horizontal" => $this->input->post("watermark_position_horizontal"),
			"sms_id"	=> $this->input->post("sms_id"),
			"sms_key"	=> $this->input->post("sms_key")
		);

		if($logo!=""){
			$param["logo"] = $logo;
		}

		if($footer_logo!=""){
			$param["footer_logo"] = $footer_logo;
		}

		if($no!=""){
			$param["no"] = $no;
		}

		$this->load->model("Mconfig");

		if($watermark!=""){
			$watermark_config = $this->Mconfig->get();
			//기존 파일 제거
			@unlink($config['upload_path']."/".$watermark_config->watermark);
			$param["watermark"] = $watermark;
		}

		$this->Mconfig->update($param,$this->input->post("id"));

		redirect("adminhome/config_etc_edit","refresh");	
	}

	/**
	 * 업체 소개 설명에서 이미지 업로드 실행
	 */
	public function upload_action(){
		$config['upload_path'] = HOME.'/uploads/logo/';
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
			$this->make_body($data);
			echo "/uploads/logo/". $data["upload_data"]["file_name"];
		}
	}

	/**
	 * 890픽셀이 넘으면 890픽셀로 줄여준다.
	 */
	private function make_body($data)
	{
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 890){

			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/logo/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 890;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$config['overwrite'] = TRUE;

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				$CI->image_lib->display_errors();
				return false;
			} 
		}
	}

	/**
	 * 대표 이미지 없음 이미지의 썸네일을 만든다.
	 */
	private function make_thumb($data, $width=300, $folder="thumb/", $skip=false)
	{
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > $width || $skip){

			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME.'/uploads/logo/'. $data["upload_data"]["file_name"];
			$thumb_config['new_image']	  = HOME.'/uploads/logo/'.$folder.$data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = TRUE;
			$thumb_config['thumb_marker'] = "";
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = $width;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = "100%";

			$CI =& get_instance();
			$CI->load->library('image_lib');
			$CI->image_lib->initialize($thumb_config);

			if ( ! $CI->image_lib->resize())
			{
				echo $CI->image_lib->display_errors();
				return "0";
			} else {
				return "1";
			}
		}
	}

	//워터마크 삭제
	public function delete_watermark_image(){
		$this->load->model("Mconfig");
		$watermark = $this->input->post("watermark");
		if($watermark){
			$this->Mconfig->delete_watermark_image();
			@unlink(HOME."/uploads/logo/".$watermark);
		}
	}

	/**
	 * 나의 소셜링크
	 */
	function social_link(){
		$this->load->model("Msocial");
		$data["query"] = $this->Msocial->get();

		$this->layout->admin('social_index', $data);
	}

	/**
	 * 나의 소셜링크 저장
	 */
	function social_link_edit(){
		$this->load->model("Msocial");

		$replace = array("http://", "https://");

		$param = Array(
			"naver_cafe"	=> str_replace($replace, "", $this->input->post("naver_cafe")),
			"naver_blog"	=> str_replace($replace, "", $this->input->post("naver_blog")),
			"facebook"		=> str_replace($replace, "", $this->input->post("facebook")),
			"twitter"		=> str_replace($replace, "", $this->input->post("twitter")),
			"google_plus"	=> str_replace($replace, "", $this->input->post("google_plus")),
			"youtube_channel"=> str_replace($replace, "", $this->input->post("youtube_channel"))
		);		

		$count = $this->Msocial->get_count();

		if(!$count){
			$this->Msocial->insert($param);			
		}
		else{
			$this->Msocial->update($param);
		}
		redirect("adminhome/social_link","refresh");
	}

}

/* End of file Adminhome.php */
/* Location: ./application/controllers/Adminhome.php */