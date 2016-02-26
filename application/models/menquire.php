<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Menquire Model Class
 *
 * @author	Dejung Kang
 */
class Menquire extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 홈에서 10개를 보여준다.
	 */
	function get_home(){
		$this->db->limit(10);
		$query = $this->db->get("enquire");
		return $query->result();
	}

	function add($param){
		$this->db->insert("enquire",$param);
	}

	/**
	 * 전체 문의 수 반환
	 */
	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("enquire");
		return $query->row(); 
	}

	/**
	 * 전체 문의 수 반환
	 */
	function get_total_count($search){
		if(is_array($search)){
			if($search["status"]!="all")	$this->db->where("status",$search["status"]);
			if($search["gubun"]!="")	$this->db->where("gubun",$search["gubun"]);
			if($search["type"]!="")		$this->db->where("type",$search["type"]);
			if($search["category"]!="")		$this->db->where_in("category",$search["category"]);
			if($search["member_id"]!="")$this->db->where("member_id",$search["member_id"]);
			if($search["keyword"]!="")	{
				$this->db->where("(name LIKE '%".$search["keyword"]."%' OR
				phone LIKE '%".$search["keyword"]."%' OR
				location LIKE '%".$search["keyword"]."%' OR
				price LIKE '%".$search["keyword"]."%' OR
				area LIKE '%".$search["keyword"]."%' OR
				content LIKE '%".$search["keyword"]."%' OR
				work LIKE '%".$search["keyword"]."%')");
			}
		}
		$query = $this->db->get("enquire");
		return $query->num_rows(); 
	}

	function get_list($search, $num, $offset){
		$this->db->select("enquire.*");
		$this->db->select("members.name as member_name");
		if(is_array($search)){
			if($search["status"]!="all")	$this->db->where("enquire.status",$search["status"]);
			if($search["gubun"]!="")	$this->db->where("enquire.gubun",$search["gubun"]);
			if($search["type"]!="")		$this->db->where("enquire.type",$search["type"]);
			if($search["category"]!="")		$this->db->where_in("category",$search["category"]);
			if($search["member_id"]!="")$this->db->where("enquire.member_id",$search["member_id"]);
			if($search["keyword"]!="")	{
				$this->db->where("(enquire.name LIKE '%".$search["keyword"]."%' OR
				enquire.phone LIKE '%".$search["keyword"]."%' OR
				enquire.location LIKE '%".$search["keyword"]."%' OR
				enquire.price LIKE '%".$search["keyword"]."%' OR
				enquire.area LIKE '%".$search["keyword"]."%' OR
				enquire.content LIKE '%".$search["keyword"]."%' OR
				enquire.work LIKE '%".$search["keyword"]."%')");
			}
		}
		$this->db->join("members","members.id=enquire.member_id","LEFT OUTER");
		$this->db->order_by("date","desc");
		$query = $this->db->get("enquire",$num, $offset);
		return $query->result_array();
	}

	function get_list_obj($status="",$count=false){
		$this->db->select("enquire.*");
		if($status!="all"){
			$this->db->where("status",$status);
		}
		$this->db->order_by("date","desc");
		$query = $this->db->get("enquire");
		if($count){
			return $query->num_rows();
		}
		else{
			return $query->result();
		}
	}

	function get_list_in($enquire_ids){
		$this->db->where_in("id",$enquire_ids);
		$query = $this->db->get("enquire");
		return $query->result();		
	}

	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("enquire",$param);
	}
	
	/**
	 * 회원 정보 삭제
	 */
	function delete_enquire($id){
		$this->db->where("id",$id);
		$this->db->delete("enquire");
	}

	function enquire_count($status="",$search){
		if($status!="all"){
			$this->db->where("status",$status);
		}

		if(is_array($search)){
			if($search["gubun"]!="")	$this->db->where("gubun",$search["gubun"]);
			if($search["type"]!="")		$this->db->where("type",$search["type"]);
			if($search["category"]!="")		$this->db->where_in("category",$search["category"]);
			if($search["member_id"]!="")$this->db->where("member_id",$search["member_id"]);
			if($search["keyword"]!="")	{
				$this->db->where("(name LIKE '%".$search["keyword"]."%' OR
				phone LIKE '%".$search["keyword"]."%' OR
				location LIKE '%".$search["keyword"]."%' OR
				price LIKE '%".$search["keyword"]."%' OR
				area LIKE '%".$search["keyword"]."%' OR
				content LIKE '%".$search["keyword"]."%' OR
				work LIKE '%".$search["keyword"]."%')");
			}
		}

		$query = $this->db->get("enquire");
		return $query->num_rows(); 
	}

	function status_category($all=false){
		if(!$all){
			$this->db->where("valid","Y");
		}
		$this->db->order_by("sorting","asc");
		$query = $this->db->get("enquire_status");
		return $query->result();	
	}

	function sorting_update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("enquire_status",$param);
	}

	function status_update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("enquire_status",$param);
	}

	function status_get($name){
		$this->db->where("name",$name);
		$query = $this->db->get("enquire_status");
		return $query->row(); 
	}	
}

/* End of file menquire.php */
/* Location: ./application/models/menquire.php */