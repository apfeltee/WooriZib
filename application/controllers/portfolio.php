<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Portfolio extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}
	
	public function index($categoty=""){
		$data["page_title"] =  "갤러리";

		$this->load->model("Mportfoliocategory");
		$data["portfolio_category"] = $this->Mportfoliocategory->get_list($categoty);
		$data["page_category"] = $categoty;
		$this->layout->view('basic/portfolio_index',$data);
	}

	public function get_json($category="0", $page="0"){
		$this->load->library('pagination');
		$this->load->model("Mportfolio");
		$total_rows = $this->Mportfolio->get_total_count($category);
		$per_page = 12;
		$query["total"] = $total_rows;
		if($total_rows <= 12){
			$query["paging"] = 0;
		} else {
			$query["paging"] =  $page+$per_page;
		}

		$result["portfolio"] = $this->Mportfolio->get_list($category, $per_page, $page);
		$this->layout->setLayout("list");
		$query["result"]   = $this->layout->view("basic/template/portfolio1",$result ,true);

		echo json_encode($query);
	}


	/**
	 * blog view
	 *
	 * 20140803 - 본 이력내역과 관심 내역을 각각 보여주는 기능을 추가한다.
	 * 20141007 - 카테고리가 비공개일 경우에는 회원가입을 해야지만 볼 수 있도록 한다.
	 */
	public function view($id){
		$this->load->model("Mportfolio");
		$this->load->model("Mportfoliocategory");

		$portfolio = $this->Mportfolio->get($id);		
		if($portfolio==null){
			redirect("portfolio/index","refresh");
			exit;
		}

		$this->Mportfolio->view($id);					// 조회수 증가
		$data["page_title"] =  $portfolio->title;

		$data["query"] = $portfolio;
		$data["id"] = $id;
		$data["portfoliocategory"] = $this->Mportfoliocategory->get_list();
		$data["cate"] = $this->Mportfoliocategory->get($portfolio->category);	// category는 layout에서 넘어가니 여기서는 단일로 cate로 변경한다.
		$this->layout->view('basic/portfolio_view',$data);
	}
}

/* End of file blog.php */
/* Location: ./application/controllers/blog.php */