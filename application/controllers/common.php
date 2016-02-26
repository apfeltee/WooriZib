<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends CI_Controller {

	/**
	 * 자동 로그아웃 기능
	 * 홈페이지에서 회원이 로그인 한 후 $config->AUTO_LOGOUT값의 분이 경과되면 자동으로 로그아웃 처리하기 위한 기능
	 *  AUTO_LOGOUT값이 0이면 기능을 사용하지 않는다.
	 */
	function autologout()
	{
		$this->load->model("Mconfig");
		$data["config"] = $this->Mconfig->get();
		if($data["config"]->AUTO_LOGOUT){
			$this->load->view("admin/template/autologout",$data);
		}
	}
}

/* End of file common.php */
/* Location: ./application/controllers/common.php */
