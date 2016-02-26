<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 포트폴리오 갤러리
 */
class Adminportfolio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index($category="0", $page="0"){
		$this->load->library('pagination');
		$this->load->model("Mportfolio");
		$this->load->model("Mportfoliocategory");

		$config['base_url'] = '/portfolio/index/' . $category . "/";
		$config['total_rows'] = $this->Mportfolio->get_total_count($category,"admin");

		$config['per_page'] = 20;
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

		$data["category"] = $this->Mportfoliocategory->get_list();
		$data["category_id"] = $category;
		$data["pagination"] = $this->pagination->initialize($config);
		$data["result"] = $this->Mportfolio->get_list($category,$config['per_page'], $page,"admin");

		$this->layout->admin('portfolio_index', $data);
	}

	/**
	 * 매물 추가 화면
	 */
	function add(){
		$this->load->model("Mportfoliocategory");
		$data["category"] = $this->Mportfoliocategory->get_list();

		$this->layout->admin('portfolio_add', $data);
	}

	function add_action(){

		$config['upload_path'] = HOME.'/uploads/portfolios';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$thumb_name = ""; //대표이미지
		if ( ! $this->upload->do_upload("thumb_name"))
		{
			$error = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$thumb_name = $data["upload_data"]["file_name"];

			$this->make_thumb($data,$this->input->post("member_id"), 300, "thumb/"); //리스트에 사용
			$this->make_thumb($data,$this->input->post("member_id"), 890, "");		//본문에 사용
		}

		$param = Array(
			"title"					=> $this->input->post("title"),
			"thumb_name"		=> $thumb_name,
			"category"	=> $this->input->post("category"),
			"content"	=> $this->input->post("content"),
			"tag"		=> $this->input->post("tag"),
			"is_activated" => $this->input->post("is_activated"),
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->load->model("Mportfolio");
		$idx = $this->Mportfolio->insert($param);

		redirect("adminportfolio/index","refresh");
	}

	/**
	 * 대표 이미지 썸네일 만들기
	 */
	private function make_thumb($data, $member_id, $width=300, $folder="thumb/")
	{
		//썸네일 만들기
		if($data["upload_data"]["image_width"] < $width){	
			$width = $data["upload_data"]["image_width"];
		}

		$thumb_config['image_library'] = 'gd2';
		$thumb_config['source_image'] = HOME.'/uploads/portfolios/'. $data["upload_data"]["file_name"];
		$thumb_config['new_image']	  = HOME.'/uploads/portfolios/'.$folder.$data["upload_data"]["file_name"];
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

	/**
	 * 블로그 수정
	 */
	function edit($id){
		$this->load->model("Mportfolio");
		$this->load->model("Mportfoliocategory");

		$data["query"] = $this->Mportfolio->get($id);
		$data["category"] = $this->Mportfoliocategory->get_list();

		$this->layout->admin('portfolio_edit', $data);
	}

	/**
	 * 매물 상세 보기 화면
	 * 
	 */
	function view($id){
		$this->load->model("Mportfolio");
		$this->load->model("Mblogapi");

		$data["blog"]	= $blog = $this->Mblogapi->get_valid_list();

		$data["query"] = $this->Mportfolio->get($id);
		$this->layout->admin('portfolio_view', $data);	
	}

	function edit_action(){

		$config['upload_path'] = HOME.'/uploads/portfolios';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		
		$thumb_name = "";
		if ( ! $this->upload->do_upload("thumb_name"))
		{
			$error = array('error' => $this->upload->display_errors());
			//echo $this->upload->display_errors();
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$thumb_name = $data["upload_data"]["file_name"];
			$this->make_thumb($data, $this->input->post("member_id"), 300, "thumb/"); //리스트에 사용
			$this->make_thumb($data, $this->input->post("member_id"), 890, "");		//본문에 사용
		}

		$param = Array(
			"title"		=> $this->input->post("title"),
			"category"	=> $this->input->post("category"),
			"content"	=> $this->input->post("content"),
			"tag"		=> $this->input->post("tag")
		);

		if($thumb_name!=""){
			$param["thumb_name"] = $thumb_name;
		}

		$this->load->model("Mportfolio");
		$this->Mportfolio->update($param,$this->input->post("id"));

		redirect("adminportfolio/index","refresh");
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		if(!file_exists(HOME.'/uploads/portfolios/contents')){
			mkdir(HOME.'/uploads/portfolios/contents',0777);
			chmod(HOME.'/uploads/portfolios/contents',0777);
		}
		$config['upload_path'] = HOME.'/uploads/portfolios/contents/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$filename = "";
		if ( ! $this->upload->do_upload("uploadfile"))
		{
			echo $CI->image_lib->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			$this->make_body($data);
			echo "/uploads/portfolios/contents/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data)
	{
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 890){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/portfolios/contents/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 890;
			$thumb_config['height'] = intval($data["upload_data"]["image_height"])*$thumb_config['width']/intval($data["upload_data"]["image_width"]);
			$thumb_config['quality'] = "100%";
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
	}

	/**
	 * 물건의 상태 변경
	 * $type : is_activated, is_finished, is_speed, recommand
	 */
	function change($type, $id, $status){
		$this->load->model("Mportfolio");
		$param = Array($type=>$status);
		$this->Mportfolio->change($param,$id);
		echo "1";
	}

	/**
	 * 삭제 권한 체크 기능 구현해야 함
	 */
	function remove($id){

		$this->load->model("Mportfolio");
		$portfolio = $this->Mportfolio->get($id);
		//메인이미지 삭제
		@unlink(HOME.'/uploads/portfolios/'.$portfolio->thumb_name);	
		@unlink(HOME.'/uploads/portfolios/thumb/'.$portfolio->thumb_name);				
		//DB 삭제
		$this->Mportfolio->romove($id);
		redirect("adminportfolio/index","refresh");
	}
}

/* End of file Adminblog.php */
/* Location: ./application/controllers/Adminblog.php */

