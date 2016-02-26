<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menquirememo Model Class
 *
 * @author	Dejung Kang
 */
class Menquirememo extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("enquire_memo");
		$query = $this->db->get();
		return $query->row();
	}

	function get_action($id){
		$this->db->where("id",$id);
		$this->db->from("enquire_action");
		$query = $this->db->get();
		return $query->row();		
	}

	function insert($param){
		$this->db->insert("enquire_memo",$param);
		return $this->db->insert_id();
	}

	function insert_action($param){
		$this->db->insert("enquire_action",$param);
		return $this->db->insert_id();
	}

	function get_list($id){
		$this->db->select("enquire_memo.*,members.name,members.profile");
		$this->db->where("enquire_memo.enquire_id",$id);
		$this->db->join("members","members.id=enquire_memo.member_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("enquire_memo");
		return $query->result();
	}

	function get_action_list($id){
		$this->db->select("enquire_action.*,members.name,members.profile");
		$this->db->where("enquire_action.enquire_id",$id);
		$this->db->join("members","members.id=enquire_action.member_id");
		$this->db->order_by("regdate","desc");
		$query = $this->db->get("enquire_action");
		return $query->result_array();	
	}

	function get_month_action($start,$end,$calendar_search=""){
		$this->db->select("enquire.name");
		$this->db->select("enquire_action.*");
		$this->db->select("members.color as color");
		$this->db->where("enquire_action.actiondate <",$end);
		$this->db->where("enquire_action.actiondate >=",$start);

		$type = array();
		if($calendar_search["call"]=="on"){
			$type[] = "enquire_action.type = 'call'";
		}

		if($calendar_search["meeting"]=="on"){
			$type[] = "enquire_action.type = 'meeting'";
		}

		if($calendar_search["etc"]=="on"){
			$type[] = "enquire_action.type = 'etc'";
		}
		if($type){
			$this->db->where("(".implode(" OR ",$type).")", NULL, FALSE);
		}

		if($calendar_search["member_id"]){
			$this->db->where_in("enquire.member_id",$calendar_search["member_id"]);
		}
		$this->db->join("enquire","enquire.id=enquire_action.enquire_id");
		$this->db->join("members","members.id=enquire.member_id");
		$query = $this->db->get("enquire_action");

		return $query->result();		
	}

	function get_month_memo($start,$end,$calendar_search=""){
		$this->db->select("enquire.name");
		$this->db->select("enquire_memo.*");
		$this->db->select("members.color as color");
		$this->db->where("enquire_memo.regdate <",$end);
		$this->db->where("enquire_memo.regdate >=",$start);
		if($calendar_search["member_id"]){
			$this->db->where_in("enquire.member_id",$calendar_search["member_id"]);
		}
		$this->db->join("enquire","enquire.id=enquire_memo.enquire_id");
		$this->db->join("members","members.id=enquire.member_id");
		$query = $this->db->get("enquire_memo");
		return $query->result();		
	}

	/**
	 * 의뢰하기의 메모를 삭제한다.
	 */
	function delete_memo($id){
		$this->db->where("id",$id);
		$this->db->delete("enquire_memo");
	}

	function delete_action($id){
		$this->db->where("id",$id);
		$this->db->delete("enquire_action");
	}

	function delete_memo_enquire_id($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$this->db->delete("enquire_memo");
	}

	function delete_action_enquire_id($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$this->db->delete("enquire_action");
	}

	function memo_count($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$query = $this->db->get("enquire_memo");
		return $query->num_rows(); 	
	}

	function contact_count($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$query = $this->db->get("enquire_action");
		return $query->num_rows(); 	
	}
}

/* End of file menquirememo.php */
/* Location: ./application/models/menquirememo.php */