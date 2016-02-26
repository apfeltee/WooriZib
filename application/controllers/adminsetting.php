<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminsetting extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_type")!="super"){
			redirect("adminhome/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mspot");
		$this->layout->admin('setting_index');
	}


	public function sql($sql){
		$this->load->helper('file');
		$this->load->model("Mlog");

		$this->Mlog->init_db(read_file(HOME."/data/".$sql));

		redirect("adminsetting/index","refresh");
	}

	public function edit_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"address" => $this->input->post("address"),
			"lat" => $this->input->post("lat"),
			"lng" => $this->input->post("lng"),
			"content" => $this->input->post("content")
		);
		$this->load->model("Mspot");
		$this->Mspot->update($this->input->post("id"),$param);
		redirect("adminspot/index","refresh");
	}
}

/* End of file adminsetting.php */
/* Location: ./application/controllers/adminsetting.php */