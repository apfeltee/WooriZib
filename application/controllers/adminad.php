<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminad extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function keyword(){
		$this->load->model("Maddress");
		$this->load->model("Mcategory");
		$this->load->model("Mtheme");
		$this->load->model("Mdanzi");

		$data["category"] = $this->Mcategory->get_list();
		$data["query"] = $this->Maddress->get_ad_dong();
		$data["danzi"] = $this->Mdanzi->get_danzi_products();
		$data["theme"] = $this->Mtheme->get_list();

		$this->layout->admin('ad_keyword', $data);
	}


	function app(){
		$this->load->model("Maddress");
		$this->load->model("Mcategory");
		$data["category"] = $this->Mcategory->get_list();
		$data["query"] = $this->Maddress->get_ad_dong();
		$this->layout->admin('app_keyword', $data);	
	}

}

/* End of file Adminad.php */
/* Location: ./application/controllers/Adminad.php */