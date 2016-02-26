<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mcontactproduct Model Class
 *
 * @author	Dejung Kang
 */
class Mcontactproduct extends CI_Model {

	function __construct(){
		parent::__construct();
	}

	function insert($param){
		$this->db->insert("contacts_product",$param);
		return $this->db->insert_id();
	}

	function delete_contacts_product($product_id){
		$this->db->where("product_id",$product_id);
		$this->db->delete("contacts_product");
	}

	function get_list($product_id){
		$this->db->select("contacts_product.*");
		$this->db->select("contacts.name as name");
		$this->db->select("contacts.phone as phone");
		$this->db->where("contacts_product.product_id",$product_id);
		$this->db->order_by("contacts_product.date","desc");
		$this->db->join("contacts","contacts.id=contacts_product.contacts_id");
		$query = $this->db->get("contacts_product");
		return $query->result();
	}

	/**
	 * 고객의 상품 수
	 */
	function get_product_total($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$query = $this->db->get("contacts_product");
		return $query->num_rows(); 
	}

	/**
	 * 고객의 상품 목록
	 */
	function get_product_list($contacts_id){
		$this->db->select("products.*");
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->where("contacts_product.contacts_id",$contacts_id);
		$this->db->order_by("products.date","desc");
		$this->db->join("products","products.id=contacts_product.product_id");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$query = $this->db->get("contacts_product");
		return $query->result_array();
	}
}

/* End of file mcontactproduct.php */
/* Location: ./application/models/mcontactproduct.php */