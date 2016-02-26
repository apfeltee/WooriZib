<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mfaq Model Class
 *
 */
class Mfaq extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("faq",$param);
		return $this->db->insert_id();
	}

	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("faq",$param);
	}
	
	function get_max_sorting(){
		$this->db->select_max('sorting');
		$query = $this->db->get("faq");
		return $query->row();
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("faq");
		return $query->row();
	}

	function get_list(){
		$this->db->order_by("sorting","asc");
		$query = $this->db->get("faq");
		return $query->result();
	}

	function delete_faq($id){
		$this->db->where("id",$id);
		$this->db->delete("faq");
	}
}

/* End of file mfaq.php */
/* Location: ./application/models/mfaq.php */