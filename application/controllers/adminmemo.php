<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminmemo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			exit;
		}
	}
	
	/**
	 * 해당 회원의 메모 리스트를 가져온다.
	 */
	function get_list($id){
		$this->load->model("Mmemo");
		echo json_encode($this->Mmemo->get_list($id));
	}

	function get_action_list($id){
		$this->load->model("Mmemo");
		$result = $this->Mmemo->get_action_list($id);
		
		foreach($result as $key=>$val){
			$date1 = strtotime($val["actiondate"]);
			$datediff =  time() - $date1;

			if($datediff>0){
				$result[$key]["dday"] = "<span class=\"badge badge-danger\">D".floor($datediff/(60*60*24))."</span>";
			} else {
				$result[$key]["dday"] = "<span class=\"badge badge-success\">D".floor($datediff/(60*60*24))."</span>";
			}
			
		}
		echo json_encode($result);
	}

	function add_action(){
		$param = Array(
				"contacts_id"	=> $this->input->post("contacts_id"),
				"content"		=> $this->input->post("content"),
				"member_id"		=> $this->session->userdata("admin_id"),
				"regdate"		=> date('Y-m-d H:i:s')
				);
		
		$this->load->model("Mmemo");
		$insert_id = $this->Mmemo->insert($param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$this->Mhistory->insert($this->input->post("contacts_id"),$insert_id,"M","A",$this->input->post("content")); 
	}


	function add_action_action(){
		$param = Array(
				"contacts_id"	=> $this->input->post("contacts_id"),
				"type"			=> $this->input->post("type"),
				"content"		=> $this->input->post("content"),
				"member_id"		=> $this->session->userdata("admin_id"),
				"actiondate"	=> $this->input->post("actiondate")." ".$this->input->post("actiontime"),
				"regdate"		=> date('Y-m-d H:i:s')
				);
		
		$this->load->model("Mmemo");
		$insert_id = $this->Mmemo->insert_action($param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$this->Mhistory->insert($this->input->post("contacts_id"),$insert_id,"A","A",$this->input->post("content")); 
	}

	/**
	 * delete memo
	 *
	 * Writer have permission to delete a memo.
	 */
	function delete_memo($id){
		$this->load->model("Mmemo");
		$memo = $this->Mmemo->get($id);
		if($memo->member_id==$this->session->userdata("admin_id")){
			$this->Mmemo->delete_memo($id);
			$this->load->model("Mhistory");
			$this->Mhistory->delete_item("M",$id);
			echo "1";
		} else {
			echo "0";
		}
	}

	/**
	 * 접촉 삭제하기 (히스토리도 삭제한다.)
	 */
	function delete_action($id){
		$this->load->model("Mmemo");
		$memo = $this->Mmemo->get_action($id);
		if($memo->member_id==$this->session->userdata("admin_id")){

			$this->Mmemo->delete_action($id);
			$this->load->model("Mhistory");
			$this->Mhistory->delete_item("A",$id);
			echo "1";
		} else {
			echo "0";
		}		
	}
}

/* End of file Admincontact.php */
/* Location: ./application/controllers/Admincontact.php */