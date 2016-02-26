<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mconcern Model Class
 *
 * @brief 	기존에는 Msms로 사용했었는데 용어가 애매모호해서 Mconcern으로 변경하였습니다.
 * @author	Kang,Dejung
 */
class Mconcern extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("concern_log",$param);
	}

}

/* End of file mconcern.php */
/* Location: ./application/models/mconcern.php */