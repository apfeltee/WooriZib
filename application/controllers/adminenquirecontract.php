<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminenquirecontract extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			exit;
		}
	}

	public function get_json($id){
		$this->load->model("Menquirecontract");
		$this->load->model("Mcontact");
		$query = $this->Menquirecontract->get($id);

		if($query->contacts_id){
			$contacts = $this->Mcontact->get($query->contacts_id);
			$query->contacts_name = $contacts->name;		
		}
		else{
			$query->contacts_name = "";
		}

		echo json_encode($query);
	}	

	public function get_list($id){
		$this->load->model("Menquirecontract");
		echo json_encode($this->Menquirecontract->get_list($id));
	}

	public function add_action(){

		$this->load->model("Menquirecontract");

		$originname = $filename = $file_size = "";

		if(!$_FILES["filename"]["error"]){
	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment/contract';
	 		$this->upload->initialize(array(
	            "upload_path"   => $folder,
	            "allowed_types" => 'gif|jpg|jpeg|png|doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
	            "encrypt_name"	=> TRUE
	        ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);
				chmod($folder,0777);
			}

			if ( ! $this->upload->do_upload("filename")){
				echo $this->upload->display_errors();
				return false;
			}
			else{
				$data = array('upload_data' => $this->upload->data());
				$originname = $data["upload_data"]["orig_name"];
				$filename = $data["upload_data"]["file_name"];
				$file_size = $data["upload_data"]["file_size"];
			}
	    }

		$param = Array(
			"enquire_id"	=> $this->input->post("enquire_id"),
			"status"		=> $this->input->post("status"),
			"title"			=> $this->input->post("title"),
			"contract_date"	=> $this->input->post("contract_date"),
			"type"			=> $this->input->post("type"),			
			"category"		=> ($this->input->post("category")) ? @implode(",",$this->input->post("category")) : "",
			"contract_price"=> $this->input->post("contract_price"),
			"part_price"	=> $this->input->post("part_price"), 
			"balance_price"	=> $this->input->post("balance_price"),
			"commission_price"	=> $this->input->post("commission_price"),
			"contract_pay_date"	=> $this->input->post("contract_pay_date"),
			"part_pay_date"		=> $this->input->post("part_pay_date"),
			"balance_pay_date"	=> $this->input->post("balance_pay_date"),
			"tax_type"		=> $this->input->post("tax_type"),
			"tax_use"		=> $this->input->post("tax_use"),
			"originname"	=> $originname,
			"filename"		=> $filename,
			"file_size"		=> $file_size,
			"contacts_id"	=> $this->input->post("contacts_id"),
			"date"			=> date('Y-m-d H:i:s')
		);

		$insert_id = $this->Menquirecontract->insert($param);

		// 히스토리추가
		$this->load->model("Menquirehistory");
		$this->Menquirehistory->insert($this->input->post("enquire_id"),$insert_id,"L","A",$this->input->post("title")); 

		echo $insert_id;
	}

	public function edit_action(){

		$this->load->model("Menquirecontract");

		$query = $this->Menquirecontract->get($this->input->post("contract_id"));

		$param = Array(
			"status"		=> $this->input->post("status"),
			"title"			=> $this->input->post("title"),
			"contract_date"	=> $this->input->post("contract_date"),
			"type"			=> $this->input->post("type"),			
			"category"		=> ($this->input->post("category")) ? @implode(",",$this->input->post("category")) : "",
			"contract_price"=> $this->input->post("contract_price"),
			"part_price"	=> $this->input->post("part_price"), 
			"balance_price"	=> $this->input->post("balance_price"),
			"commission_price"	=> $this->input->post("commission_price"),
			"contract_pay_date"	=> $this->input->post("contract_pay_date"),
			"part_pay_date"		=> $this->input->post("part_pay_date"),
			"balance_pay_date"	=> $this->input->post("balance_pay_date"),
			"tax_type"		=> $this->input->post("tax_type"),
			"tax_use"		=> $this->input->post("tax_use"),
			"contacts_id"	=> $this->input->post("contacts_id")
		);

		if(!$_FILES["filename"]["error"]){
	 		$this->load->library('upload');
	 		$folder = HOME.'/uploads/attachment/contract';
	 		$this->upload->initialize(array(
	            "upload_path"   => $folder,
	            "allowed_types" => 'gif|jpg|jpeg|png|doc|docx|hwp|ppt|pptx|pdf|zip|txt|jpg|png',
	            "encrypt_name"	=> TRUE
	        ));
			
			if(!file_exists($folder)){
				mkdir($folder,0777);
				chmod($folder,0777);
			}

			if ( ! $this->upload->do_upload("filename")){
				echo $this->upload->display_errors();
				return false;
			}
			else{
				$data = array('upload_data' => $this->upload->data());
				$param["originname"] = $data["upload_data"]["orig_name"];
				$param["filename"] = $data["upload_data"]["file_name"];
				$param["file_size"] = $data["upload_data"]["file_size"];
			}

			//기존파일은 삭제
			@unlink(HOME.'/uploads/attachment/contract/'.$query->filename);
	    }

		$result = $this->Menquirecontract->update($this->input->post("contract_id"),$param);

		// 히스토리추가
		$this->load->model("Menquirehistory");
		$this->Menquirehistory->insert($query->enquire_id,$this->input->post("contract_id"),"L","M",$this->input->post("title"));

		echo $result;
	}

	/**
	 * 계약 삭제하기
	 */
	public function delete_action($id){
		$this->load->model("Menquirecontract");

		$query = $this->Menquirecontract->get($id);

		@unlink(HOME.'/uploads/attachment/contract/'.$query->filename);

		$this->Menquirecontract->delete_contract($id);
		echo "1";
	}
}

/* End of file adminenquirecontract.php */
/* Location: ./application/controllers/adminenquirecontract.php */