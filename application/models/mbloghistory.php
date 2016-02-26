<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mbloghistory Model Class
 *
 * @author	Dejung Kang
 */
class Mbloghistory extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function ping(){
		$this->db->from("bloghistory");
		$this->db->limit(1);
		$this->db->get();
	}

	/**
	 * 블로그 이력 추가
	 */
	function insert($param){
		$this->db->insert("bloghistory",$param);
		return $this->db->insert_id();
	}

	function insert_daum($param){
		$this->db->insert("bloghistory_daum",$param);
		return $this->db->insert_id();
	}

	/**
	 * 블로그 히스토리 수정
	 */
	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("bloghistory",$param);
	}

	/**
	 * 조회수 증가
	 */
	function view($id){
		$this->db->where("id",$id);
		$this->db->set("viewcnt","viewcnt+1",false);
		$this->db->update("bloghistory");
	}

	function get_list($id,$type,$blog_id){
		$this->db->where("data_id",$id);
		$this->db->where("type",$type);
		$this->db->where("blog_id",$blog_id);
		$this->db->order_by("date","desc");
		$query = $this->db->get("bloghistory");
		return $query->result();
	}

	function get_list_daum($id,$type,$blog_name,$blog_category){
		$this->db->where("data_id",$id);
		$this->db->where("type",$type);
		$this->db->where("blog_name",$blog_name);
		$this->db->where("blog_category",$blog_category);
		$this->db->order_by("date","desc");
		$query = $this->db->get("bloghistory_daum");
		return $query->result();
	}
}

/* End of file mbloghistory.php */
/* Location: ./application/models/mbloghistory.php */