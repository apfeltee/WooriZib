<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Addressinstallation extends CI_Controller {

	
	public function __construct() {
		parent::__construct(); 
	}
	
	public function get_sido($type){
		$this->load->model("Maddressinstallation");
		if($type!="admin"){
			$this->Maddressinstallation->set_type($type);
		}
		$query = $this->Maddressinstallation->get_sido();
		echo json_encode($query);
	}

	public function get_gugun($type,$sido){
		$this->load->model("Maddressinstallation");
		if($type!="admin"){
			$this->Maddressinstallation->set_type($type);
		} 
		$query = $this->Maddressinstallation->get_gugun(enconv($sido));
		echo json_encode($query);		
	}

	public function get_dong($type, $parent_id){
		$this->load->model("Maddressinstallation");
		if($type!="admin"){
			$this->Maddressinstallation->set_type($type);
		}

		$query = $this->Maddressinstallation->get_dong($parent_id);
		echo json_encode($query);		
	}

	public function get($id){
		$this->load->model("Maddressinstallation");	
		$query = $this->Maddressinstallation->get($id);
		echo json_encode($query);
	}

	public function get_address($sido, $gugun, $dong){
		$this->load->model("Maddressinstallation");	

		$query = $this->Maddressinstallation->get_address(enconv($sido),enconv($gugun),enconv($dong));
		echo json_encode($query);
	}

	public function subway(){
		$this->load->Model("Maddressinstallation");
		$query = $this->Maddressinstallation->get_subway($this->input->post("subway"));
		echo json_encode($query);
	}

	public function get_bound($type, $sido, $gugun="", $dong=""){
		$this->load->Model("Maddressinstallation");
		if($type=="dong"){
			$param = Array(
				"sido" => urldecode($sido),
				"gugun" => urldecode($gugun),
				"dong" => urldecode($dong)
			);
		} else if($type=="gugun"){
			$param = Array(
				"sido" => urldecode($sido),
				"gugun" => urldecode($gugun)
			);
		} else if($type=="sido"){
			$param = Array(
				"sido" => urldecode($sido)
			);
		}

		echo json_encode($this->Maddressinstallation->get_bound($param));
	}

	/**
	 * 우편번호 좌표 업데이트
	 */
	public function address(){
		$this->load->model("Marea");
		$data["query"] = $this->Marea->get_no();
		$this->layout->admin('address_index',$data);
	}
	
	public function address_action(){
		$this->load->model("Marea");

		$param = Array(
			"lat"=>$this->input->post("lat"),
			"lng"=>$this->input->post("lng")
		);
		
		$this->Marea->update_address($param,$this->input->post("id"));
		redirect("adminarea/address","refresh");
	}

	/** 단지 정보 좌표 갱신 **/
	public function danzi(){
		$this->load->model("Mdanzi");
		$data["query"] = $this->Mdanzi->get_none();
		$this->layout->admin('danzi_latlng',$data);
	}

	public function danzi_action(){
		$this->load->model("Mdanzi");

		$param = Array(
			"lat"=>$this->input->post("lat"),
			"lng"=>$this->input->post("lng")
		);
		
		$this->Mdanzi->update($this->input->post("id"),$param);
		redirect("address/danzi","refresh");
	}	
}

/* End of file addressinstallation.php */
/* Location: ./application/controllers/addressinstallation.php */