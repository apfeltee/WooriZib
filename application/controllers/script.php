<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 메인의 형태를 여러 형태로 가져가기 위한 구조
 */
class Script extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	public function src($url){
		header('Content-type: text/plain; charset=utf-8');		
		$this->load->view('script/'.$url);
	}

	public function product_common(){

		header('Content-type: text/plain; charset=utf-8');
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();

		$this->load->view('script/product_common', $data);
	}

	/**
	 * 매물 등록 및 수정과 관련된 스크립트
	 */
	public function product_add($type="front"){

		header('Content-type: text/plain; charset=utf-8');
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$data["type"] = $type;

		$this->load->view('script/product_add', $data);
	}

	public function product_edit($type="front", $id){
		$this->load->model("Madminproduct");
		$data["query"] = $this->Madminproduct->get($id);

		header('Content-type: text/plain; charset=utf-8');
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		$data["type"] = $type;

		$this->load->view('script/product_edit', $data);
	}

}

/* End of file script.php */
/* Location: ./application/controllers/script.php */
