<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminmenu extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	public function index(){
		$this->load->model("Mmainmenu");
		$data["query"] = $this->Mmainmenu->get_list();
		$this->layout->admin('mainmenu_index', $data);
	}

	public function update(){
		$this->load->model("Mmainmenu");
		$id = $this->input->post("id");
		$param = Array(
			"title"	=> $this->input->post("title"),
			"flag"	=> $this->input->post("flag")
		);
		$this->Mmainmenu->update($id,$param);
		redirect("adminmenu/index","refresh");
	}

	public function sorting($id,$sorting){
		$this->load->model("Mmainmenu");
		$param = Array("sorting"=>$sorting);
		$this->Mmainmenu->update($id,$param);
	}

}

/* End of file adminmenu.php */
/* Location: ./application/controllers/adminmenu.php */