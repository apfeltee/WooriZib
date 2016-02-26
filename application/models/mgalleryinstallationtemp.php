<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Mgalleryinstallationtemp Model Class
 *
 * @author	Dejung Kang
 */
class Mgalleryinstallationtemp extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get($id){
		$this->db->where("id",$id);
		$result = $this->db->get("gallery_installation_temp");
		return $result->row();	
	}

	function update($id,$param){
		$this->db->where("id",$id);
		$this->db->update("gallery_installation_temp",$param);
	}

	function get_sorting($id){
		$this->db->select_max('sorting');
		$this->db->where("member_id",$id);
		$result = $this->db->get("gallery_installation_temp");
		return $result->row()->sorting;
	}

	/**
	 * 매물의 사진들을 가져온다.
	 */
	function get_list($member_id){
		$this->db->where("member_id",$member_id);
		$this->db->order_by("sorting","asc");
		$result = $this->db->get("gallery_installation_temp");
		return $result->result_array();
	}

	function change_sorting($gallery_id,$sorting){
		$this->db->where("id",$gallery_id);
		$this->db->set("sorting",$sorting);
		$this->db->update("gallery_installation_temp");
	}

	/**
	 * 매물 사진을 등록한다.
	 */
	 function insert($param){
		$this->db->insert("gallery_installation_temp",$param);
	 }

	 function delete($id){
		$this->db->where("id",$id);
		$this->db->delete("gallery_installation_temp");
	 }

	 function delete_installation($id){
		$this->db->where("installation_id",$id);
		$this->db->delete("gallery_installation_temp");	 
	 }
}

/* End of file mgalleryinstallationtemp.php */
/* Location: ./application/models/mgalleryinstallationtemp.php */