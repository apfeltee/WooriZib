<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menquirecontract Model Class
 *
 * @author	Dejung Kang
 */
class Menquirecontract extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("enquire_contract");
		$query = $this->db->get();
		return $query->row();
	}

	function insert($param){
		$this->db->insert("enquire_contract",$param);
		return $this->db->insert_id();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("enquire_contract",$param);
	}

	function get_list($id){
		$this->db->where("enquire_id",$id);
		$this->db->order_by("date","desc");
		$query = $this->db->get("enquire_contract");
		return $query->result();
	}

	function delete_contract($id){
		$this->db->where("id",$id);
		$this->db->delete("enquire_contract");
	}

	function delete_contract_contacts_id($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$this->db->delete("enquire_contract");
	}

	function get_month_contract($start,$end,$calendar_search=""){
		$this->db->select("enquire.name");
		$this->db->select("enquire_contract.*");
		$this->db->select("members.color as color");
		$this->db->where("enquire_contract.date <",$end);
		$this->db->where("enquire_contract.date >=",$start);
		if($calendar_search["member_id"]){
			$this->db->where_in("enquire.member_id",$calendar_search["member_id"]);
		}
		$this->db->join("enquire","enquire.id=enquire_contract.enquire_id");
		$this->db->join("members","members.id=enquire.member_id");
		$query = $this->db->get("enquire_contract");
		return $query->result();		
	}

	function contract_count($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$query = $this->db->get("enquire_contract");
		return $query->num_rows(); 		
	}
}

/* End of file menquirecontract.php */
/* Location: ./application/models/menquirecontract.php */