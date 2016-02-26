<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Real Estate Theme Admin Control Class
 *
 *
 * @package		CodeIgniter
 * @subpackage	Controller
 * @author		Dejung Kang
 */
class Adminloan extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mloan");
		$data["query"] = $this->Mloan->get_list();
		$this->layout->admin('loan_index',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mloan");
		$param = Array("sorting"=>$sorting);
		$this->Mloan->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mloan");
		$query = $this->Mloan->get($id);
		echo json_encode($query);
	}

	public function add_action(){

		$this->load->model("Mloan");

		$sorting = $this->Mloan->get_max_sorting();

		$param = Array(
			"bank_name" => $this->input->post("bank_name"),
			"rate_min" => $this->input->post("rate_min"),
			"rate_max" => $this->input->post("rate_max"),
			"rate_loan" => $this->input->post("rate_loan"),
			"etc" => $this->input->post("etc"),
			"sorting" => $sorting + 1
		);

		$this->Mloan->insert($param);
		redirect("adminloan/index","refresh");
	}

	public function edit_action(){

		$this->load->model("Mloan");

		$param = Array(
			"bank_name" => $this->input->post("bank_name"),
			"rate_min" => $this->input->post("rate_min"),
			"rate_max" => $this->input->post("rate_max"),			
			"etc" => $this->input->post("etc")
		);

		$this->Mloan->update($this->input->post("id"),$param);
		redirect("adminloan/index","refresh");
	}

	public function delete_loan($id){
		$this->load->model("Mloan");
		$this->Mloan->delete_loan($id);
		redirect("adminloan/index","refresh");
	}
}

/* End of file adminloan.php */
/* Location: ./application/controllers/adminloan.php */