<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mnotice Model Class
 *
 * @author	Dejung Kang
 */
class Mnotice extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 공지사항 글 추가
	 */
	function insert($param){
		$this->db->insert("notices",$param);
		return $this->db->insert_id();
	}

	/**
	 * 공지사항 글 수정
	 */
	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("notices",$param);
	}

	/**
	 * 조회수 증가
	 */
	function view($id){
		$this->db->where("id",$id);
		$this->db->set("viewcnt","viewcnt+1",false);
		$this->db->update("notices");
	}

	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("notices");
		return $query->row();
	}

	function get_total_count(){
		$query = $this->db->get("notices");
		return $query->num_rows(); 
	}

	function get_list($num, $offset){
		$this->db->order_by("date","desc");
		$query = $this->db->get("notices",$num, $offset);
		return $query->result();
	}

	/*** main ***/
	function get_recent(){
		$this->db->limit(10);
		$this->db->order_by("date","desc");
		$query = $this->db->get("notices");
		return $query->result();	
	}

	/**
	 * 최신 글목록 가져오기
	 */
	function get_last($cnt=5){
		$this->db->order_by("date","desc");
		$this->db->limit($cnt);
		$query = $this->db->get("notices");
		return $query->result();
	}

	function delete_notice($id){
		$this->db->where("id",$id);
		$this->db->delete("notices");
	}

	function change($param, $id){
		$this->db->where("id", $id);
		$this->db->update("notices", $param);
	}

}

/* End of file mnotice.php */
/* Location: ./application/models/mnotice.php */