<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mportfoliocomment Model Class
 *
 * @author	Dejung Kang
 */
class Mportfoliocomment extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 댓글 목록
	 */
	function get_list($portfolio_id){
		$this->db->where("portfolio_id",$portfolio_id);
		$this->db->order_by("id","desc");
		$this->db->order_by("step_id","asc");
		$this->db->from("component_portfolio_comment");
		$result = $this->db->get();
		return $result->result_array();
	}

	function get($id,$step_id){
		$this->db->where("id",$id);
		$this->db->where("step_id",$step_id);
		$result = $this->db->get("component_portfolio_comment");
		return $result->row_array();
	}

	function insert($param){
		$this->db->insert("component_portfolio_comment",$param);
		return $this->db->insert_id();
	}

	function update($param, $where){
		$this->db->where($where);
		$this->db->update("component_portfolio_comment",$param);
	}

	/**
	 * 댓글 삭제
	 */
	function delete_comment($where){
		$this->db->where($where);
		$this->db->delete("component_portfolio_comment");
	}

	/**
	 * 댓글 아이디 최대값 가져오기
	 */
	function get_max_id($portfolio_id){
		$this->db->select_max("id");
		$this->db->where("portfolio_id",$portfolio_id);
		$result = $this->db->get("component_portfolio_comment");
		return $result->row()->id;
	}

	function get_comment_step_id($portfolio_id,$comment_id){
		$this->db->select_max("step_id");
		$this->db->where("portfolio_id",$portfolio_id);
		$this->db->where("id",$comment_id);
		$result = $this->db->get("component_portfolio_comment");
		return $result->row()->step_id;	
	}

	/**
	 * 하위 댓글 갯수 가져오기
	 */
	function get_child_count($id,$portfolio_id){
		$this->db->where("id",$id);
		$this->db->where("portfolio_id",$portfolio_id);
		$this->db->where_not_in("step_id",0);
		$result = $this->db->count_all_results("component_portfolio_comment");
		return $result;
	}

	/**
	 * 상위 댓글 삭제여부 변경
	 */
	function update_delete_flag($param,$where){
		$this->db->where($where);
		$this->db->update("component_portfolio_comment",$param);
	}

}

/* End of file mportfoliocomment.php */
/* Location: ./application/models/mportfoliocomment.php */