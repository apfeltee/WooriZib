<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpaysms extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("pay_sms",$param);
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("pay_sms",$param);
	}

	function get($id){
		$this->db->where("id",$id);
		$this->db->from("pay_sms");
		$query = $this->db->get();
		return $query->row();
	}

	function get_total(){
		$this->db->where("state","Y");
		return $this->db->count_all_results("pay_sms"); 
	}

	function get_list(){
		$this->db->where("state","Y");
		$this->db->order_by("payed_date","desc");
		$result = $this->db->get("pay_sms");
		return $result->result();
	}
}

/* End of file mpaysms.php */
/* Location: ./application/models/mpaysms.php */