<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mblog Model Class
 *
 * @author	Dejung Kang
 */
class Mportfolio extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 블로그 글 추가
	 */
	function insert($param){
		$this->db->insert("component_portfolio",$param);
		return $this->db->insert_id();
	}

	/**
	 * 블로그 글 수정
	 */
	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("component_portfolio",$param);
	}

	/**
	 * 조회수 증가
	 */
	function view($id){
		$this->db->where("id",$id);
		$this->db->set("viewcnt","viewcnt+1",false);
		$this->db->update("component_portfolio");
	}

	function get($id){
		$this->db->select("component_portfolio.*");
		$this->db->select("component_portfolio_category.name as category_name");
		$this->db->where("component_portfolio.id",$id);
				$this->db->join("component_portfolio_category","component_portfolio_category.id=component_portfolio.category");
		
		$query = $this->db->get("component_portfolio");
		return $query->row();
	}

	/**
	 * 해당 카테고리의 마지막 작성된 글을 가져온다.
	 */
	function get_last($category_id){
		$this->db->select("id,title,thumb_name,content");
		$this->db->where("category",$category_id);
		$this->db->where("is_activated","1");
		$this->db->order_by("date","desc");
		$this->db->limit(1);
		$query = $this->db->get("component_portfolio");
		return $query->row();
	}

	function get_total_count($category, $type="front"){
		if($type=="front"){
			$this->db->where("component_portfolio.is_activated","1");
		}
		if($category!="0"){
			$this->db->where("component_portfolio.category",$category);
		}
		$query = $this->db->get("component_portfolio");
		return $query->num_rows(); 
	}

	function get_list($category, $num, $offset,$type="front"){
		$this->db->select("component_portfolio.*");
		$this->db->select("component_portfolio_category.name");
		//$this->db->select(" (select count(*) from blog_comment where blog_id=component_portfolio.id) as cnt ");
		if($type=="front"){
			$this->db->where("component_portfolio.is_activated","1");
		}
		if($category!="0"){
			$this->db->where("component_portfolio.category",$category);
		}
		
		$this->db->join("component_portfolio_category","component_portfolio_category.id=component_portfolio.category");
		$this->db->order_by("date","desc");
		$query = $this->db->get("component_portfolio",$num, $offset);
		return $query->result();
	}

	/*** main ***/
	function get_recent($id="0"){
		$this->db->select("component_portfolio.*");
		$this->db->where("is_activated","1");
		if($id!="0"){
			$this->db->where("id <>",$id);
		}
		$this->db->limit(5);
		$this->db->order_by("date","desc");
		$query = $this->db->get("component_portfolio");
		return $query->result();	
	}

	function romove($id){
		$this->db->where("id",$id);
		$this->db->delete("component_portfolio");
	}

	function change($param, $id){
		$this->db->where("id", $id);
		$this->db->update("component_portfolio", $param);
	}

	function update_blog($id){
		$this->db->set("is_blog","is_blog+1",false);
		$this->db->where("id",$id);
		$this->db->update("component_portfolio");
	}
}

/* End of file mblog.php */
/* Location: ./application/models/mblog.php */