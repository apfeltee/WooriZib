<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminattachment extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	/**
	 * 해당 기사에서 첨부된 파일 목록을 가져온다.
	 */
	function get_json($product_id){
		$this->load->model("Mattachment");
		$result = $this->Mattachment->get_list($product_id);
		echo json_encode($result);
	}

	/**
	 * 첨부파일 삭제 (한개)
	 */
	function remove($product_id, $id){
		$this->load->model("Mattachment");
		$attachment = $this->Mattachment->get($product_id, $id); //id 값만 있으면 가져올 수 있긴한데 보안을 위하여 $news_id 값도 넘긴다.

		unlink(HOME.'/uploads/attachment/'.$attachment->product_id.'/'.$attachment->filename);	

		$this->Mattachment->remove($product_id, $id);
		echo "1";
	}

	/**
	 * 분양 정보에서 첨부된 파일 가져오기
	 */
	function get_json_installation($installation_id){
		$this->load->model("Mattachmentinstallation");
		$result = $this->Mattachmentinstallation->get_list($installation_id);
		echo json_encode($result);
	}

	/**
	 * 분양 정보 첨부파일 삭제
	 */
	function remove_installation($installation_id, $id){
		$this->load->model("Mattachmentinstallation");
		$attachment = $this->Mattachmentinstallation->get($installation_id, $id);

		unlink(HOME.'/uploads/attachment_installation/'.$attachment->installation_id.'/'.$attachment->filename);	

		$this->Mattachmentinstallation->remove($installation_id, $id);
		echo "1";
	}
}

/* End of file adminattachment.php */
/* Location: ./application/controllers/adminattachment.php */