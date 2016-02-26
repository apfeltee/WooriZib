<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpay extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function get_setting_list(){
		$this->db->from("pay_setting");
		$this->db->order_by("sorting","asc");
		$result = $this->db->get();
		return $result->result();
	}

	function sorting_update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pay_setting",$param);
	}

	function setting_get($id){
		$this->db->where("id",$id);
		$this->db->from("pay_setting");
		$query = $this->db->get();
		return $query->row();
	}

	function settting_insert($param){
		$this->db->insert("pay_setting",$param);
	}

	function settting_update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pay_setting",$param);
	}

	function delete_setting($id){
		$this->db->where("id",$id);
		$this->db->delete("pay_setting");
	}

	function insert($param){
		$this->db->insert("pay",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pay",$param);
	}

	/**
	 * 결제정보 리턴
	 */
	function get($id){
		$this->db->where("id",$id);
		$this->db->from("pay");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 결제상품 번호에 의한 결제정보 리턴
	 */
	function get_by_paysetting($member_id,$id){
		$this->db->where("member_id",$member_id);
		$this->db->where("pay_setting_id",$id);
		$this->db->from("pay");
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 회원의 현재 유효한 결제 정보
	 */
	function is_valid_pay($id){
		$this->db->where("member_id",$id);
		$this->db->where("state","Y");
		$this->db->where("`end_date` >= NOW()", NULL, FALSE);
		$this->db->from("pay");
		$this->db->order_by("start_date","asc");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * 회원의 최종적으로 유효한 결제 정보(미사용이면서 대기중)
	 */
	function last_valid_pay($id){
		$this->db->where("member_id",$id);
		$this->db->where("state","Y");
		$this->db->where("`end_date` >= NOW()", NULL, FALSE);
		$this->db->from("pay");
		$this->db->order_by("start_date","desc");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}

	function get_total($id){
		$this->db->where("member_id",$id);
		$this->db->where("state","Y");
		return $this->db->count_all_results("pay"); 
	}

	function get_list($id, $num="", $offset=""){
		$this->db->where("member_id",$id);
		$this->db->where("state","Y");
		$this->db->order_by("date","desc");
		$result = $this->db->get("pay", $num, $offset);
		return $result->result();
	}

	function get_list_all($id){
		$this->db->where("member_id",$id);
		$this->db->where("state","Y");
		$this->db->order_by("date","desc");
		$result = $this->db->get("pay");
		return $result->result();
	}

	function get_admin_total(){
		$this->db->where("state","Y");
		$this->db->join("members","members.id=pay.member_id");
		return $this->db->count_all_results("pay");
	}

	function get_admin_list($where="",$num="", $offset=""){
		$this->db->select("pay.*");
		$this->db->select("members.name, members.biz_name, members.email, members.type");

		if($where){
			if(isset($where['member'])) $this->db->where("pay.member_id",$where['member']);
			if(isset($where['date1'])) $this->db->where("DATE_FORMAT(pay.date, '%Y-%m-%d') >= '".$where['date1']."'");
			if(isset($where['date2'])) $this->db->where("DATE_FORMAT(pay.date, '%Y-%m-%d') <= '".$where['date2']."'");
		}

		$this->db->where("state","Y");
		$this->db->join("members","members.id=pay.member_id");
		$this->db->order_by("date","desc");
		$result = $this->db->get("pay",$num, $offset);
		return $result->result();
	}

	function member_pay_delete($member_id){
		$this->db->where("member_id",$member_id);
		$this->db->delete("pay");
	}

}

/* End of file mpay.php */
/* Location: ./application/models/mpay.php */