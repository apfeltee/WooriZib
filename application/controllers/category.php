<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 관리자용으로는 Admincategory가 있는데 Front에서 함께 써야 하는 기능들을 여기에 제작해 놓고 사용한다.
 */
class Category extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 매물 종류 ID로 가져오면 안되고 매물 종류의 main 값으로 가져와야 한다.
	 * 폼은 메인아이디값을 기준으로 구성을 하기 때문이다.
	 * 	 
	 * 1 - 원룸/투룸
	 * 2 - 아파트
	 * 3 - 주택
	 * 4 - 빌라
	 * 5 - 오피스텔
	 * 6 - 상가/점포
	 * 7 - 토지/임야
	 * 8 - 경매
	 * 9 - 공장
	 * 
	 */
	public function get_form_json($id){
		$this->load->model("Mcategory");
		$category = $this->Mcategory->get($id);
		$query = $this->Mcategory->get_form($category->main);
		echo json_encode($query);
	}
}

/* End of file category.php */
/* Location: ./application/controllers/category.php */