<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msmshistory extends CI_Model {

    function __construct(){
        parent::__construct();
    }

	function get($id){
		$this->db->select("sms_history.*");
		$this->db->select("members.name as member_name, members.email as member_email");
		$this->db->where("sms_history.id",$id);
		$this->db->join("members","members.id=sms_history.member_id",'left');
		$query = $this->db->get("sms_history");
		return $query->row();
	}

    function insert($param){
        $this->db->insert("sms_history",$param);
        return $this->db->insert_id();
    }

    function get_total_count(){
        return $this->db->count_all_results("sms_history");
    }

    function get_list($num="", $offset="" ){
        $this->db->select("sms_history.*");
		$this->db->select("members.name as member_name, members.email as member_email");
		$this->db->join("members","members.id=sms_history.member_id",'left');
        $this->db->order_by("date","desc");
        $query = $this->db->get("sms_history",$num, $offset);
        return $query->result();
    }

	function get_three_minute($phone){
		$this->db->where("sms_to",$phone);
		$this->db->where("result","발송성공");
		$this->db->where("date > date_sub(NOW(), INTERVAL 3 minute)");
		$this->db->order_by("date","desc");
		$this->db->limit(1);
		$query = $this->db->get("sms_history");
		return $query->row();
	}

	function get_last($phone){
		$this->db->where("sms_to",$phone);
		$this->db->where("result","발송성공");
		$this->db->order_by("date","desc");
		$this->db->limit(1);
		$query = $this->db->get("sms_history");
		return $query->row();
	}
}

/* End of file msmshistory.php */
/* Location: ./application/models/msmshistory.php */