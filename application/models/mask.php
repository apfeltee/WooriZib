<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mask Model Class
 *
 * @author	Dejung Kang
 */
class Mask extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("ask",$param);
	}

	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("ask",$param);
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("ask");
		return $query->row();
	}

	function get_total_count(){
		$query = $this->db->get("ask");
		return $query->num_rows(); 
	}

	function get_list($num, $offset){
		$this->db->order_by("date","desc");
		$query = $this->db->get("ask",$num, $offset);
		return $query->result();
	}

	function delete_ask($id){
		$this->db->where("id",$id);
		$this->db->delete("ask");
	}
}

/* End of file mask.php */
/* Location: ./application/models/mask.php */