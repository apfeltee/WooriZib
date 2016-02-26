<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 블로그 카테고리
 */
class Adminportfoliocategory extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index($valid="Y"){
		$this->load->model("Mportfoliocategory");
		$data["query"] = $this->Mportfoliocategory->get_list();
		$this->layout->admin('portfolio_category',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mportfoliocategory");
		$param = Array("sorting"=>$sorting);
		$this->Mportfoliocategory->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mportfoliocategory");
		$query = $this->Mportfoliocategory->get($id);
		echo json_encode($query);
	}

	public function get_others_json($id){
		$this->load->model("Mportfoliocategory");
		$query = $this->Mportfoliocategory->get_others($id);
		echo json_encode($query);
	}

	/**
	 * 2015-02-15 기존에는 순번을 입력받아서 넣었는데 자동으로 순번을 증가시켜서 입력하도록 수정하였다.
	 */
	public function add_action(){
		$this->load->model("Mportfoliocategory");
		
		$param = Array(
			"name" => $this->input->post("name"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid")
		);

		$this->Mportfoliocategory->insert($param);
		redirect("adminportfoliocategory/index","refresh");
	}

	public function edit_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid")
		);

		$this->load->model("Mportfoliocategory");
		$this->Mportfoliocategory->update($this->input->post("id"),$param);
		redirect("adminportfoliocategory/index","refresh");
	}

	public function delete_action(){
		$this->load->model("Mportfoliocategory");
		$this->Mportfoliocategory->change_portfolio_category($this->input->post("delete_id"),$this->input->post("change_id"));
		$this->Mportfoliocategory->delete_portfolio_category($this->input->post("delete_id"));
		redirect("adminportfoliocategory/index","refresh");
	}
}

/* End of file portfoliocategory.php */
/* Location: ./application/controllers/portfoliocategory.php */