<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminpay extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index($page=0){
		$this->load->model("Mpay");

		$this->load->library('pagination');

		$param = Array(			
			"member"=>$this->input->get("member_id"),
			"date1"=>$this->input->get("date1"),
			"date2"=>$this->input->get("date2")
		);		

		$param = array_filter($param);

		$config['base_url'] = "/adminpay/index/";
		$config['total_rows'] = $this->Mpay->get_admin_total($param);
		$config['per_page'] = 15;
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['num_tag_open'] = "<li>";
		$config['num_tag_close'] = "</li>";
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';

		$config['next_link'] = '>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();

		$data['total'] = $config['total_rows'];
		$data["query"] = $this->Mpay->get_admin_list($param, $config['per_page'], $page);
		$this->layout->admin('pay_index',$data);
	}

	public function setting(){
		$this->load->model("Mpay");
		$data["query"] = $this->Mpay->get_setting_list();
		$this->layout->admin('pay_setting',$data);
	}

	public function sorting($id,$sorting){
		$this->load->model("Mpay");
		$param = Array("sorting"=>$sorting);
		$this->Mpay->sorting_update($id,$param);
	}

	public function setting_get_json($id){
		$this->load->model("Mpay");
		$query = $this->Mpay->setting_get($id);
		echo json_encode($query);
	}

	public function setting_add_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"day" => $this->input->post("day"),
			"count" => $this->input->post("count"),
			"price" => $this->input->post("price")
		);

		$this->load->model("Mpay");
		$this->Mpay->settting_insert($param);
		redirect("adminpay/setting","refresh");
	}

	public function setting_edit_action(){
		$param = Array(
			"name" => $this->input->post("name"),
			"day" => $this->input->post("day"),
			"count" => $this->input->post("count"),
			"price" => $this->input->post("price")
		);

		$this->load->model("Mpay");
		$this->Mpay->settting_update($this->input->post("id"),$param);
		redirect("adminpay/setting","refresh");
	}

	public function setting_delete_action(){
		$this->load->model("Mpay");
		$this->Mpay->delete_setting($this->input->post("delete_id"));
		redirect("adminpay/setting","refresh");
	}

}

/* End of file Adminpay.php */
/* Location: ./application/controllers/Adminpay.php */