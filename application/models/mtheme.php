<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mtheme Model Class
 *
 * @author	Dejung Kang
 */
class Mtheme extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("theme");
		$query = $this->db->get();
		return $query->row();
	}

	function get_max_sorting(){
		$this->db->select_max("sorting");
		$result = $this->db->get("theme");
		return $result->row()->sorting;
	}

	function insert($param){
		$this->db->insert("theme",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("theme",$param);
	}

	function get_list(){
		$this->db->from("theme");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_in($theme_ids){
		$this->db->from("theme");
		$this->db->where_in("id",$theme_ids);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 * 자신 이외의 목록을 가져온다. (삭제 시 변경을 위해서)
	 */
	function get_others($id){
		$this->db->from("theme");
		$this->db->where("id <> ",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();	
	}

	function change_area_products($delete_id,$change_id){
		$this->db->set("theme",$change_id);
		$this->db->where("theme",$delete_id);
		$this->db->update("products");
	}

	function delete_area($id){
		$this->db->where("id",$id);
		$this->db->delete("theme");
	}
}

/* End of file mtheme.php */
/* Location: ./application/models/mtheme.php */