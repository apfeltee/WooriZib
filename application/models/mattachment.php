<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mgallery Model Class
 *
 * @author	Dejung Kang
 */
class Mattachment extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($product_id, $id){
		
		$where = Array(
					"product_id"=>$product_id,
					"id"=>$id
				);

		$this->db->where($where);
		$result=$this->db->get("product_attachment");
		return $result->row();

	}

	function insert($param){

		$this->db->insert("product_attachment",$param);

	}

	function get_list($product_id){

		$this->db->where("product_id",$product_id);
		$result = $this->db->get("product_attachment");
		return $result->result();

	}

	function remove($product_id, $id){

		$where = Array(
					"product_id"=>$product_id,
					"id"=>$id
				);

		$this->db->where($where);
		$this->db->delete("product_attachment");	
	}

	function insert_estimate($param){
		$this->db->insert("building_estimate",$param);
	}

	function get_estimate($id){		
		$this->db->where("id",$id);
		$result=$this->db->get("building_estimate");
		return $result->row();

	}

	function get_estimate_list($enquire_id){
		$this->db->where("enquire_id",$enquire_id);
		$result = $this->db->get("building_estimate");
		return $result->result();
	}
}

/* End of file mattachment.php */
/* Location: ./application/models/mattachment.php */