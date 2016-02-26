<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mcategory Model Class
 *
 * @author	Dejung Kang
 */
class Mcategory extends CI_Model {

	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->load->model("Mconfig");
		$this->config = $this->Mconfig->get();
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("category");
		$query = $this->db->get();
		return $query->row();
	}

	function insert($param){
		$this->db->insert("category",$param);
		return $this->db->insert_id();
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("category",$param);
	}

	function get_list(){
		$this->db->where("valid","Y");
		$this->db->from("category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_list_array(){
		$this->db->where("valid","Y");
		$this->db->from("category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result_array();
	}

	function get_list_cnt(){

		$display_query = (!$this->config->COMPLETE_DISPLAY) ? "and is_finished='0'" : "";

		$this->db->select("category.name,category.name as text, category.id");
		$this->db->select(" (select count(*) from products where category=category.id and is_activated='1' and is_valid='1' ".$display_query.") as cnt");
		$this->db->where("valid","Y");
		$this->db->from("category");
		$result = $this->db->get();
		return $result->result();	
	}

	/**
	 * 유효 무효에 상관없이 모든 유형을 가져온다.
	 */
	function get_list_all(){
		$this->db->from("category");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	/**
	 * 자신 이외의 목록을 가져온다. (삭제 시 변경을 위해서)
	 */
	function get_others($id){
		$this->db->from("category");
		$this->db->where("id <> ",$id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();	
	}

	function change_area_products($delete_id,$change_id){
		$this->db->set("category",$change_id);
		$this->db->where("category",$delete_id);
		$this->db->update("products");
	}

	function delete_area($id){
		$this->db->where("id",$id);
		$this->db->delete("category");
	}

	function get_list_multi($val){
		$param = explode(",",$val);
		$this->db->where_in("id",$param);
		$result = $this->db->get("category");
		return $result->result();
	}

	/**
	 * 데이터 컨버전을 목적으로 만듬
	 * 이름으로 카테고리가 있는지 여부를 반환해 준다. 없으면 추가해 주기 위해서.
	 */
	function get_by_name($name){
		$this->db->where("name",$name);
		$query =  $this->db->get("category");
		return $query->row();
	}

	/**
	 *  폼 정보를 가져오는 함수
	 *
	 */
	function get_form($main_id){
		$this->db->where("id", $main_id);
		$this->db->from("config_form");
		$result = $this->db->get();
		return $result->row();
	}

	function insert_sub($param){
		$this->db->insert("category_sub",$param);
	}

	function update_sub($id,$param){
		$this->db->where("id",$id);
		$this->db->update("category_sub",$param);
	}

	function get_sub_last($main_id){
		$this->db->limit(1);
		$this->db->order_by("sorting","desc");
		$this->db->where("main_id", $main_id);
		$result = $this->db->get("category_sub");
		return (isset($result->row()->sorting)) ? $result->row()->sorting : 0;		
	}

	function get_sub_list($main_id){
		$this->db->where("main_id", $main_id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("category_sub");
		return $result->result();
	}

	function delete_sub($id){
		$this->db->where("id", $id);
		$this->db->delete("category_sub");
	}

}

/* End of file mcategory.php */
/* Location: ./application/models/mcategory.php */