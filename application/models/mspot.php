<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mspot Model Class
 *
 * @author	Dejung Kang
 */
class Mspot extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("spot");
		$query = $this->db->get();
		return $query->row();
	}

	function insert($param){
		$this->db->insert("spot",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("spot",$param);
	}

	function get_list(){
		$this->db->from("spot");
		$result = $this->db->get();
		return $result->result();
	}

	function delete_spot($id){
		$this->db->where("id",$id);
		$this->db->delete("spot");
	}
}

/* End of file mspot.php */
/* Location: ./application/models/mspot.php */