<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mservice Model Class
 *
 * 메인 페이지 레이아웃 중 서비스 소개 박스
 *
 * @author	Dejung Kang
 */
class Mservice extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("service");
		$query = $this->db->get();
		return $query->row();
	}

	function get_max_sorting(){
		$this->db->select_max("sorting");
		$result = $this->db->get("service");
		return $result->row()->sorting;
	}

	function insert($param){
		$this->db->insert("service",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("service",$param);
	}

	function get_list(){
		$this->db->from("service");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_valid(){
		$this->db->from("service");
		$this->db->order_by("sorting","asc");
		$this->db->where("flag","Y");
		$result = $this->db->get();
		return $result->result();
	}

	function delete_area($id){
		$this->db->where("id",$id);
		$this->db->delete("service");
	}
}

/* End of file mservice.php */
/* Location: ./application/models/mservice.php */