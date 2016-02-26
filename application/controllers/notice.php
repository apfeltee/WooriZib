<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	function index($page="0"){

		$this->load->library('pagination');
		$this->load->model("Mnotice");

		$config['base_url'] = '/notice/index/';
		$config['total_rows'] = $this->Mnotice->get_total_count();

		$config['per_page'] = 5;
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

		$data["result"] = $this->Mnotice->get_list($config['per_page'], $page);

		$data["page_title"] =  "공지사항";
		$this->layout->view('basic/notice_index',$data);
	}

	public function get_json($id){
		$this->load->model("Mnotice");
		$query = $this->Mnotice->get($id);
		echo json_encode($query);
	}

	/**
	 * 홈에서 최신매물 우측의 탭으로 보여줄 공지사항 리스트 5개
	 * 2015년 10월 1일 강대중
	 */
	public function home_list(){
		$this->load->model("Mnotice");
		$data["news"] = $this->Mnotice->get_last(4);
		$this->load->view(THEME.'/template/home_tabs_notice',$data);
	}	
}

/* End of file Notice.php */
/* Location: ./application/controllers/Notice.php */

