<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class News extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}
	
	/**
	 * news list
	 */
	public function index($category="0",$page=0){
		$data["page_title"] =  "뉴스";

		$this->load->library('pagination');
		$this->load->model("Mnews");
		$this->load->model("Mnewscategory");
		$this->load->model("Mproduct");
		$this->load->model("Mlog");
		$this->load->model("Mhope");

		$config['base_url'] = '/news/index/'.$category."/";
		$config['total_rows'] = $this->Mnews->get_total_count($category,"front");

		$config['per_page'] = 20;
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

		$data["panel_history"]	=  $this->Mlog->get_list($this->session->userdata("session_id"),10,0);
		$data["panel_hope"]		=  $this->Mhope->get_list($this->session->userdata("session_id"),10,0);

		$data["current_newscategory"] = $category; //현재 선택되어 있는 카테고리를 표시
		$data["newscategory"] = $this->Mnewscategory->get_list();
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();
		$data["result"] = $this->Mnews->get_list($category, $config['per_page'], $page, "front");

		$data["recent"] = $this->Mproduct->get_recent(0);

		$this->layout->view('basic/news_index', $data);
	}

	/**
	 * news view
	 *
	 * 20140803 - 본 이력내역과 관심 내역을 각각 보여주는 기능을 추가한다.
	 * 20141007 - 카테고리가 비공개일 경우에는 회원가입을 해야지만 볼 수 있도록 한다.
	 */
	public function view($id){
		$this->load->model("Mnews");
		$this->load->model("Mproduct");
		$this->load->model("Mnewscategory");			// 카테고리 정보를 가져와 인증이 필요한지 여부를 판단한다.
		$this->load->model("Mlog");
		$this->load->model("Mhope");

		$news = $this->Mnews->get($id);		
		if($news==null){
			redirect("news/index","refresh");
			exit;
		}

		$this->Mnews->view($id);					// 조회수 증가
		$data["page_title"] =  $news->title;

		$data["panel_history"]	=  $this->Mlog->get_list($this->session->userdata("session_id"),10,0);
		$data["panel_hope"]		=  $this->Mhope->get_list($this->session->userdata("session_id"),10,0);

		$data["query"] = $news;
		$data["id"] = $id;
		$data["newscategory"] = $this->Mnewscategory->get_list();
		$data["cate"] = $this->Mnewscategory->get($news->category);	// category는 layout에서 넘어가니 여기서는 단일로 cate로 변경한다.
		$this->layout->view('basic/news_view',$data);
	}

	/**
	 * 홈에서 최신매물 우측의 탭으로 보여줄 뉴스 리스트 5개
	 * 2015년 10월 1일 강대중
	 */
	public function home_list(){
		$this->load->model("Mnews");
		$data["news"] = $this->Mnews->get_last("all",4);
		$this->load->view(THEME.'/template/home_tabs_news',$data);
	}
}

/* End of file news.php */
/* Location: ./application/controllers/news.php */