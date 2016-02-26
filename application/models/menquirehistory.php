<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menquirehistory Model Class
 *
 * @author	Dejung Kang
 */
class Menquirehistory extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($enquire_id,$data_id,$type,$action,$title){
		$param = Array(
					"enquire_id"=>$enquire_id,
					"data_id"=>$data_id,
					"member_id"=>$this->session->userdata("admin_id"),
					"type"=>$type,
					"action"=>$action,
					"title"=>$title,
					"regdate"=>date('Y-m-d H:i:s')
				);

		$this->db->insert("enquire_history",$param);
	}

	function get_total_count($type){
		if($type!="0"){
			$this->db->where("enquire_history.type",$type);
		}
		$query = $this->db->get("enquire_history");
		return $query->num_rows(); 		
	}

	function get_list($type, $num, $offset){
		$this->db->select("enquire_history.*,members.name as member_name,members.profile");
		$this->db->select("enquire.name");
		if($type!="0"){
			$this->db->where("enquire_history.type",$type);
		}
		$this->db->join("enquire","enquire.id=enquire_history.enquire_id");
		$this->db->join("members","members.id=enquire_history.member_id");
		$this->db->order_by("enquire_history.regdate","desc");
		$query = $this->db->get("enquire_history",$num, $offset);
		return $query->result();
	}

	function delete_log($contacts_id){
		$this->db->where("enquire_id",$contacts_id);
		$this->db->delete("enquire_history");
	}

	function delete_item($type, $id){
		$this->db->where("type",$type);
		$this->db->where("data_id",$id);
		$this->db->delete("enquire_history");	
	}
}

/* End of file menquirehistory.php */
/* Location: ./application/models/menquirehistory.php */