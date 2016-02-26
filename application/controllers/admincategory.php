<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admincategory extends CI_Controller {

	public function __construct() {
		parent::__construct();

		$user_check = true;

		if($this->uri->segment(2)=="get_option_json") $user_check = false;
		if($this->uri->segment(2)=="get_input_json") $user_check = false;
		
		if($this->session->userdata("admin_id")=="" && $user_check){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index($valid="Y"){
		$this->load->model("Mcategory");
		$data["query"] = $this->Mcategory->get_list_all($valid);
		foreach($data["query"] as $key=>$val){
			$category_sub = $this->Mcategory->get_sub_list($val->id);
			if($category_sub){
				$data["query"][$key]->category_sub = $category_sub;
			}
		}
		$this->layout->admin('category_index',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mcategory");
		$param = Array("sorting"=>$sorting);
		$this->Mcategory->update($id,$param);
	}

	public function get_json($id){
		$this->load->model("Mcategory");
		$query = $this->Mcategory->get($id);
		echo json_encode($query);
	}

	/**
	 * 해당 종류의 옵션 항목을 반환합니다.
	 */
	public function get_option_json($id){
		$this->load->model("Mcategory");
		$query = $this->Mcategory->get($id);
		echo json_encode(explode(",",$query->template));
	}

	/**
	 * 해당 종류의 추가 입력 항목을 반환합니다.
	 */
	public function get_input_json($id){
		$this->load->model("Mcategory");
		$query = $this->Mcategory->get($id);
		echo json_encode(explode(",",$query->meta));	
	}

	public function get_others_json($id){
		$this->load->model("Mcategory");
		$query = $this->Mcategory->get_others($id);
		echo json_encode($query);
	}

	public function add_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"main" => $this->input->post("main"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid"),
			"template" => $this->input->post("template"),
			"meta" => $this->input->post("meta")
		);

		$this->load->model("Mcategory");
		$this->Mcategory->insert($param);
		redirect("admincategory/index","refresh");
	}

	public function edit_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"main" => $this->input->post("main"),
			"opened" => $this->input->post("opened"),
			"valid" => $this->input->post("valid"),
			"template" => $this->input->post("template"),
			"meta" => $this->input->post("meta")
		);

		$this->load->model("Mcategory");
		$this->Mcategory->update($this->input->post("id"),$param);
		redirect("admincategory/index","refresh");
	}

	public function delete_action(){
		$this->load->model("Mcategory");
		$this->Mcategory->change_area_products($this->input->post("delete_id"),$this->input->post("change_id"));
		$this->Mcategory->delete_area($this->input->post("delete_id"));
		redirect("admincategory/index","refresh");
	}

	/**
	 * 소분류 추가
	 */
	public function add_sub_action(){
		$this->load->model("Mcategory");
		$max_sorting = $this->Mcategory->get_sub_last($this->input->post("main_id"));
		$param = Array(
			"main_id" => $this->input->post("main_id"),
			"name" => $this->input->post("sub_name"),
			"sorting" => $max_sorting + 1
		);
		$this->Mcategory->insert_sub($param);
		redirect("admincategory/index","refresh");
	}

	/**
	 * 소분류 삭제
	 */
	public function delete_sub($id){
		$this->load->model("Mcategory");
		$this->load->model("Madminproduct");
		$this->Madminproduct->sub_update($id);
		$this->Mcategory->delete_sub($id);
	}

	/**
	 * 소분류 순서 변경
	 */
	public function sorting_sub(){
		$this->load->model("Mcategory");

		$main = $this->input->post("main");
		$sub = array_filter($this->input->post("sub"));

		foreach($main as $key=>$val){

			$main_param = Array(
				"sorting" => $key + 1
			);

			$this->Mcategory->update($val,$main_param);		
		}

		foreach($sub as $key=>$val){

			$category_sub = array_filter(explode(",",$val));

			foreach($category_sub as $s_key=>$s_val){
				$sub_param = Array(
					"main_id" => $key,
					"sorting" => $s_key + 1
				);
				$this->Mcategory->update_sub($s_val,$sub_param);
			}
		}

		echo "1";
	}

	public function edit_sub_action(){
		$this->load->model("Mcategory");
		$param = array(
			"name" => $this->input->post("sub_name")
		);
		$this->Mcategory->update_sub($this->input->post("sub_id"),$param);
		redirect("admincategory/index","refresh");
	}
}

/* End of file admincategory.php */
/* Location: ./application/controllers/admincategory.php */