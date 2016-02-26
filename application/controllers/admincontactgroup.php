<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincontactgroup extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}
	
	public function add_action(){
		
		$param = Array(
			"group_name"	=> 	$this->input->post("group_name")
		);
		
		$this->load->model("Mcontactgroup");
		$this->Mcontactgroup->insert($param);
		echo "1";
	}

	public function edit_action(){
		$this->load->model("Mcontactgroup");
		$param = Array("group_name"=>$this->input->post("change_name"));
		$this->Mcontactgroup->update($this->input->post("group_id"),$param);
		echo "1";
	}

	public function get_list(){
		$this->load->model("Mcontactgroup");
		echo json_encode($this->Mcontactgroup->get_list());
	}


	/**
	 * 그룹 삭제시 삭제할 그룹을 다른 그룹으로 대체한 후에 삭제한다.
	 */
	public function delete_action(){
		$this->load->model("Mcontact");
		$this->Mcontact->update_group($this->input->post("delete_id"),$this->input->post("replace_id"));
		$this->load->model("Mcontactgroup");
		$this->Mcontactgroup->delete_group($this->input->post("delete_id"));
		echo "1";
	}
}

/* End of file Admincontactgroup.php */
/* Location: ./application/controllers/Admincontactgroup.php */