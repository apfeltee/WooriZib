<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 관리자 권한 관리
 */
class Adminauth extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Madminauth");
		$data["query"] = $this->Madminauth->get_auth_list();
		$this->layout->admin('auth_index',$data);
	}

	public function get_json($id){
		$this->load->model("Madminauth");
		$query = $this->Madminauth->get($id);
		echo json_encode($query);
	}

	public function get_others_json($id){
		$this->load->model("Madminauth");
		$query = $this->Madminauth->get_others($id);
		echo json_encode($query);
	}

	public function add_action(){		
		$param = Array(
			"auth_name" => $this->input->post("auth_name"),
			"auth_home" => $this->input->post("auth_home"),
			"auth_product" => $this->input->post("auth_product"),
			"auth_member" => $this->input->post("auth_member"),
			"auth_contact" => $this->input->post("auth_contact"),
			"auth_request" => $this->input->post("auth_request"),
			"auth_news" => $this->input->post("auth_news"),
			"auth_portfolio" => $this->input->post("auth_portfolio"),
			"auth_set" => $this->input->post("auth_set"),
			"auth_custom" => $this->input->post("auth_custom"),
			"auth_popup" => $this->input->post("auth_popup"),
			"auth_layout" => $this->input->post("auth_layout"),
			"auth_stats" => $this->input->post("auth_stats"),
			"auth_pay" => $this->input->post("auth_pay")
		);
		$this->load->model("Madminauth");
		$this->Madminauth->insert($param);
		redirect("adminauth/index","refresh");
	}

	public function edit_action(){		
		$param = Array(
			"auth_name" => $this->input->post("auth_name"),
			"auth_home" => $this->input->post("auth_home"),
			"auth_product" => $this->input->post("auth_product"),
			"auth_member" => ($this->input->post("auth_member")) ? $this->input->post("auth_member") : "Y",
			"auth_contact" => $this->input->post("auth_contact"),
			"auth_request" => $this->input->post("auth_request"),
			"auth_news" => $this->input->post("auth_news"),
			"auth_portfolio" => $this->input->post("auth_portfolio"),
			"auth_set" => $this->input->post("auth_set"),
			"auth_custom" => $this->input->post("auth_custom"),
			"auth_popup" => $this->input->post("auth_popup"),
			"auth_layout" => $this->input->post("auth_layout"),
			"auth_stats" => $this->input->post("auth_stats"),
			"auth_pay" => $this->input->post("auth_pay")
		);
		$this->load->model("Madminauth");
		$this->Madminauth->update($this->input->post("id"),$param);
		redirect("adminauth/index","refresh");
	}

	public function delete_action(){
		$this->load->model("Mmember");
		$this->load->model("Madminauth");
		$this->Mmember->change_member_auth($this->input->post("delete_id"),$this->input->post("change_id"));
		$this->Madminauth->delete_auth($this->input->post("delete_id"));
		redirect("adminauth/index","refresh");
	}
}

/* End of file Adminauth.php */
/* Location: ./application/controllers/Adminauth.php */