<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminask extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index($page="0"){

		$this->load->model("Mask");
		$this->load->library('pagination');

		$config['base_url'] = '/adminask/index/';
		$config['total_rows'] = $this->Mask->get_total_count();

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

		$data["result"] = $this->Mask->get_list($config['per_page'], $page);

		$this->layout->admin('ask_index', $data);
	}

	function view($id){
		$this->load->model("Mask");
		
		$data["query"] = $this->Mask->get($id);
		$this->layout->admin('ask_view', $data);	
	}

	function delete_ask($id){
		$this->load->Model("Mask");
		$this->Mask->delete_ask($id);
		redirect("adminask/index","refresh");
	}

	function answer(){
		$this->load->Model("Mask");
		$param = Array(
			"answer" => $this->input->post("answer")
		);
		$this->Mask->update($param,$this->input->post("id"));
		redirect("adminask/view/".$this->input->post("id"),"refresh");
	}
}

/* End of file Adminask.php */
/* Location: ./application/controllers/Adminask.php */

