<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 홈페이지 홈 설정
 *
 * @author	SJ
 */
class Madminfront extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("home_layout");
		$query = $this->db->get();
		return $query->row();
	}

	function get_module($module){
		$this->db->where("module",$module);
		$this->db->from("home_layout");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("home_layout",$param);
	}

	function get_list(){
		$this->db->from("home_layout");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function slide_get_sorting($id){
		$this->db->select_max('sorting');
		$result = $this->db->get("slide");
		return $result->row()->sorting;
	}


	function slide_insert($param){
		$this->db->insert("slide",$param);
	}

	function slide_get_list(){
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("slide");
		return $result->result_array();
	}

	function slide_get_list_json(){
		$this->db->select("filename,link");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("slide");
		return json_encode($result->result());
	}

	function slide_change_sorting($slide_id,$sorting){
		$this->db->where("id",$slide_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("slide");
	}

	function slide_get($slide_id){
		$this->db->where("id",$slide_id);
		$result = $this->db->get("slide");
		return $result->row();	
	}

	function slide_delete($slide_id){
		$this->db->where("id",$slide_id);
		$this->db->delete("slide");
	}

	function landing_get_sorting($id){
		$this->db->select_max('sorting');
		$result = $this->db->get("landing");
		return $result->row()->sorting;
	}

	function landing_insert($param){
		$this->db->insert("landing",$param);
	}

	function landing_get_list(){
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("landing");
		return $result->result_array();
	}

	function landing_get_list_json(){
		$this->db->select("filename");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("landing");
		return json_encode($result->result());
	}

	function landing_change_sorting($landing_id,$sorting){
		$this->db->where("id",$landing_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("landing");
	}

	function landing_get($landing_id){
		$this->db->where("id",$landing_id);
		$result = $this->db->get("landing");
		return $result->row();	
	}

	function landing_random_get(){
		$this->db->order_by("rand()");
		$this->db->limit(1);
		$result = $this->db->get("landing");
		return $result->row();
	}

	function landing_delete($landing_id){
		$this->db->where("id",$landing_id);
		$this->db->delete("landing");
	}

	function link_update($param,$id,$type){
		$this->db->where("id",$id);
		$this->db->update($type,$param);
	}
}

/* End of file madminfront.php */
/* Location: ./application/models/madminfront.php */