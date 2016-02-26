<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My404 extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	public function index(){
		$this->layout->view('basic/error_404');	
	}
}

/* End of file my404.php */
/* Location: ./application/controllers/my404.php */