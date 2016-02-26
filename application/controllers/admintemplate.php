<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admintemplate extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->layout->admin('template_index');
	}

	public function edit($id){
		$this->load->model("Mintro");
		$data["query"] = $this->Mintro->get($id);
		$this->layout->admin('intro_edit',$data);
	}

	public function edit_action(){
		$this->load->model("Mintro");

		$param = Array(
			"title" => $this->input->post("title"),
			"content" => $this->input->post("content"),
			"flag" => $this->input->post("flag")
		);

		$this->Mintro->update($param,$this->input->post("id"));

		redirect("adminintro/index","refresh");
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
		if (!$this->upload->do_upload("uploadfile")){
			echo $CI->image_lib->display_errors();
			return false;
		}
		else{
			$data = array('upload_data' => $this->upload->data());
			$filename = $data["upload_data"]["file_name"];
			$this->make_body($data);
			echo "/uploads/contents/". $data["upload_data"]["file_name"];
		}
	}
}

/* End of file admintemplate.php */
/* Location: ./application/controllers/admintemplate.php */