<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mintro extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_list($flag=false){
		$this->db->from("intro");
		if($flag){
			$this->db->where("flag","Y");
		}
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_max_sorting(){
		$this->db->select_max("sorting");
		$result = $this->db->get("intro");
		return $result->row()->sorting;
	}

	function insert($param){
		$this->db->insert("intro",$param);
	}

	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("intro",$param);
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("intro");
		return $query->row();
	}

	function delete($id){
		$this->db->where("id",$id);
		$this->db->delete("intro");
	}
}

/* End of file mintro.php */
/* Location: ./application/models/mintro.php */