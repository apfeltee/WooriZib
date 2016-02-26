<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminfaq extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index($page="0"){

		$this->load->model("Mfaq");

		$data["result"] = $this->Mfaq->get_list();

		$this->layout->admin('faq_index', $data);
	}

	function add(){
		$this->layout->admin('faq_add');
	}	

	function add_action(){

		$this->load->model("Mfaq");

		$param = Array(
			"title"		=> $this->input->post("title"),
			"content"	=> $this->input->post("content"),
			"date"		=> date('Y-m-d H:i:s')
		);
		
		$max_query = $this->Mfaq->get_max_sorting();

		if($max_query){
			$param['sorting'] = $max_query->sorting + 1; 
		}

		$idx = $this->Mfaq->insert($param);

		redirect("adminfaq/index","refresh");
	}

	function edit($id){
		$this->load->model("Mfaq");

		$data["query"] = $this->Mfaq->get($id);
		
		$this->layout->admin('faq_edit', $data);
	}

	function view($id){
		$this->load->model("Mfaq");
		
		$data["query"] = $this->Mfaq->get($id);
		$this->layout->admin('faq_view', $data);	
	}

	function edit_action(){

		$param = Array(
			"title"		=> $this->input->post("title"),
			"content"	=> $this->input->post("content")
		);

		$this->load->model("Mfaq");
		$this->Mfaq->update($param,$this->input->post("id"));

		redirect("adminfaq/index","refresh");
	}

	function delete_faq($id){
		$this->load->Model("Mfaq");
		$this->Mfaq->delete_faq($id);
		redirect("adminfaq/index","refresh");
	}

	public function sorting($id,$sorting){
		$this->load->model("Mfaq");
		$param = Array("sorting"=>$sorting);
		$this->Mfaq->update($param,$id);
	}
}

/* End of file Adminfaq.php */
/* Location: ./application/controllers/Adminfaq.php */

