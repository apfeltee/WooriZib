<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminspot extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mspot");
		$data["query"] = $this->Mspot->get_list();
		$this->layout->admin('spot_index',$data);
	}


	public function get_json($id){
		$this->load->model("Mspot");
		$query = $this->Mspot->get($id);
		echo json_encode($query);
	}

	public function add_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"address" => $this->input->post("address"),
			"lat" => $this->input->post("lat"),
			"lng" => $this->input->post("lng"),
			"content" => $this->input->post("content"),
			"date"		=> date('Y-m-d H:i:s')
		);

		$this->load->model("Mspot");
		$this->Mspot->insert($param);
		redirect("adminspot/index","refresh");
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

	public function delete_action($id){	
		$this->load->model("Mspot");
		$this->Mspot->delete_spot($id);
		redirect("adminspot/index","refresh");
	}
}

/* End of file adminspot.php */
/* Location: ./application/controllers/adminspot.php */