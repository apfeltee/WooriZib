<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 지하철역
 */
class Subway extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	public function get_local(){
		$this->load->model("Msubway");
		$query = $this->Msubway->get_local();
		foreach($query as $val){
			if($val->local==1) $val->local_text = toeng("수도권");
			if($val->local==2) $val->local_text = toeng("부산");
			if($val->local==3) $val->local_text = toeng("대구");
			if($val->local==4) $val->local_text = toeng("광주");
			if($val->local==5) $val->local_text = toeng("대전");
		}
		echo json_encode($query);
	}

	public function subway(){
		$this->load->Model("Maddress");
		$query = $this->Maddress->get_subway($this->input->post("subway"));
		echo json_encode($query);
	}

	public function get_hosun($local){
		$this->load->model("Msubway");
		$query = $this->Msubway->get_hosun($local);
		if($this->config->item("language")!="korean"){
			foreach($query as $key=>$val){
				$query[$key]->hosun_label = han2eng($val->hosun);
			}
		}
		echo json_encode($query);
	}

	public function get_station($hosun){
		$this->load->model("Msubway");
		$query = $this->Msubway->get_station($hosun);
		if($this->config->item("language")!="korean"){
			foreach($query as $key=>$val){
				$query[$key]->name_label = han2eng($val->name);
			}
		}
		echo json_encode($query);
	}

	public function get($id){
		$this->load->model("Msubway");
		$query = $this->Msubway->get($id);
		echo json_encode($query);
	}

}

/* End of file subway.php */
/* Location: ./application/controllers/subway.php */