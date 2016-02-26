<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mviral Model Class
 *
 * @author	Dejung Kang
 */
class Mviral extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_proverb(){
		$this->db->order_by('id', 'RANDOM');
	    $this->db->limit(1);
	    $query = $this->db->get('viral_proverb');
		return $query->row();
	}

	function get_statement(){
		$this->db->order_by('id', 'RANDOM');
	    $this->db->limit(1);
	    $query = $this->db->get('viral_statement');
		return $query->row();
	}

	function get_youtube(){
		$this->db->order_by('id', 'RANDOM');
	    $this->db->limit(1);
	    $query = $this->db->get('viral_youtube');
		return $query->row();
	}

}

/* End of file mviral.php */
/* Location: ./application/models/mviral.php */