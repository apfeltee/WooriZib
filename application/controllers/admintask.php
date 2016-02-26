<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admintask extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			exit;
		}
	}
	
	public function get_json($id){
		$this->load->model("Mtask");
		$query = $this->Mtask->get($id);
		echo json_encode($query);
	}

	public function get_list($id){
		$this->load->model("Mtask");
		echo json_encode($this->Mtask->get_list($id));
	}

	public function add_action(){
		$param = Array(
				"contacts_id"	=> $this->input->post("contacts_id"),
				"title"			=> $this->input->post("title"),
				"important"		=> $this->input->post("important"),				
				"content"		=> $this->input->post("content_add"),
				"member_id"		=> $this->session->userdata("admin_id"),
				"deaddate"		=> $this->input->post("deaddate"),
				"regdate"		=> date('Y-m-d H:i:s')
				);
		
		$this->load->model("Mtask");
		$insert_id = $this->Mtask->insert($param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$this->Mhistory->insert($this->input->post("contacts_id"),$insert_id,"T","S",$this->input->post("title")); 

		echo "1";
	}

	public function edit_action(){
		$param = Array(
				"title"			=> $this->input->post("title"),
				"important"		=> $this->input->post("important"),								
				"content"		=> $this->input->post("content_edit"),
				"member_id"		=> $this->session->userdata("admin_id"),
				"deaddate"		=> $this->input->post("deaddate"),
				);

		$this->load->model("Mtask");
		$this->Mtask->update($this->input->post("task_id"), $param);

		//**********************************************
		// 히스토리추가
		//**********************************************
		$this->load->model("Mhistory");
		$task = $this->Mtask->get($this->input->post("task_id"));

		$this->Mhistory->insert($task->contacts_id,$this->input->post("task_id"),"T","M",$this->input->post("title")); 

		echo "1";
	}

	public function finish_action($id,$status){
		$this->load->model("Mtask");
		$task = $this->Mtask->get($id);
		if($task->member_id==$this->session->userdata("admin_id")){
			if($status=="Y"){
				$param = Array("finished"=>"Y","enddate"=>date('Y-m-d H:i:s'));	
			} else {
				$param = Array("finished"=>"N","enddate"=>"");

				// 히스토리추가
				$this->load->model("Mhistory");
				$this->Mhistory->insert($task->contacts_id,$task->id,"T","E",$task->title);

			}
			
			$this->Mtask->update($id,$param);
			echo "1";
		} else {
			echo "0";
		}
	}

	public function delete_task($id){
		$this->load->model("Mtask");
		$task = $this->Mtask->get($id);
		if($task->member_id==$this->session->userdata("admin_id")){
			$this->Mtask->delete_task($id);
			echo "1";
		} else {
			echo "0";
		}
	}

	/**
	 * 에디터에서 이미지를 업로드할 때 실행된다.
	 */
	public function upload_action(){
		$config['upload_path'] = HOME.'/uploads/contents/';
		$config['allowed_types'] = 'gif|jpg|jpeg|png';
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		$filename = "";
		if ( ! $this->upload->do_upload("uploadfile"))
		{
			echo $this->upload->display_errors();
			return false;
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());
			echo "/uploads/contents/". $data["upload_data"]["file_name"];
		}
	}	

}

/* End of file Admintask.php */
/* Location: ./application/controllers/Admintask.php */