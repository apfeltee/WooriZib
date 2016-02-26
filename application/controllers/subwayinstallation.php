<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 지하철역
 */
class Subwayinstallation extends CI_Controller {

	public function __construct() {
		parent::__construct(); 
	}

	public function get_local(){
		$this->load->model("Msubwayinstallation");
		$query = $this->Msubwayinstallation->get_local();
		foreach($query as $val){
			if($val->local==1) $val->local_text = "수도권";
			if($val->local==2) $val->local_text = "부산";
			if($val->local==3) $val->local_text = "대구";
			if($val->local==4) $val->local_text = "광주";
			if($val->local==5) $val->local_text = "대전";
		}
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

	public function get_dong($type, $sido, $gugun){
		$this->load->model("Maddressinstallation");
		if($type!="admin"){
			$this->Maddressinstallation->set_type($type);
		}

		$query = $this->Maddressinstallation->get_dong(enconv($sido),enconv($gugun));
		echo json_encode($query);		
	}

	public function get_address($sido, $gugun, $dong){
		$this->load->model("Maddressinstallation");	

		$query = $this->Maddressinstallation->get_address(enconv($sido),enconv($gugun),enconv($dong));
		echo $query->id;
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

	public function get_hosun($local){
		$this->load->model("Msubwayinstallation");
		$query = $this->Msubwayinstallation->get_hosun($local);
		echo json_encode($query);
	}

	public function get_station($local,$hosun){
		$this->load->model("Msubwayinstallation");
		$query = $this->Msubwayinstallation->get_station($local,$hosun);
		echo json_encode($query);
	}

	public function get($id){
		$this->load->model("Msubwayinstallation");
		$query = $this->Msubwayinstallation->get($id);
		echo json_encode($query);
	}

}

/* End of file subway.php */
/* Location: ./application/controllers/subway.php */