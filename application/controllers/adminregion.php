<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminregion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mregion");
		$data["query"] = $this->Mregion->get_list();
		$this->layout->admin('region_index',$data);
	}
	
	public function add_action(){
		$this->load->model("Mregion");
		$param = Array(
			"name" => $this->input->post("name"),
			"address_id" => $this->input->post("address_id")
		);
		$this->Mregion->insert($param);
		redirect("adminregion/index","refresh");
	}

	public function delete_action($id){
		$this->load->model("Mregion");
		$this->Mregion->delete($id);
	}

}

/* End of file adminregion.php */
/* Location: ./application/controllers/adminregion.php */

