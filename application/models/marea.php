<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Madminarea Model Class
 *
 * @author	Dejung Kang
 */
class Marea extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("area");
		return $query->row();
	}

	function insert($param){
		$this->db->insert("area",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("area",$param);
	}

	function get_list(){
		$this->db->from("area");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 * 자신 이외의 목록을 가져온다. (삭제 시 변경을 위해서)
	 */
	function get_others($id){
		$this->db->from("area");
		$this->db->where("id <> ",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();	
	}

	function change_area_products($delete_id,$change_id){
		$this->db->set("area_id",$change_id);
		$this->db->where("area_id",$delete_id);
		$this->db->update("products");
	}

	function delete_area($id){
		$this->db->where("id",$id);
		$this->db->delete("area");
	}
}

/* End of file marea.php */
/* Location: ./application/models/marea.php */