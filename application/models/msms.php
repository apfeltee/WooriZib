<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Msms Model Class
 *
 * @author	Dejung Kang
 */
class Msms extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("sms_log",$param);
	}

}

/* End of file msms.php */
/* Location: ./application/models/msms.php */