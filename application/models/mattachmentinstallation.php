<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mattachment Model Class
 *
 * @author	Dejung Kang
 */
class Mattachmentinstallation extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($installation_id, $id){
		
		$where = Array(
					"installation_id"=>$installation_id,
					"id"=>$id
				);

		$this->db->where($where);
		$result=$this->db->get("installation_attachment");
		return $result->row();

	}

	function insert($param){

		$this->db->insert("installation_attachment",$param);

	}

	function get_list($installation_id){

		$this->db->where("installation_id",$installation_id);
		$result = $this->db->get("installation_attachment");
		return $result->result();

	}

	function remove($installation_id, $id){

		$where = Array(
					"installation_id"=>$installation_id,
					"id"=>$id
				);

		$this->db->where($where);
		$this->db->delete("installation_attachment");	
	}
}

/* End of file mattachmentinstallation.php */
/* Location: ./application/models/mattachmentinstallation.php */