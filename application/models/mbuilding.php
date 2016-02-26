<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mbuilding extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get($address){
		$this->db->where("address",$address);
		$this->db->from("building");
		$query = $this->db->get();
		return $query->row();
	}

	function get_id($id){
		$this->db->where("id",$id);
		$this->db->from("building");
		$query = $this->db->get();
		return $query->row();
	}

	function get_total_count(){
		$query = $this->db->get("building");
		return $query->num_rows();			
	}

	function insert($data){
		$this->db->insert_batch("building",$data);
	}

	function delete(){
		$this->db->where("1=1", NULL, FALSE);
		$this->db->delete("building");
	}

	function get_supremum($code_name){
		$this->db->where("code_name",$code_name);
		$this->db->from("building_supremum");
		$query = $this->db->get();
		return $query->row();	
	}

	function get_expense($kind,$grade){
		$this->db->where("kind",$kind);
		$this->db->where("grade",$grade);
		$this->db->from("building_expense");
		$query = $this->db->get();
		return $query->row();	
	}

	function get_building_limit($code_name){
		$this->db->where("code_name",$code_name);
		$this->db->from("building_limit");
		$this->db->order_by("id","asc");
		$result = $this->db->get();
		return $result->result();		
	}

	function get_enquire($id,$member_id=""){
		$this->db->select("building_enquire.*");
		$this->db->select("members.name as member_name, members.email as member_email, members.phone as member_phone");
		if($member_id){
			$this->db->where("members.id",$member_id);
		}
		$this->db->where("building_enquire.id",$id);
		$this->db->join("members","members.id=building_enquire.member_id");
		$this->db->from("building_enquire");
		$query = $this->db->get();
		return $query->row();
	}

	function enquire_insert($param){
		$this->db->insert("building_enquire",$param);
	}

	function get_enquire_count($member_id=""){
		if($member_id){
			$this->db->where("member_id",$member_id);
		}
		$query = $this->db->get("building_enquire");
		return $query->num_rows();
	}

	function get_enquire_list($num="", $offset="",$member_id=""){
		$this->db->select("building.*");
		$this->db->select("building_enquire.*");
		$this->db->select("members.name as member_name, members.email as member_email, members.phone as member_phone");
		$this->db->join("building","building.id=building_enquire.building_id");
		$this->db->join("members","members.id=building_enquire.member_id");
		if($member_id){
			$this->db->where("members.id",$member_id);
		}
        $this->db->order_by("building_enquire.date","desc");
		$query = $this->db->get("building_enquire",$num, $offset);
        return $query->result();
	}
}

/* End of file Mbuilding.php */
/* Location: ./application/models/Mbuilding.php */