<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mhope Model Class
 *
 * @author	Dejung Kang
 */
class Mhope extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 관심 추가
	 */
	function add($param){
		$this->db->insert("hope",$param);
	}

	/**
	 * 관심 삭제
	 */
	function remove($id, $session_id){
		$this->db->where("session_id",$session_id);
		$this->db->where("product_id",$id);
		$this->db->delete("hope");
	}

	function check($id){
		$this->db->where("product_id",$id);
		return $this->db->count_all_results("hope");
	}

	function get_total($session_id){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->where("hope.session_id",$session_id);
		$this->db->join("hope","hope.product_id=products.id");
		$this->db->order_by("hope.date","desc");
		return $this->db->count_all_results("products");	
	}

	function get_list($session_id, $num, $offset){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);	
		$this->db->select("members.name as member_name, members.phone as member_phone, members.profile as member_profile");	
		$this->db->where("hope.session_id",$session_id);
		$this->db->join("hope","hope.product_id=products.id");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$this->db->join("address","address.id=products.address_id");
		$this->db->order_by("hope.date","desc");
		$this->db->join("members","members.id=products.member_id");		
		$query = $this->db->get("products",$num, $offset);
		return $query->result();
	}

	function get_list_by_session($id){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);	
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->where("hope.session_id",$id);
		$this->db->join("hope","hope.product_id=products.id");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$this->db->join("address","address.id=products.address_id");
		$query = $this->db->get("products");
		return $query->result();
	}

	function get_list_by_member($id){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);	
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->where("hope.member_id",$id);
		$this->db->join("hope","hope.product_id=products.id");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$this->db->join("address","address.id=products.address_id");
		$query = $this->db->get("products");
		return $query->result();
	}

	/**
	 * 관심 분양 목록
	 */
	function get_list_installation($session_id, $num, $offset){
		$this->db->distinct();
		$this->db->select("installations.*");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun , ' ', address.dong) as address_name", FALSE);	
		$this->db->select("gallery_installation.filename as thumb_name, gallery_installation.id as gallery_id");
		$this->db->where("hope_installations.session_id",$session_id);
		$this->db->join("hope_installations","hope_installations.installation_id=installations.id");
		$this->db->join("gallery_installation","gallery_installation.installation_id=installations.id and gallery_installation.sorting=1","left");
		$this->db->join("address","address.id=installations.address_id");
		$query = $this->db->get("installations");
		return $query->result();
	}

	function remove_by_member($product_id, $member_id){
		$this->db->where("member_id",$id);
		$this->db->where("product_id",$product_id);
		$this->db->delete("hope");
	}

	function remove_by_session($product_id, $session_id){
		$this->db->where("session_id",$session_id);
		$this->db->where("product_id",$product_id);
		$this->db->delete("hope");
	}

	function convert($member_id, $session_id){
		$this->db->where("session_id",$session_id);
		$param = Array("member_id"=>$member_id);
		$this->db->update("hope",$param);
	}
}

/* End of file mhope.php */
/* Location: ./application/models/mhope.php */