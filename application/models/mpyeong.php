<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpyeong extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$result = $this->db->get("pyeong");
		return $result->row();	
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pyeong",$param);
	}

	function get_main_pyeong($id){
		$this->db->where("installation_id",$id);
		$this->db->order_by("sorting","asc");
		$this->db->limit(1);
		$result = $this->db->get("pyeong");
		return $result->row();	
	}

	function get_sorting($id){
		$this->db->select_max('sorting');
		$this->db->where("installation_id",$id);
		$result = $this->db->get("pyeong");
		return $result->row()->sorting;
	}

	function get_list($id,$type="obj"){
		$this->db->where("installation_id",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("pyeong");
		if($type=="obj"){
			return $result->result();
		} else {
			return $result->result_array();
		}
	}

	function change_sorting($pyeong_id,$sorting){
		$this->db->where("id",$pyeong_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("pyeong");
	}

	function insert($param){
		$this->db->insert("pyeong",$param);
	}

	function delete($pid, $gid){
		$this->db->where("installation_id",$pid);
		$this->db->where("id",$gid);
		$this->db->delete("pyeong");
	}

	function delete_pyeong($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("pyeong");	 
	}

	function sorting_refresh($installation_id){
		$this->db->set("sorting","sorting-1",false);
		$this->db->where("installation_id",$installation_id);
		$this->db->update("pyeong");
	}
}

/* End of file mpyeong.php */
/* Location: ./application/models/mpyeong.php */