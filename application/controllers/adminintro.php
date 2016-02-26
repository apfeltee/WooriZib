<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminintro extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mintro");
		$data["query"] = $this->Mintro->get_list();
		$this->layout->admin('intro_index',$data);
	}

	public function add(){
		$this->load->model("Mintro");
		$this->layout->admin('intro_add');
	}

	public function add_action(){
		$this->load->model("Mintro");
		$sorting = $this->Mintro->get_max_sorting();

		$param = Array(
			"title" => $this->input->post("title"),
			"content" => $this->input->post("content"),
			"flag" => $this->input->post("flag"),
			"sorting" => $sorting + 1
		);

		$this->Mintro->insert($param);
		redirect("adminintro/index","refresh");
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

	private function make_body($data){
		if($data["upload_data"]["image_width"] > 1200){
			$thumb_config['image_library'] = 'gd2';
			$thumb_config['source_image'] = HOME."/uploads/contents/". $data["upload_data"]["file_name"];
			$thumb_config['create_thumb'] = FALSE;
			$thumb_config['maintain_ratio'] = TRUE;
			$thumb_config['width'] = 1200;
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

	function delete_action($id){
		$this->load->Model("Mintro");
		$this->Mintro->delete($id);
		redirect("adminintro/index","refresh");
	}

	public function sorting($id,$sorting){
		$this->load->model("Mintro");
		$param = Array("sorting"=>$sorting);
		$this->Mintro->update($param,$id);
	}
}

/* End of file adminintro.php */
/* Location: ./application/controllers/adminintro.php */