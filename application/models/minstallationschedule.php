<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Minstallationschedule Model Class
 *
 * @author	Dejung Kang
 */
class Minstallationschedule extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("installation_schedule",$param);
		return $this->db->insert_id();
	}

	function get_list($id){
		$this->db->where("installation_id",$id);
		$this->db->order_by("id","asc");
		$query = $this->db->get("installation_schedule");
		return $query->result();
	}

	function delete($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("installation_schedule");
	}
}

/* End of file minstallationschedule.php */
/* Location: ./application/models/minstallationschedule.php */