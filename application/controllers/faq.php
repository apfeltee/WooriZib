<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller {

	function index(){

		$this->load->Model("Mfaq");

		$data["query"] = $this->Mfaq->get_list();

		$data["page_title"] =  "자주 묻는 질문";

		$this->layout->view('basic/faq_index',$data);
	}
}

/* End of file faq.php */
/* Location: ./application/controllers/faq.php */
