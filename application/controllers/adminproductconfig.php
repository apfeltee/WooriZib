<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Adminproductconfig extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if($this->session->userdata("admin_id")==""){
			redirect("adminlogin/index","refresh");
		}
	}

	function index(){
		$this->load->model("Mproductconfig");
		$data["query"] = $this->Mproductconfig->get_list();
		$this->layout->admin('productconfig_index', $data);
	}

	function get_json($id){
		$this->load->model("Mproductconfig");
		$query = $this->Mproductconfig->get($id);
		echo json_encode($query);
	}

	function edit_action(){
		$this->load->model("Mproductconfig");

		$param = Array(
			"default_type" => $this->input->post("default_type"),
			"default_part" => $this->input->post("default_part"),
			"danzi" => $this->input->post("danzi"),
			"lease_price" => $this->input->post("lease_price"),
			"premium_price" => $this->input->post("premium_price"),
			"mgr_price" => $this->input->post("mgr_price"),
			"mgr_price_full_rent" => $this->input->post("mgr_price_full_rent"),
			"monthly_rent_deposit_min" => $this->input->post("monthly_rent_deposit_min"),
			"loan" => $this->input->post("loan"),
			"dongho" => $this->input->post("dongho"),
			"real_area" => $this->input->post("real_area"),
			"law_area" => $this->input->post("law_area"),
			"land_area" => $this->input->post("land_area"),
			"road_area" => $this->input->post("road_area"),
			"enter_year" => $this->input->post("enter_year"),
			"build_year" => $this->input->post("build_year"),
			"bedcnt" => $this->input->post("bedcnt"),
			"bathcnt" => $this->input->post("bathcnt"),
			"current_floor" => $this->input->post("current_floor"),
			"total_floor" => $this->input->post("total_floor"),
			"store_category" => $this->input->post("store_category"),
			"store_name" => $this->input->post("store_name"),
			"profit" => $this->input->post("profit"),
			"gongsil_see" => $this->input->post("gongsil_see"),
			"gongsil_status" => $this->input->post("gongsil_status"),
			"gongsil_contact" => $this->input->post("gongsil_contact"),
			"extension" => $this->input->post("extension"),
			"heating" => $this->input->post("heating"),
			"park" => $this->input->post("park"),
			"road_condition" => $this->input->post("road_condition"),
			"ground" => $this->input->post("ground"),
			"factory" => $this->input->post("factory"),
			"vr" => $this->input->post("vr"),
			"video_url" => $this->input->post("video_url")
		);

		$this->Mproductconfig->update($this->input->post("id"),$param);
		
		redirect("adminproductconfig/index","refresh");
	}
}

/* End of file Adminproductconfig.php */
/* Location: ./application/controllers/Adminproductconfig.php */