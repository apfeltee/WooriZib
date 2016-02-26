<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mparentaddress Model Class
 *
 * @author	Dejung Kang
 */
class Mparentaddress extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}


	function get($id){
		$this->db->where("id",$id);
		$query = $this->db->get("parent_address");
		return $query->row();
	}

}

/* End of file mparentaddress.php */
/* Location: ./application/models/mparentaddress.php */