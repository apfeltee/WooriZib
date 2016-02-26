<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ask extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	/**
	 * 문의하기 페이지
	 */
	public function index($page=0){

		$this->load->model("Mconfig");
		$this->load->model("Mask");

		$config = $this->Mconfig->get();

		$data["page_title"] =  "문의하기";

		if($config->ASK_TYPE){

			$this->load->library('pagination');
			$page_config['base_url'] = "/ask/index/";
			$page_config['total_rows'] = $this->Mask->get_total_count();
			$page_config['per_page'] = 15;
			$page_config['uri_segment'] = count($this->uri->segment_array());
			$page_config['first_link'] = '<<';
			$page_config['first_tag_open'] = '<li>';
			$page_config['first_tag_close'] = '</li>';

			$page_config['last_link'] = '>>';
			$page_config['last_tag_open'] = '<li>';
			$page_config['last_tag_close'] = '</li>';

			$page_config['num_tag_open'] = "<li>";
			$page_config['num_tag_close'] = "</li>";
			$page_config['cur_tag_open'] = '<li class="active"><a href="#">';
			$page_config['cur_tag_close'] = '</a></li>';

			$page_config['next_link'] = '>';
			$page_config['next_tag_open'] = '<li>';
			$page_config['next_tag_close'] = '</li>';

			$page_config['prev_link'] = '<';
			$page_config['prev_tag_open'] = '<li>';
			$page_config['prev_tag_close'] = '</li>';

			$data["query"] = $this->Mask->get_list($page_config['per_page'], $page);

			$this->pagination->initialize($page_config);
			$data["pagination"] = $this->pagination->create_links();

			$this->layout->view('basic/ask_list',$data);
		}
		else{
			$this->layout->view('basic/ask_index',$data);
		}
	}

	/**
	 * 문의하기 정보 반환
	 */
	function get(){
		$this->load->model("Mask");
		$query = $this->Mask->get($this->input->post("ask_id"));
		echo json_encode($query);
	}

	/**
	 * 문의하기 비밀번호 체크 후 정보 반환
	 */
	public function ask_pw(){

		$this->load->model("Mask");

		$query = $this->Mask->get($this->input->post("ask_id"));

		$pw = $this->_prep_password($this->input->post("pw"));

		if($pw==$query->pw){
			echo json_encode($query);
		}
	}

	/**
	 * 문의하기 등록 페이지
	 */
	function add(){
		$data["page_title"] =  "문의하기";
		$this->layout->view('basic/ask_index',$data);
	}

	/**
	 * 문의하기 페이지에서 문의등록 하기
	 */
	function add_action(){

		$this->load->model("Mask");

		$param = Array(
			"title"	=> $this->input->post("title"),
			"name"	=> $this->input->post("name"),
			"email"	=> $this->input->post("email"),
			"phone"	=> $this->input->post("phone"),
			"content"	=> $this->input->post("content"),
			"date"	=> date('Y-m-d H:i:s')
		);

		if($this->input->post("open")){
			$param["open"] = $this->input->post("open");
		}

		if($this->input->post("pw")){
			$param["pw"] = $this->_prep_password($this->input->post("pw"));
		}

		$this->Mask->insert($param);

		redirect("ask/index","refresh");
	}

	/**
	 * 비밀번호 인크립션
	 */
	private function _prep_password($password){
		 return sha1($password.$this->config->item('encryption_key'));
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
			echo $CI->image_lib->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			$this->make_body($data);
			echo "/uploads/contents/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data){
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 500){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/contents/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 500;
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
}

/* End of file ask.php */
/* Location: ./application/controllers/ask.php */