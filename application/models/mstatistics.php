<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mstatistics Model Class
 *
 * @author	Dejung Kang
 */
class Mstatistics extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_today_site_visit(){
		$this->db->select("DATE_FORMAT(date,'%H') as h, count(*) as cnt",false);
		$this->db->where("date >",date("Y-m-d 00:00:00"));
		$this->db->where("session_cnt","1");
		$this->db->group_by("h");
		$query = $this->db->get("log_site");
		return $query->result();
	}

	function get_today_blog_visit(){
		$this->db->select("DATE_FORMAT(date,'%H') as h, count(*) as cnt",false);
		$this->db->where("date >",date("Y-m-d 00:00:00"));
		$this->db->group_by("h");
		$query = $this->db->get("log_blog");
		return $query->result();
	}

	function get_month_site_visit(){
		$this->db->select("DATE_FORMAT(date,'%Y %m-%d') as h, count(*) as cnt",false);
		$this->db->where("date BETWEEN (CURDATE() - INTERVAL 30 DAY) AND (CURDATE()+ INTERVAL 1 DAY)");
		$this->db->where("session_cnt","1");
		$this->db->group_by("h");
		$query = $this->db->get("log_site");
		return $query->result();	
	}

	function get_month_blog_visit(){
		$this->db->select("DATE_FORMAT(date,'%Y %m-%d') as h, count(*) as cnt",false);
		$this->db->where("date BETWEEN (CURDATE() - INTERVAL 30 DAY) AND (CURDATE()+ INTERVAL 1 DAY)");
		$this->db->group_by("h");
		$query = $this->db->get("log_blog");
		return $query->result();	
	}

}

/* End of file mstatistics.php */
/* Location: ./application/models/mstatistics.php */