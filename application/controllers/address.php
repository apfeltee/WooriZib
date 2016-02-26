<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address extends CI_Controller {

	
	public function __construct() {
		parent::__construct(); 
	}
	
	public function get_sido($type){
		$this->load->model("Maddress");
		if($type!="admin"){
			$this->Maddress->set_type($type);
		}
		$query = $this->Maddress->get_sido();

		if($this->config->item("language")!="korean"){
			foreach($query as $key=>$val){
				$query[$key]->sido_label = han2eng($val->sido);
			}
		}
		echo json_encode($query);
	}

	public function get_gugun($type,$sido){
		$this->load->model("Maddress");
		if($type!="admin"){
			$this->Maddress->set_type($type);
		} 
		$query = $this->Maddress->get_gugun(enconv($sido));

		if($this->config->item("language")!="korean"){
			foreach($query as $key=>$val){
				$query[$key]->gugun_label = han2eng($val->gugun);
			}
		}
		echo json_encode($query);		
	}

	public function get_dong($type, $parent_id){
		$this->load->model("Maddress");
		if($type!="admin"){
			$this->Maddress->set_type($type);
		}

		$query = $this->Maddress->get_dong($parent_id);

		if($this->config->item("language")!="korean"){
			foreach($query as $key=>$val){
				$query[$key]->dong_label = han2eng($val->dong);
			}
		}
		echo json_encode($query);		
	}

	public function get($id){
		$this->load->model("Maddress");	
		$query = $this->Maddress->get($id);
		echo json_encode($query);
	}

	public function get_parent($id){
		$this->load->model("Maddress");	
		$query = $this->Maddress->get_parent($id);
		echo json_encode($query);
	}

	public function get_address($sido, $gugun, $dong){
		$this->load->model("Maddress");
		$sido = urldecode($sido);
		$gugun = urldecode($gugun);
		$dong = urldecode($dong);
		$query = $this->Maddress->get_address($sido,$gugun,$dong);
		echo json_encode($query);
	}

	public function subway(){
		$this->load->Model("Maddress");
		$query = $this->Maddress->get_subway($this->input->post("subway"));
		echo json_encode($query);
	}

	public function get_bound($type, $sido, $gugun="", $dong=""){
		$this->load->Model("Maddress");
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

		echo json_encode($this->Maddress->get_bound($param));
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
	
	public function get_sido_building(){
		$this->load->model("Maddress");
		$query = $this->Maddress->get_sido_building();
		echo json_encode($query);
	}

	public function get_gugun_building($sido){
		$this->load->model("Maddress");
		$query = $this->Maddress->get_gugun_building(enconv($sido));
		echo json_encode($query);		
	}

	public function get_dong_building($parent_id){
		$this->load->model("Maddress");
		$query = $this->Maddress->get_dong_building($parent_id);
		echo json_encode($query);		
	}
}

/* End of file address.php */
/* Location: ./application/controllers/adminarea.php */