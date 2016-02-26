<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mmemo Model Class
 *
 * @author	Dejung Kang
 */
class Mmemo extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("contacts_memo");
		$query = $this->db->get();
		return $query->row();
	}

	function get_action($id){
		$this->db->where("id",$id);
		$this->db->from("contacts_action");
		$query = $this->db->get();
		return $query->row();		
	}

	function insert($param){
		$this->db->insert("contacts_memo",$param);
		return $this->db->insert_id();
	}

	function insert_action($param){
		$this->db->insert("contacts_action",$param);
		return $this->db->insert_id();
	}

	function get_list($id){
		$this->db->select("contacts_memo.*,members.name,members.profile");
		$this->db->where("contacts_memo.contacts_id",$id);
		$this->db->join("members","members.id=contacts_memo.member_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("contacts_memo");
		return $query->result();
	}

	function get_action_list($id){
		$this->db->select("contacts_action.*,members.name,members.profile");
		$this->db->where("contacts_action.contacts_id",$id);
		$this->db->join("members","members.id=contacts_action.member_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("contacts_action");
		return $query->result_array();	
	}

	function get_month_action($start,$end){
		$this->db->where("actiondate <",$end);
		$this->db->where("actiondate >=",$start);
		$query = $this->db->get("contacts_action");
		return $query->result();			
	}

	function get_month_memo($start,$end){
		$this->db->where("regdate <",$end);
		$this->db->where("regdate >=",$start);
		$query = $this->db->get("contacts_memo");
		return $query->result();		
	}

	/**
	 * delete memo
	 */
	function delete_memo($id){
		$this->db->where("id",$id);
		$this->db->delete("contacts_memo");
	}

	function delete_action($id){
		$this->db->where("id",$id);
		$this->db->delete("contacts_action");
	}

	function delete_memo_contacts_id($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$this->db->delete("contacts_memo");
	}

	function delete_action_contacts_id($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$this->db->delete("contacts_action");
	}

	function memo_count($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$query = $this->db->get("contacts_memo");
		return $query->num_rows(); 	
	}

	function contact_count($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$query = $this->db->get("contacts_action");
		return $query->num_rows(); 	
	}

}

/* End of file mmemo.php */
/* Location: ./application/models/mmemo.php */