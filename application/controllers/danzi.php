<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Danzi extends CI_Controller {

	
	public function __construct() {
		parent::__construct(); 
	}

	public function get_json($id){
		$this->load->model("Mdanzi");
		$query = $this->Mdanzi->get($id);
		echo json_encode($query);		
	}	

	public function get_area(){
		$this->load->model("Mdanzi");
		$query = $this->Mdanzi->get_area($this->input->post("address_id"),$this->input->post("danzi_name"));
		echo json_encode($query);		
	}

	public function get_danzi_area(){
		$this->load->model("Mdanzi");
		$query = $this->Mdanzi->get_danzi_area($this->input->post("address_id"),$this->input->post("danzi_name"));
		echo json_encode($query);		
	}

	public function get_danzi_name($address_id=""){
		$this->load->model("Mdanzi");
		$query = $this->Mdanzi->get_danzi($address_id,true);
		echo json_encode($query);		
	}

	public function get_danzi_coords($danzi_id=""){
		$this->load->model("Mdanzi");
		$query = $this->Mdanzi->get($danzi_id);
		echo json_encode($query);		
	}
}

/* End of file danzi.php */
/* Location: ./application/controllers/danzi.php */