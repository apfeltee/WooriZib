<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Style extends CI_Controller {

	function index($param=""){

		if(!$param){			
			$data["skin_color"] = $this->config->item('skin_color');
		}
		else{
			$data["skin_color"] = $param;
		}		

		header('Content-type: text/css');
		$this->layout->setLayout("list");
		$this->layout->view("admin/template/style",$data);
	}

	function mobile($param=""){

		if(!$param){			
			$data["skin_color"] = $this->config->item('skin_color');
		}
		else{
			$data["skin_color"] = $param;
		}		

		header('Content-type: text/css');
		$this->layout->setLayout("list");
		$this->layout->view("mobile/style",$data);
	}	
}

/* End of file style.php */
/* Location: ./application/controllers/style.php */
