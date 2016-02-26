<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 블로그 카테고리
 */
class Adminnewscategory extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index($valid="Y"){
		$this->load->model("Mnewscategory");
		$data["query"] = $this->Mnewscategory->get_list();
		$this->layout->admin('news_category',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mnewscategory");
		$param = Array("sorting"=>$sorting);
		$this->Mnewscategory->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mnewscategory");
		$query = $this->Mnewscategory->get($id);
		echo json_encode($query);
	}

	public function get_others_json($id){
		$this->load->model("Mnewscategory");
		$query = $this->Mnewscategory->get_others($id);
		echo json_encode($query);
	}

	public function add_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid")
		);

		$this->load->model("Mnewscategory");
		$this->Mnewscategory->insert($param);
		redirect("adminnewscategory/index","refresh");
	}

	public function edit_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid")
		);

		$this->load->model("Mnewscategory");
		$this->Mnewscategory->update($this->input->post("id"),$param);
		redirect("adminnewscategory/index","refresh");
	}

	public function delete_action(){
		$this->load->model("Mnewscategory");
		$this->Mnewscategory->change_area_products($this->input->post("delete_id"),$this->input->post("change_id"));
		$this->Mnewscategory->delete_news($this->input->post("delete_id"));
		redirect("adminnewscategory/index","refresh");
	}
}

/* End of file adminnewscategory.php */
/* Location: ./application/controllers/adminnewscategory.php */