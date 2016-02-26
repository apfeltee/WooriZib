<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 블로그 카테고리
 *
 * @author	Dejung Kang
 */
class Mportfoliocategory extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("component_portfolio_category");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 2015-02-15 새로 입력되는 카테고리의 정렬 순서값인 최대값+1을 반환해준다.
	 */
	function get_last_sorting(){
		$this->db->select_max("sorting");
		$this->db->from("component_portfolio_category");
		$query = $this->db->get();
		return $query->row()->sorting+1;
	}

	function insert($param){
		$this->db->insert("component_portfolio_category",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("component_portfolio_category",$param);
	}

	function get_list(){
		$this->db->from("component_portfolio_category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_array(){
		$this->db->from("component_portfolio_category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result_array();
	}

	function get_list_cnt(){
		$this->db->select("component_portfolio_category.name,component_portfolio_category.id");
		$this->db->select(" (select count(*) from blogs where category=component_portfolio_category.id and is_activated='1') as cnt");
		$this->db->from("component_portfolio_category");
		$result = $this->db->get();
		return $result->result();	
	}

	/**
	 * 자신 이외의 목록을 가져온다. (삭제 시 변경을 위해서)
	 */
	function get_others($id){
		$this->db->from("component_portfolio_category");
		$this->db->where("id <> ",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();	
	}

	function change_portfolio_category($delete_id,$change_id){
		$this->db->set("category",$change_id);
		$this->db->where("category",$delete_id);
		$this->db->update("component_portfolio");
	}

	function delete_portfolio_category($id){
		$this->db->where("id",$id);
		$this->db->delete("component_portfolio_category");
	}
}

/* End of file mcategory.php */
/* Location: ./application/models/mcategory.php */