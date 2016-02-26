<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mcontactgroup Model Class
 *
 * @author	Dejung Kang
 */
class Mcontactgroup extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("contacts_group");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 로그인 체크할 때 사용한다.
	 */
	function get_array($id){
		$this->db->where("id",$id);
		$this->db->from("contacts_group");
		$query = $this->db->get();
		return $query->row_array();
	}

	function insert($param){
		$this->db->insert("contacts_group",$param);
		return $this->db->insert_id();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("contacts_group",$param);
	}


	function get_list(){
		$this->db->select("contacts_group.*");
		$this->db->select(" (select count(*) from contacts where group_id=contacts_group.id) as cnt");
		$this->db->order_by("id","asc");
		$query = $this->db->get("contacts_group");
		return $query->result();
	}

	function get_all_cnt(){
		$this->db->from("contacts");
		return $this->db->count_all_results();	
	}

	function get_no_cnt(){
		$this->db->where("group_id","0");
		$this->db->from("contacts");
		return $this->db->count_all_results();	
	}

	/**
	 * 회원 정보 삭제
	 */
	function delete_group($id){
		$this->db->where("id",$id);
		$this->db->delete("contacts_group");
	}
}

/* End of file mcontactgroup.php */
/* Location: ./application/models/mcontactgroup.php */