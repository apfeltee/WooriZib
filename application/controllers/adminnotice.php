<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminnotice extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index($page="0"){

		$this->load->library('pagination');
		$this->load->model("Mnotice");

		$config['base_url'] = '/adminnotice/index/';
		$config['total_rows'] = $this->Mnotice->get_total_count();

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

		$data["pagination"] = $this->pagination->initialize($config);
		$data["result"] = $this->Mnotice->get_list($config['per_page'], $page);

		$this->layout->admin('notice_index', $data);
	}

	/**
	 * 매물 추가 화면
	 */
	function add(){
		$this->layout->admin('notice_add');
	}

	function add_action(){
		$param = Array(
			"title"					=> $this->input->post("title"),
			"content"	=> $this->input->post("content"),
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->load->model("Mnotice");
		$idx = $this->Mnotice->insert($param);

		redirect("adminnotice/index","refresh");
	}

	/**
	 * 공지 수정
	 */
	function edit($id){
		$this->load->model("Mnotice");

		$data["query"] = $this->Mnotice->get($id);
		
		$this->layout->admin('notice_edit', $data);
	}

	function view($id){
		$this->load->model("Mnotice");
		
		$data["query"] = $this->Mnotice->get($id);
		$this->layout->admin('notice_view', $data);	
	}

	function edit_action(){

		$param = Array(
			"title"		=> $this->input->post("title"),
			"content"	=> $this->input->post("content")
		);

		$this->load->model("Mnotice");
		$this->Mnotice->update($param,$this->input->post("id"));

		redirect("adminnotice/index","refresh");
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		$config['upload_path'] = HOME.'/uploads/notices/';
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
			echo "/uploads/notices/". $data["upload_data"]["file_name"];
		}
	}

	private function make_body($data)
	{
		//썸네일 만들기
		if($data["upload_data"]["image_width"] > 560){

			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/notices/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 560;
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
	 * $type : is_popup
	 */
	function change($type, $id, $status){
		$this->load->model("Mnotice");
		$param = Array($type=>$status);
		$this->Mnotice->change($param,$id);
		echo "1";
	}

	function delete_notice($id){
		$this->load->Model("Mnotice");
		$notice = $this->Mnotice->get($id);
		$this->Mnotice->delete_notice($id);
		redirect("adminnotice/index","refresh");
	}
}

/* End of file Adminnotice.php */
/* Location: ./application/controllers/Adminnotice.php */

