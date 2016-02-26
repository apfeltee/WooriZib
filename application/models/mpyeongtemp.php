<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpyeongtemp extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$result = $this->db->get("pyeong_temp");
		return $result->row();	
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pyeong_temp",$param);
	}

	function get_sorting($id){
		$this->db->select_max('sorting');
		$this->db->where("member_id",$id);
		$result = $this->db->get("pyeong_temp");
		return $result->row()->sorting;
	}

	function get_list($member_id){
		$this->db->where("member_id",$member_id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("pyeong_temp");
		return $result->result_array();
	}

	function change_sorting($pyeong_id,$sorting){
		$this->db->where("id",$pyeong_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("pyeong_temp");
	}

	function insert($param){
		$this->db->insert("pyeong_temp",$param);
	}

	function delete($id){
		$this->db->where("id",$id);
		$this->db->delete("pyeong_temp");
	}

	function delete_installation($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("pyeong_temp");	 
	}
}

/* End of file mpyeongtemp.php */
/* Location: ./application/models/mpyeongtemp.php */