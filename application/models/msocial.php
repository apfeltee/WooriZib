<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Msocial Model Class
 *
 */
class Msocial extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get(){
		$this->db->from("social");
		$query = $this->db->get();
		$this->db->limit(1);
		return $query->row();
	}

	function get_count(){
		$result = $this->db->count_all_results("social");
		return $result;
	}

	function insert($param){
		$this->db->insert("social",$param);
		return $this->db->insert_id();
	}

	function update($param){
		$this->db->update("social",$param);
	}
}

/* End of file Msocial.php */
/* Location: ./application/models/Msocial.php */