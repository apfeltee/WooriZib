<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminstats extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function site($page=0){
		$this->load->model("Mlog");

		$this->load->library('pagination');

		$param = Array(
			"mobile"=>$this->input->get("mobile"),
			"date1"=>$this->input->get("date1"),
			"date2"=>$this->input->get("date2")
		);		

		$param = array_filter($param);

		$config['base_url'] = "/adminstats/site/";
		$config['total_rows'] = $this->Mlog->get_site_total($param);
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
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
		$data['query'] = $this->Mlog->get_site_log($param, $config['per_page'], $page);

		$this->layout->admin('stats_site', $data);
	}

	function blog(){

	}

	function call($page=0){
		$this->load->model("Mlog");

		$this->load->library('pagination');

		$param = Array(			
			"member"=>$this->input->get("member_id"),
			"date1"=>$this->input->get("date1"),
			"date2"=>$this->input->get("date2")
		);		

		$param = array_filter($param);

		$config['base_url'] = "/adminstats/call/";
		$config['total_rows'] = $this->Mlog->get_call_total($param);
		if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($_GET, '', "&");
		$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
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

		//$data['call_log_member'] = $this->Mlog->call_log_members();
		$data['total'] = $config['total_rows'];
		$data['query'] = $this->Mlog->get_call_log('', $param, $config['per_page'], $page);

		$this->layout->admin('stats_call', $data);
	}
}

/* End of file Adminstats.php */
/* Location: ./application/controllers/Adminstats.php */

