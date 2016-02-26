<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Region extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function get_json($id){
		$this->load->model("Mregion");
		$data["query"] = $this->Mregion->get($id);
		echo json_encode($data["query"]);
	}

}

/* End of file region.php */
/* Location: ./application/controllers/region.php */