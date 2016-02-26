<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminnewsconfig extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}
	
	/**
	 * 기본 설정 보기
	 */
	function index(){
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$this->layout->admin('newsconfig_index', $data);
	}

	/**
	 * 기본 설정 수정 저장
	 */
	function edit_action(){
		
		$param = Array(
			"news_flag"		=> $this->input->post("news_flag"),
			"news_ktitle"	=> $this->input->post("news_ktitle"),
			"news_etitle"	=> $this->input->post("news_etitle"),
			"news_reply"	=> $this->input->post("news_reply")
		);

		$this->load->model("Mconfig");
		$this->Mconfig->update($param,$this->input->post("id"));

		redirect("adminnewsconfig/index","refresh");
	}
}

/* End of file Adminnewsconfig.php */
/* Location: ./application/controllers/Adminnewsconfig.php */