<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hope extends CI_Controller {

	/**
	 * 이 함수를 수정하면 기존에 올린 글들의 로고가 안 보이기 때문에 별도의 함수를 만들어서 신규 로그 측정기능을 구현해야 한다.
	 * 이 함수에서 로그축적하던 코드는 삭제하였다.
	 */
	public function remove()
	{
		$this->load->model("Mhope");
		
		foreach($this->input->post("id") as $val){

			if($this->session->userdata("id")!=""){
				//로그인시
				$this->Mhope->remove_by_member($val, $this->session->userdata("id"));
			} else {
				//비로그인시
				$this->Mhope->remove_by_session($val, $this->session->userdata("session_id"));
			}
		}

		redirect("member/hope","refresh");
	}
}

/* End of file hope.php */
/* Location: ./application/controllers/hope.php */
