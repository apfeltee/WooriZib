<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mhistory Model Class
 *
 * @author	Dejung Kang
 */
class Mhistory extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function insert($contacts_id,$data_id,$type,$action,$title){
		$param = Array(
					"contacts_id"=>$contacts_id,
					"data_id"=>$data_id,
					"member_id"=>$this->session->userdata("admin_id"),
					"type"=>$type,
					"action"=>$action,
					"title"=>$title,
					"regdate"=>date('Y-m-d H:i:s')
				);

		$this->db->insert("contacts_history",$param);
	}

	function get_total_count($type){
		if($type!="0"){
			$this->db->where("contacts_history.type",$type);
		}
		$query = $this->db->get("contacts_history");
		return $query->num_rows(); 		
	}

	function get_list($type, $num, $offset){
		$this->db->select("contacts_history.*,members.name as member_name,members.profile");
		$this->db->select("contacts.name,contacts.organization,contacts.role");
		if($type!="0"){
			$this->db->where("contacts_history.type",$type);
		}
		$this->db->join("contacts","contacts.id=contacts_history.contacts_id");
		$this->db->join("members","members.id=contacts_history.member_id");
		$this->db->order_by("contacts_history.regdate","desc");
		$query = $this->db->get("contacts_history",$num, $offset);
		return $query->result();
	}

	function get_list_by_session($id){
		$this->db->distinct();
		$this->db->select("products.*");
		$this->db->select("CONCAT(address.sido, ' ', address.gugun, ' ', address.dong) AS address_name", FALSE); 
		$this->db->select("gallery.filename as thumb_name, gallery.id as gallery_id");
		$this->db->where("log_site.session_id",$id);
		$this->db->join("log_site","log_site.data_id=products.id and log_site.type='product'");
		$this->db->join("gallery","gallery.product_id=products.id and gallery.sorting=1","left");
		$this->db->join("address","address.id=products.address_id");
		$this->db->order_by("log_site.date","desc");
		$query = $this->db->get("products");
		return $query->result();
	}

	function delete_log($contacts_id){
		$this->db->where("contacts_id",$contacts_id);
		$this->db->delete("contacts_history");
	}

	function delete_item($type, $id){
		$this->db->where("type",$type);
		$this->db->where("data_id",$id);
		$this->db->delete("contacts_history");	
	}
}

/* End of file mhistory.php */
/* Location: ./application/models/mhistory.php */