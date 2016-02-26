<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mnews Model Class
 *
 * @author	Dejung Kang
 */
class Mnews extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 블로그 글 추가
	 */
	function insert($param){
		$this->db->insert("news",$param);
		return $this->db->insert_id();
	}

	/**
	 * 블로그 글 수정
	 */
	function update($param, $id){
		$this->db->where("id",$id);
		$this->db->update("news",$param);
	}

	/**
	 * 조회수 증가
	 */
	function view($id){
		$this->db->where("id",$id);
		$this->db->set("viewcnt","viewcnt+1",false);
		$this->db->update("news");
	}

	function get($id){
		$this->db->select("news.*");
		$this->db->select("news_category.name as category_name");
		$this->db->where("news.id",$id);
		$this->db->select("members.name as member_name, members.email as member_email, members.phone as member_phone");
		$this->db->join("news_category","news_category.id=news.category");
		$this->db->join("members","news.member_id=members.id");
		$query = $this->db->get("news");
		return $query->row();
	}

	/**
	 * 최신 글목록 가져오기
	 */
	function get_last($category_id="all", $cnt=5){
		$this->db->select("id,title,thumb_name,content");

		if($category_id!="all"){
			$this->db->where("category",$category_id);
		}

		$this->db->where("is_activated","1");
		$this->db->order_by("date","desc");
		$this->db->limit($cnt);
		$query = $this->db->get("news");
		return $query->result();
	}

	function get_total_count($category, $type="front"){
		if($type=="front"){
			$this->db->where("news.is_activated","1");
		}
		if($category!="0"){
			$this->db->where("news.category",$category);
		}
		$query = $this->db->get("news");
		return $query->num_rows(); 
	}

	function get_list($category, $num="", $offset="", $type="front"){
		$this->db->select("news.*");
		$this->db->select("news_category.name");
		$this->db->select("members.name as member_name");
		$this->db->select(" (select count(*) from news_comment where news_id=news.id) as cnt ");
		if($type=="front"){
			$this->db->where("news.is_activated","1");
		}
		if($category!="0"){
			$this->db->where("news.category",$category);
		}
		$this->db->join("members","members.id=news.member_id");
		$this->db->join("news_category","news_category.id=news.category");
		$this->db->order_by("date","desc");
		if($num!=""){
			$query = $this->db->get("news",$num, $offset);
		}
		else{
			$query = $this->db->get("news");
		}
		return $query->result();
	}

	/**
	 * 매물 우측 뉴스 정보
	 */
	function get_right_list(){
		$this->db->where("is_activated","1");
		$this->db->where("product_print","Y");
		$this->db->order_by("date","desc");
		$query = $this->db->get("news");
		return $query->result();
	}

	/**
	 * 홈 슬라이드를 위해서 하나의 카테고리에 대한 전체 글 목록을 가져온다.
	 */
	function get_list_all($category){
		$this->db->select("news.*");
		$this->db->where("news.is_activated","1");
		$this->db->where("news.category",$category);
		$this->db->order_by("news.date","desc");
		$query = $this->db->get("news");
		return $query->result();
	}

	/*** main ***/
	function get_recent($id="0"){
		$this->db->select("news.*");
		$this->db->where("is_activated","1");
		if($id!="0"){
			$this->db->where("id <>",$id);
		}
		$this->db->limit(8);
		$this->db->order_by("date","desc");
		$query = $this->db->get("news");
		return $query->result();	
	}

	function delete_news($id){
		$this->db->where("id",$id);
		$this->db->delete("news");
	}

	function change($param, $id){
		$this->db->where("id", $id);
		$this->db->update("news", $param);
	}

	function change_area_news($delete_id,$change_id){
		$this->db->set("member_id",$change_id);
		$this->db->where("member_id",$delete_id);
		$this->db->update("news");
	}

	function update_news($id){
		$this->db->set("is_blog","is_blog+1",false);
		$this->db->where("id",$id);
		$this->db->update("news");
	}

	function delete_thumb_image($new_id){
		$param = Array(
			"thumb_name" => ""	
		);
		$this->db->where("id",$new_id);
		$this->db->update("news",$param);
	}

	function insert_attachment($param){
		$this->db->insert("news_attachment",$param);
	}

	function get_attachment($id){
		$this->db->where("id",$id);
		$query = $this->db->get("news_attachment");
		return $query->row();	
	}

	function get_attachment_list($news_id){
		$this->db->where("news_id",$news_id);
		$query = $this->db->get("news_attachment");
		return $query->result();
	}

	function delete_attachment($id){
		$this->db->where("id",$id);
		$this->db->delete("news_attachment");		
	}

	function delete_all_attachment($news_id){
		$this->db->where("news_id",$news_id);
		$this->db->delete("news_attachment");		
	}
}

/* End of file mnews.php */
/* Location: ./application/models/mnews.php */