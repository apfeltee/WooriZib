<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mloan Model Class
 *
 * @author	Dejung Kang
 */
class Mloan extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("loan");
		$query = $this->db->get();
		return $query->row();
	}

	function get_max_sorting(){
		$this->db->select_max("sorting");
		$result = $this->db->get("loan");
		return $result->row()->sorting;
	}

	function insert($param){
		$this->db->insert("loan",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("loan",$param);
	}

	function get_list(){
		$this->db->from("loan");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_in($loan_ids){
		$this->db->from("loan");
		$this->db->where_in("id",$loan_ids);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function delete_loan($id){
		$this->db->where("id",$id);
		$this->db->delete("loan");
	}
}

/* End of file mloan.php */
/* Location: ./application/models/mloan.php */