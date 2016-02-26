<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 블로그 카테고리
 *
 * @author	Dejung Kang
 */
class Mnewscategory extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("news_category");
		$query = $this->db->get();
		return $query->row();
	}

	function insert($param){
		$this->db->insert("news_category",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("news_category",$param);
	}

	function get_list(){
		$this->db->from("news_category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_array(){
		$this->db->from("news_category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result_array();
	}

	function get_list_cnt(){
		$this->db->select("news_category.name,news_category.id");
		$this->db->select(" (select count(*) from news where category=news_category.id and is_activated='1') as cnt");
		$this->db->from("news_category");
		$result = $this->db->get();
		return $result->result();	
	}

	/**
	 * 자신 이외의 목록을 가져온다. (삭제 시 변경을 위해서)
	 */
	function get_others($id){
		$this->db->from("news_category");
		$this->db->where("id <> ",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();	
	}

	function change_area_products($delete_id,$change_id){
		$this->db->set("category",$change_id);
		$this->db->where("category",$delete_id);
		$this->db->update("news");
	}

	function delete_news($id){
		$this->db->where("id",$id);
		$this->db->delete("news_category");
	}
}

/* End of file mcategory.php */
/* Location: ./application/models/mcategory.php */